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
    $logininfo["login_details"] = $logininfo["login_details[]"];
    $logininfo['ID']['type'] = "input"; 
    $logininfo['ID']['input_type'] = "hidden"; 
    $logininfo['accountID']['type'] = "input"; 
    $logininfo['accountID']['input_type'] = "hidden"; 
    unset($logininfo["login_details[]"]);
    $logininfo["input_data"] = $login_details;
?>
<form action="" id="foo" method="post">
    <?= $c->create_form($logininfo) ?>
    <input type="hidden" name="page" value="account">
    <input type="hidden" name="edit_login_details" value="account">
    <div id="custommessage"></div>
    <input type="submit" value="Update LoginInfo" class="btn btn-primary">
</form>