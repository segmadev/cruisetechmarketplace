<?php
    if(!isset($_GET['loginID']) || $_GET['loginID'] == "") {
        $d->message("No ID passed", "error");
        exit();
    }

    $loginID = htmlspecialchars($_GET['loginID']);
    $login_details = $d->getall("logininfo", "ID = ?", [$loginID]);
    if(!is_array($login_details)) {
        $d->message("No Record found", "error");
        exit();
    }
    $loginInfo["login_details"] = $loginInfo["login_details[]"];
    $loginInfo['ID']['type'] = "input"; 
    $loginInfo['ID']['input_type'] = "hidden"; 
    $loginInfo['accountID']['type'] = "input"; 
    $loginInfo['accountID']['input_type'] = "hidden"; 
    unset($loginInfo["login_details[]"]);
    $loginInfo["input_data"] = $login_details;
?>
<form action="" id="foo" method="post">
    <?= $c->create_form($loginInfo) ?>
    <input type="hidden" name="page" value="account">
    <input type="hidden" name="edit_login_details" value="account">
    <div id="custommessage"></div>
    <input type="submit" value="Update LoginInfo" class="btn btn-primary">
</form>