
<?php
if(isset($_POST['update_account'])) {
    $accountID =  htmlspecialchars($_POST['accountID']);
    echo $a->add_login_info($accountID, "batch");
}
if (isset($_POST['new_account'])) echo $a->manage_account($account_from);
if (isset($_POST['upadate_account'])) echo $a->manage_account($account_from, "update");
if (isset($_POST['delete_account'])) echo $a->delete_account(htmlspecialchars($_POST['ID'] ?? ""), $r);
if (isset($_POST['edit_login_details']) && isset($_POST['ID'])) {
    $loginID = htmlspecialchars($_POST['ID']);
    $value = htmlspecialchars($_POST['login_details']);
    $accountID = htmlspecialchars($_POST['accountID']);
    $username = htmlspecialchars($_POST['username'] ?? "");
    $preview_link = htmlspecialchars($_POST['preview_link'] ?? '');
    echo $a->update_login_info($loginID, $value, $accountID, $username, $preview_link);
}
if (isset($_POST['delete_login'])) {
    $id = htmlspecialchars($_POST['ID'] ?? "");
    echo $a->delete_login_details($id);
}
if (isset($_GET['get']) && $_GET['get'] == "accounts") {
    $start = htmlspecialchars($_POST['start']);
    $limit = htmlspecialchars($_POST['limit']);
    try {
        
        $data = $a->fetch_account(start: $start, limit: $limit, status: "");
        $body = "";
        if ($data->rowCount() > 0) {
            foreach ($data as $value) {
                $body .= $a->display_account($value);
            }
            $return = ["status" => "ok", "data" => "$body"];
            echo json_encode($return);
            return;
            // echo $body;
        }
        $return = ["status" => "null", "data" => ""];
        echo json_encode($return);
        return;
    } catch (\Throwable $th) {
        $return = ["status" => "null", "data" => ""];
        echo json_encode($return);
    }
}

if (isset($_GET['get']) && $_GET['get'] == 'logins') {
    // var_dump($_GET['delete_logins']);
    try {
        if(isset($_GET['delete_logins']) && $_GET['delete_logins'] == "on" && $_GET['is_sold'] != "sold_report"){
            $return = ["status" => "null", "data" => $a->delete_logins_in_bulk()];
            echo json_encode($return);
            return;
        }
        $data = $a->fetch_login();
        $body = "";
        if (is_array($data) && isset($data['report'])) {
            $return = ["status" => "ok", "data" => $data['report']];
            echo json_encode($return);
            return;
        }
        if ($data != "" && $data->rowCount() > 0) {
            foreach ($data as $value) {
                $body .= $a->display_login_details($value);
            }
            $return = ["status" => "ok", "data" => "$body"];
            echo json_encode($return);
            return;
            // echo $body;
        }
        $return = ["status" => "null", "data" => ""];
        echo json_encode($return);
        return;
    } catch (\Throwable $th) {
        $return = ["status" => "null", "data" => ""];
        echo json_encode($return);
        // echo "An error occurred: ". $th->getMessage();
    }
}