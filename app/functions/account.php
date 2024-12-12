<?php
class Account extends user
{
  function delete_account($id, $r)
  {
    if (!$this->validate_admin()) {
      return false;
    }
    if (!$r->validate_action(["account" => "delete"], true)) return;
    $account = $this->getall("account", "ID = ?", [$id]);
    if (!is_array($account)) {
      return false;
    }
    if (!$this->delete("account", "ID = ?", [$id])) {
      $this->message("Unable to delete account, Reload page and try again", "error", "json");
    }

    $return = [
      "message" => ["Success", "Account Deleted", "success"],
      "function" => ["removediv", "data" => ["#displayaccount-$id", "null"]]
    ];
    return json_encode($return);
  }

  function getOrders($start, $limit, $userID = null)
  {
    $where = "ID != ?";
    $data = [""];

    if (isset($_GET['orID']) && $_GET['orID'] != "") {
      $where .= "and ID = ?";
      $data[] = htmlspecialchars($_GET['orID']);
    }

    if ($userID == null && isset($_GET['userID'])) {
      $userID = htmlspecialchars($_GET['userID']);
    }


    if ($userID != null) {
      $where .= "and userID = ?";
      $data[] = $userID;
    }

    if (isset($_GET['type']) && $_GET['type'] != "") {
      $where .= "and order_type = ?";
      $data[] = htmlspecialchars($_GET['type']);
    }

    return $this->getall("orders", $where . " order by date desc LIMIT $start, $limit", $data, fetch: "all");
  }

  function displayOrder($order)
  {
    // die(var_dump($order));
    if ($order['order_type'] === "rentals" && empty($order['loginIDs'])) {
      return '';
    }

    $page = $order['order_type'] === "rentals" ? "rentals" : "orders";
    $idInfo = $order['order_type'] === "rentals" ? "accountID" : "ID";
    $id = $order[$idInfo];
    $c = new content();

    ob_start();
?>
<tr>
    <td>
        <!-- <a <?= $c->modal_attributes("modal?p=$page&action=view&$idInfo=$id", "Order Details") ?>
            class="btn btn-sm btn-primary">View</a> -->
        <a target="_BLANK" href='<?= "index?p=$page&action=view&$idInfo=$id" ?>' class="btn btn-sm btn-primary">View</a>
    </td>
    <td><?= $order['ID'] ?></td>
    <td>
        <?= $order['order_type'] === "account"
          ? $this->display_account_name($order['accountID'])
          : "<h5>" . ($order['loginIDs'] ?? "unbooked") . "</h5>"; ?>
    </td>
    <td><?= $this->money_format($order['amount'], currency) ?></td>
    <td><?= number_format($order['no_of_orders']) ?></td>
    <td><?= $this->date_format($order['date']) ?></td>
</tr>
<?php
    return ob_get_clean();
  }

