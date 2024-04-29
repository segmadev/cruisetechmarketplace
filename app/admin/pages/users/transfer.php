<style>
    li {
        list-style: none;
    }

</style>
<?php 
    if(!isset($_GET['userID'])) {
        $c->message("No user passed reload page and try again.", "error");
        exit();
    }
    $DuserID = htmlspecialchars($_GET['userID']);
    $transfer_from['input_data']["userID"] = $DuserID;
   
?>

<div class="row flex">
   <div class="col-12 col-md-6">
   <form id="foo" class="card p-3">
    <h4>Transfer</h4>
        <?=  $c->create_form($transfer_from); ?>
        <input type="hidden" name="page" value="users">
        <input type="hidden" name="transfer" value="">
        <div id="custommessage"></div>
        <input type="submit" value="Transfer" class="btn btn-primary">
    </form>
   </div>
    <div class="col-12 col-md-6">
        <?=  $u->short_user_table($DuserID); ?><?= $u->show_balance($DuserID) ?>
    </div>
</div>