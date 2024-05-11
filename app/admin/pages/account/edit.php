<?php
$script[] = "modal";
$script[] = "sweetalert";

?>
<div class="card">
    <div class="card-header">
        <h3>Edit Account</h3>
    </div>
    <div class="card-body">
        <form action="" id="foo">
            <div class="row">
                <?= $c->create_form($account_from); ?>
            </div>
            <input type="hidden" name="page" value="account">
            <input type="hidden" name="upadate_account" value="account">
            <?php require_once "pages/account/logins.php"; ?>
            <div id="custommessage"></div><br>
            <button type="submit" class="btn btn-primary">
                Update Account
            </button>
        </form>
        <hr>    
        <h3>Logins Added</h3>
        <div class="note-has-grid row">
            <?php if ($logins->rowCount() > 0) {
                $i = 1;
                foreach ($logins as $login) {
                    echo $a->display_login_details($login, $i);
                    $i++;
                }
            } ?>
        </div>
    </div>
</div>