  function fetch_login()
  {
    // die(var_dump($_GET));
    $start = $_POST['start'] ?? 0;
    $limit = $_POST['limit'] ?? 10;
    // echo $start;

    if (isset($_GET['is_sold']) && $_GET['is_sold'] == "sold_report" && $start > 0) {
      return;
    }
    $s = isset($_GET['s']) ? htmlspecialchars($_GET['s']) : "";

    $where = "";
    $data = [htmlspecialchars($_GET['id'] ?? "")];


    // if (isset($_GET['is_sold']) && $_GET['is_sold'] == "all") {
    //   $where .= "or sold_to  LIKE CONCAT( '%',?,'%')";
    //   $data[] = $s;
    // }

    if (isset($_GET['is_sold']) && $_GET['is_sold'] == "no") {
      // $s = htmlspecialchars($_GET['s']);
      $where .= "and sold_to = ?";
      $data[] = "";
    }
    if (isset($_GET['is_sold']) && ($_GET['is_sold'] == "yes" || $_GET['is_sold'] == "sold_report")) {
      // $s = htmlspecialchars($_GET['s']);
      $where .= "and sold_to != ?";
      $data[] = "";
    }


    if (isset($_GET['startDate']) && isset($_GET['endDate']) && $_GET['startDate'] != "") {
      $where .= "and date >= ? and date <= ?";
      $data[] = htmlspecialchars($this->formatDate($_GET['startDate']));
      $data[] = htmlspecialchars($this->formatDate($_GET['endDate'])) ?? date("Y-m-d H:i:s");
    }

    if (isset($_GET['s']) && $_GET['s'] != "") {
      $s = htmlspecialchars($_GET['s']);
      $where .= "and (login_details  LIKE CONCAT( '%',?,'%') or username  LIKE CONCAT( '%',?,'%')) ";
      $data[] = $s;
      $data[] = $s;
    }
    // if(isset())
    if (isset($_GET['is_sold']) && $_GET['is_sold'] == "sold_report") {
      if ($start > 0) return;
      $account = $this->getall("account", "ID = ?", [htmlspecialchars($_GET['id'] ?? "")]);
      if (!is_array($account)) return "";
      $amount = (int)$account['amount'];
      if (isset($amount['real_amount']) && ($amount['real_amount'] != "" || $amount['real_amount'] != null)) {
        $amount = (int)$amount['real_amount'];
      }
      $number_sold = $this->getall("logininfo", "accountID = ? $where order by date DESC", $data, fetch: "");
      return ["report" => "<div class='card card-body fs-4 p-3'><p>Total Amount Sold: " . ($this->money_format($amount * $number_sold)) . "</p><p>No Sold: " . number_format($number_sold) . "</p></div>"];
    }
    return $this->getall("logininfo", "accountID = ? $where order by date DESC LIMIT $start, $limit", $data, fetch: "moredetails");
  }
  function fetch_account($start = 0, $platform = "", $limit = 10, $status = 1, $category = "all", $select = "*")
  {

    $where = "";
    $data = [""];
    if (isset($_GET['s']) && $_GET['s'] != "") {
      $s = htmlspecialchars($_GET['s']);
      $where .= "and ID  LIKE CONCAT( '%',?,'%') or title  LIKE CONCAT( '%',?,'%') or description  LIKE CONCAT( '%',?,'%')";
      $data[] = $s;
      $data[] = $s;
      $data[] = $s;
    }
    if ($platform == "" && isset($_GET['platform']) && $_GET['platform'] != "") {
      $platform = htmlspecialchars($_GET['platform']);
    }
    if (isset($_GET['category']) && $_GET['category'] != "all") {
      $category = htmlspecialchars($_GET['category']);
    }

    if ($platform != "") {
      $where .= " and platformID = ?";
      $data[] = $platform;
    }
    if ($category != "all") {
      $where .= " and categoryID = ?";
      $data[] = $category;
    }


    if (isset($_GET['userID']) && $_GET['userID'] != "") {
      $where .= " and sold_to = ?";
      $data[] = htmlspecialchars($_GET['userID']);
    }
    if ($status != "") {
      $where .= " and status = ?";
      $data[] = $status;
    }
    return $this->getall("account", "id != ? $where order by date DESC LIMIT $start, $limit", $data, select: $select, fetch: "moredetails");
  }

  function get_account($id, $status = "1")
  {
    $where = "id = ?";
    $data = [$id];
    if ($status != "") {
      $where .= "and status = ?";
      $data[] = $status;
    }
    return $this->getall("account", $where, $data);
  }

  function get_platfrom_img_url($pid)
  {
    $platfrom = $this->getall("platform", "ID = ?", [$pid]);
    return PATH . "assets/images/icons/" . $platfrom['icon'];
  }

  function get_platform($id)
  {
    return $this->getall("platform", "ID = ?", [$id]);
  }

  function get_num_of_login($accountID)
  {
    return $this->getall("logininfo", "accountID = ? and sold_to = ?", [$accountID, ""], fetch: "");
  }

  public function fetchLoginsByAccountID($accountID, $limit = 20, $offset = 0, $excludeIDs = [])
  {
    // Create the WHERE clause for excluding IDs
    $excludeIDs = array_map('intval', $excludeIDs); // Sanitize exclude IDs
    $excludeClause = !empty($excludeIDs) ? "AND ID NOT IN (" . implode(',', $excludeIDs) . ")" : '';

    // Fetch logins from database
    $whereClause = "accountID = ? and username != ? and preview_link != ? and sold_to = ? $excludeClause ORDER BY date ASC LIMIT $limit OFFSET $offset";
    $data = [$accountID, "", "", ""];
    return $this->getall('logininfo', $whereClause, $data, 'ID, accountID, username, preview_link', 'moredetails');
  }

