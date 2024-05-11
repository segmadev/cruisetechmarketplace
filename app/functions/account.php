<?php
class Account extends user
{
    function delete_account($id) {
      if(!$this->validate_admin()) {
        return false;
      }

      $account = $this->getall("account", "ID = ?", [$id]);
      if(!is_array($account)) {
        return false;
      }
      if(!$this->delete("account", "ID = ?", [$id])) {
        $this->message("Unable to delete account, Reload page and try again", "error", "json");
      }

      $return = [
        "message"=> ["Success", "Account Deleted", "success"],
        "function"=>["removediv", "data"=>["#displayaccount-$id", "null"]]
    ];
    return json_encode($return);
      
    }
    function fetch_account($start = 0, $platform = "", $limit = 10, $status = 1)
    {
     
        $where = "";
        $data = [""];
        if(isset($_GET['s']) && $_GET['s'] != "") {
          $s = htmlspecialchars($_GET['s']);
          $where .= "and title  LIKE CONCAT( '%',?,'%') or description  LIKE CONCAT( '%',?,'%')";
          $data[] = $s;
          $data[] = $s;
        }
        if($platform == "" && isset($_GET['platform']) && $_GET['platform'] != "") {
           $platform = htmlspecialchars($_GET['platform']);
        }

        if ($platform != "") {
          $where .= " and platformID = ?";
          $data[] = $platform;   
        }
        if (isset($_GET['userID']) && $_GET['userID'] != "") {
          $where .= " and sold_to = ?";
          $data[] = htmlspecialchars($_GET['userID']);   
        }
        if($status != ""){
          $where .= " and status = ?";
          $data[] = $status;
        }
        return $this->getall("account", "id != ? $where LIMIT $start, $limit", $data, fetch: "moredetails");
    }

    function get_account($id, $status = "1") {
        $where = "id = ?";
        $data = [$id];
        if($status != "") {
            $where .= "and status = ?";
            $data[] = $status;
        }
        return $this->getall("account", $where, $data);
    }

    function get_platfrom_img_url($pid) {
        $platfrom = $this->getall("platform", "ID = ?", [$pid]);
        return PATH."assets/images/icons/".$platfrom['icon'];
    }

    function get_platform($id) {
        return $this->getall("platform", "ID = ?", [$id]);
    }

    function get_num_of_login($accountID) {
      return $this->getall("logininfo", "accountID = ? and sold_to = ?", [$accountID, ""], fetch: "");
    }

    function buy_account($userID, $accountID) {
        $account = $this->getall("account", "ID = ?", [$accountID]);
        if ($account['status'] != 1 || $account['sold_to'] != "") {
            return $this->message("Account not avilable or sold out.", "error");
        }
        // debit user account
        $debit = $this->credit_debit($userID, $account['amount'], "balance", "debit", "account", $account['ID']);
        if (!$debit) {
            return "";
        }

        // update account
        $update = $this->update("account", ["status" => 3, "sold_to" => $userID], "ID = '$accountID'");
        if (!$update) {
            $this->message("An error occurred while purchasing your account please contact " + $this->get_settings("support_email") + " for help.", "error");
        } 
        // redirect to account info page
        $return = [
            "message"=> ["Success", "Account Purchased", "success"],
            "function"=>["loadpage", "data"=>["index?p=account&action=view&id=$accountID", "null"]]
        ];
        return json_encode($return);
    }
    function display_account($account, $userID = null){
        $platform = $this->get_platform($account['platformID']);
        return "<div class='col single-note-item all-category note-favourite' id='displayaccount-".$account['ID']."'>
                <div class='card card-body bg-light p-0 p-2 border-1'>
                  
                  <div class='d-flex'>
                    <div>
                        <img src='".PATH."assets/images/icons/".$platform['icon']."' class='img-fluid rounded-circle' width='20' />
                    </div>
                    <div class='ms-2'>
                    <h6 class='note-title w-100 mb-0'> ".$account['title']." </h6>
                    <p class='note-date fs-2'>".$platform['name']. "</p>
                    </div>
                  </div>
                  
                  <div class='d-flex align-items-center justify-content-between'>
                <div class=''>
                  <p class='h6'><b>" . $this->money_format($account['amount'], currency) . "</b></p>
                  </div>
                  <div>   
                  " . $this->account_btn($account, $userID) . "
                  </div>
                  </div>
                  <div class='w-100 text-end'><small><b>".$this->get_num_of_login($account['ID'])." pcs available</b></small></div>
            </div>
          </div>
            ";
    }