  function buy_account_choice($userID, $accountID, array $choices)
  {
    $qty = count($choices);
    $account = $this->getall("account", "ID =?", [$accountID]);
    $amount = (float)$account['amount'] * (int)$qty;
    $orderID = uniqid("order-");
  }

  // Function to calculate the discount based on amount and quantity
  function calculateDiscount($amount, $quantity, $discountData)
  {
    // var_dump($discountData);
    if ($quantity >= $discountData['no_order'] && $discountData['totalCredit'] > 0) {
      if ($discountData['discount_type'] === "percentage") {
        $discountAmount = $amount * ($discountData['discount'] / 100);
      } else {
        $discountAmount = $discountData['discount']; // Flat discount
      }
      return $amount - $discountAmount;
    }
    return $amount; // No discount applied
  }


  function can_buy($userID, $catID)
  {
    $category = $this->getall("category", "ID = ?", [$catID]);
    if (!is_array($category)) return false;
    if ($category['cat_type'] == 0 && !$this->is_ofline_buyer($userID)) {
      return false;
    }
    return true;
  }

  function buy_account($userID, $accountID, $qty, array $logins = [], $isApi = false)
  {
    $account = $this->getall("account", "ID = ?", [$accountID]);
    if (!is_array($account)) {
      return $this->message("Account Not found.", "error", "json");
    }
    $useCart = false;
    $qty = (int)$qty;
    if (isset($account['categoryID']) && !$this->can_buy($userID, $account['categoryID'])) {
      return $this->message("Error.", "error", "json");
    }
    if (count($logins) > 0) {
      $useCart = true;
      $qty = count($logins);
      if ($qty <= 0) return $this->message("No account selected", "error", "json");
    }

    if (count($logins) == 0) {
      $accountLeft = $this->get_num_of_login($accountID);
      if ($accountLeft < $qty) {
        return $this->message("Account(s) not avilable or sold out. Have just $accountLeft Left.", "error", "json");
      }
      // get all logins with accoutID based on qty
      $logins = $this->getall("logininfo", "accountID = ? and sold_to = ? LIMIT $qty", [$accountID, ""], fetch: "all");
      if ($logins->rowCount() < $qty) {
        return $this->message("only " . $logins->rowCount() . " available left", "error", "json");
      }
    }

    if ($qty == 0) {
      return $this->message("You selected zero(0) account.", "error", "json");
    }
    // die(var_dump($account));
    $costAmount = (float)$account['amount'] * (int)$qty;
    $discountData = $this->getUserStage($userID);
    $amount = ($this->is_ofline_buyer($userID)) ? $costAmount : $this->calculateDiscount($costAmount, $qty, $discountData['stage']);
    $orderID = uniqid("order-");
    // debit user account
    $user = $this->getall("users", "ID =?", [$userID]);
    if (!is_array($user)) return;
    if ((int)$user['balance'] < $amount) return $this->message("Insufficient balance", "error", "json");
    $debit = $this->credit_debit($userID, $amount, "balance", "debit", "orders", $orderID);
    if (!$debit) {
      return "";
    }
    // loop throught logins and and upate sold_out with userID, 
    // update account
    $bought_logins = [];
    $faild = 0;
    foreach ($logins as $login) {
      $check = $this->getall("logininfo", "ID = ? and sold_to != ?", [$login['ID'], ""], fetch: "");
      if ($check > 0) {
        $faild++;
      } else {
        $update = $this->update("logininfo", ["sold_to" => $userID, "sold_at" => date("Y-m-d H:i:s")], "ID = '$login[ID]' AND (sold_to IS NULL OR sold_to = '')");
        $check = $this->getall("logininfo", "ID = ? and sold_to = ?", [$login['ID'], $userID], fetch: "");
        if (!$update || $check == 0) {
          $faild++;
        } else {
          // pass all login ID to bought_logins
          $bought_logins[] = $login['ID'];
        }
      }
    }
    // echo "GOT HERE";
    if ($faild > 0) {
      $refundAmount = ($amount / $qty) * (int)$faild;
      $this->credit_debit($userID, $refundAmount, "balance", "credit", "order-refund", $orderID);
    }
    // create an order for the account
    $order = [
      "ID" => $orderID,
      "userID" => $userID,
      "accountID" => $accountID,
      "loginIDs" => implode(',', $bought_logins),
      "amount" => $amount,
      "cost_amount" => $costAmount,
      "no_of_orders" => ($qty - $faild),
      "order_where" => ($isApi) ? "api" : "live"
    ];
    $this->quick_insert("orders", $order);
    $message =  ($qty - $faild) . " Account Purchased.";
    if ($faild > 0) $message .= "<br><b>You were not fast enough, $faild of the account(s) is bought by someone else before you buy.</b>";
    if (($qty - $faild) > 0) $message .= "<br><b class='text-danger'><small>Redirecting in 9secs...<small></b> <br> <small> If not redirected <a href='index?p=orders&action=view&id=$orderID'>Click here</a></small>";
    // redirect to account info page
    $return = [
      "message" => ["Success", $message, "success"]
    ];
    $urlLink = null;
    $time = 9000;
    if (($qty - $faild) > 0) $urlLink = "index?p=orders&action=view&id=$orderID";
    if ($useCart) $return['function'] = ["emptyCartAndRedirect", "data" => [$urlLink, $time]];
    if (!$useCart) $return['function'] = ["loadpage", "data" => [$urlLink, 5000]];
    // if(($qty - $faild) > 0)  $return['function']['data'] = ["index?p=orders&action=view&id=$orderID", 9000];
    if ($isApi) {
      return $order;
    }
    return json_encode($return);
  }

  function sell_account($userID, $accountID, array $choices) {}


  function display_account_name($account)
  {
    $string_short_length = 30;

    if (!is_array($account)) {
      $account = $this->getall("account", "ID = ?", [$account]);
    }
    $platform = $this->get_platform($account['platformID']);
    return "
      <div class='d-flex m-0 justify-content-between'>
                    <div class='d-flex m-0'>
                    <div>
                    <img src='" . PATH . "assets/images/icons/" . $platform['icon'] . "' class='img-fluid rounded-circle' width='20' />
                </div>
                <div class='ms-2'>
                <h6 class='note-title w-100 mb-0'> " . $this->short_text($account['title'], 38) . " </h6>
                <p class='note-date fs-2 m-0'>" . $platform['name'] . "</p>
                </div>
                    </div>

                    <div class=''>
                  <p class='h6 p-0'><b>" . $this->money_format($account['amount'], currency) . "</b></p>
                  </div>
                  </div>
      ";
  }
  function display_account($account, $userID = null)
  {
    $platform = $this->get_platform($account['platformID']);
    return "<div class='col single-note-item all-category p-0 m-0' id='displayaccount-" . $account['ID'] . "'>
                <div class='card noanimation card-body bg-light p-0 p-2 border-1 mb-1'>
                  " . $this->display_account_name($account) . "
                  <div class='d-flex align-items-center justify-content-between p-0 m-0'>
                  <div class='w-80 text-end'><small><b>" . $this->get_num_of_login($account['ID']) . " pcs available</b></small></div>
                  <div>   
                  " . $this->account_btn($account, $userID) . "
                  </div>
                  </div>
                  
            </div>
          </div>
            ";
  }

  function account_btn($account, $userID = null)
  {
    $accountID = $account['ID'];
    if ($this->validate_admin() && side == 'admin') {
      $btn =
        " <a 
        href='index?p=account&action=edit&id=$accountID' 
        class='link me-1 btn btn-sm btn-outline-primary'>
          <i class='ti ti-edit fs-4 favourite-note'></i> Edit
        </a>
        ";

      if ($account['status'] != 3) {
        $btn .= "<form action='' id='foo'>
          <input type='hidden' name='ID' value='$accountID'>
          <input type='hidden' name='delete_account' value='approved'>
          <input type='hidden' name='page' value='account'>
          <input type='hidden' name='confirm' value='You are about to delete this Account.'>
          <div id='custommessage'></div>
          <button type='submit' class='ml-2 btn btn-sm btn-light-danger d-flex align-items-center gap-3 text-danger' href='#'><i class='fs-4 ti ti-trash'></i>Delete</button>
      </form>";
      } else {
        $btn .= "<a href='index?p=users&action=view&id=" . $account['sold_to'] . "' class='btn btn-sm btn-outline-success'>View Buyer</a>";
      }
      return $btn;
    }
    if ($this->get_num_of_login($account['ID']) > 0) {
      return "
        <a 
                    href='index?p=account&action=details&id=" . $account['ID'] . "' 
                    
                    class='link me-1 btn btn-sm bg-blue'>
                      <i class='ti ti-plus fs-4 favourite-note'></i> buy
                    </a>

        ";
    } else {
      return "
        <a 
                    href='#' 
                    
                    class='link me-1 btn btn-sm btn-dart'>
                      Sold Out.
                    </a>

        ";
    }

    return "<a  href='" . PATH . "index?p=account&action=view&id=$accountID' class='link btn btn-sm btn-primary  ms-2'>
      View Account
    </a>";
  }