    function account_btn($account, $userID = null) {
      $accountID = $account['ID'];
      if($this->validate_admin() && side == 'admin') {
        $btn =
        " <a 
        href='index?p=account&action=edit&id=$accountID' 
        class='link me-1 btn btn-sm btn-outline-primary'>
          <i class='ti ti-edit fs-4 favourite-note'></i> Edit
        </a>
        ";

        if($account['status'] != 3) {
        $btn .= "<form action='' id='foo'>
          <input type='hidden' name='ID' value='$accountID'>
          <input type='hidden' name='delete_account' value='approved'>
          <input type='hidden' name='page' value='account'>
          <input type='hidden' name='confirm' value='You are about to delete this Account.'>
          <div id='custommessage'></div>
          <button type='submit' class='ml-2 btn btn-sm btn-light-danger d-flex align-items-center gap-3 text-danger' href='#'><i class='fs-4 ti ti-trash'></i>Delete</button>
      </form>";
    }else{
      $btn .= "<a href='index?p=users&action=view&id=".$account['sold_to']."' class='btn btn-sm btn-outline-success'>View Buyer</a>";
    }
    return $btn;
      }
      if($userID == null || $account['sold_to'] == "" || $account['sold_to'] != $userID) {
        return "
        <a 
                    href='#' 
                    data-bs-toggle='modal' 
                    data-bs-target='#bs-example-modal-md' 
                    id='modal-".$account['ID']."' 
                    data-url='".PATH."modal?p=account&action=details&id=".$account['ID']."' 
                    data-title='Account Details' 
                    onclick='modalcontent(this.id)'
                    class='link me-1 btn btn-sm btn-outline-success'>
                      <i class='ti ti-plus fs-4 favourite-note'></i> buy
                    </a>

        ";
      }

      return "<a  href='".PATH."index?p=account&action=view&id=$accountID' class='link btn btn-sm btn-primary  ms-2'>
      View Account
    </a>";

    }

    // login details 
    function display_login_details($login, $index = 1) {
    $stick = "success";
    $status = "Active";
    if ($login['sold_to'] != '') {
      $stick = "danger";
      $status = "Sold to <a href='index?p=users&action=view&id=".$login['sold_to']."'>".$this->get_name($login['sold_to'])."</a>";
    }
      return "
        <div class='col-md-4 single-note-item all-category' id='displaylogin-".$login['ID']."'>
                        <div class='card card-body p-4'>
                            <span class='side-stick bg-$stick'></span>
                            <h6 class='note-title text-truncate w-75 mb-0'>Login $index</h6>
                            <p class='note-date fs-2 text-$stick'>$status</p>
                            
                            <div class='note-content'>
                                <p class='note-inner-content'> 
                                ".$login['login_details']."
                                </p>
                            </div>
                            <div class='d-flex gap-1'>
                            ".$this->get_login_btns($login)."
                          </div>
                        </div>
                    </div>
        ";
    }

    function get_login_btns($login) {
    $btn = $this->copy_text($login['login_details']);
    $id = $login['ID'];
      if($this->validate_admin()) {
        $modal_attributes = $this->modal_attributes("modal?p=account&action=edit_login&loginID=$id", "Edit Login Details");
        $id = $login['ID'];
        $btn .=
        "<a 
        $modal_attributes
        href='index?p=account&action=edit&id=$id' 
        class='link me-1 btn btn-sm btn-outline-primary'>
          <i class='ti ti-edit fs-4'></i> Edit
        </a>
        ";
        if($login['sold_to'] == "") {
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
?>