  // login details 
  function display_login_details($login, $index = 1, $showAccount = 'd-none')
  {
    $stick = "success";
    $status = "Active";
    $soldAt = "";
    if ($login['sold_to'] != '') {
      $stick = "danger";
      $soldAt = " <small><b>Sold At: </b>" . $login['sold_at'] . "</small>";
      $status = "Sold to <a href='index?p=users&action=view&id=" . $login['sold_to'] . "'>" . $this->get_name($login['sold_to']) . "</a>";
    }
    return "
        <div class='col-md-4 single-note-item all-category' id='displaylogin-" . $login['ID'] . "'>
                        <div class='shadow-sm card-body p-4'>
                            <span class='side-stick bg-$stick'></span>
                            <h6 class='note-title text-truncate w-75 mb-0 fs-2'>ID " . $login['ID'] . "</h6>
                            <h6 class='note-title text-truncate w-75 mb-0'></h6>
                            <p class='note-date fs-2 text-$stick'>$status</p>
                            
                            <div class='note-content'>
                                <p class='note-inner-content'> 
                                " . $login['login_details'] . "
                                </p>
                            </div>
                            <div class='d-flex gap-1'>
                            " . $this->get_login_btns($login, $showAccount) . "
                          </div>
                          <hr>
                          <small><b>Added on: </b>" . $login['date'] . "</small>
                          $soldAt
                        </div>
                    </div>
        ";
  }

  function get_login_btns($login, $showAccount = 'd-none')
  {
    $btn = $this->copy_text($login['login_details']);
    $id = $login['ID'];
    if ($this->validate_admin()) {
      $modal_attributes = $this->modal_attributes("modal?p=account&action=edit_login&loginID=$id", "Edit Login Details");
      $id = $login['ID'];
      $btn .=
        "<a 
        target='_blank'
        href='index?p=account&action=edit&id=" . $login['accountID'] . "' 
        class='link me-1 btn btn-sm btn-outline-dark $showAccount'>
           Open Account
        </a>
        "
        . "<a 
        $modal_attributes
        href='index?p=account&action=edit&id=$id' 
        class='link me-1 btn btn-sm btn-outline-primary'>
          <i class='ti ti-edit fs-4'></i> Edit
        </a>
        ";
      if ($login['sold_to'] == "") {
        $btn .= "<form action='' id='foo'>
          <input type='hidden' name='ID' value='$id'>
          <input type='hidden' name='delete_login' value='approved'>
          <input type='hidden' name='page' value='account'>
          <input type='hidden' name='confirm' value='You are about to delete this Login.'>
          <div id='custommessage'></div>
          <button type='submit' class='ml-2 btn btn-sm btn-light-danger d-flex align-items-center gap-3 text-danger' href='#'><i class='fs-4 ti ti-trash'></i>Delete</button>";
      }
    }

    return $btn;
  }
}

// <form action='' id='foo'>
//             <input type='hidden' name='ID' value='$accountID'>
//             <input type='hidden' name='buy' value='yes'>
//             <input type='hidden' name='page' value='account'>
//             <input type='hidden' name='confirm' value='".$this->money_format($account['amount'])." will be decducted from your balance for ". $account['title'] ."'>
//             <div id='custommessage'></div>
//             <button type='submit' class=' d-flex align-items-center gap-3 btn btn-sm btn-success' href='#'><i class='fs-4 ti ti-check'></i>Buy</button>
// </form>