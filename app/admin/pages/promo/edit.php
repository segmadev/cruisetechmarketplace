<?php 
$promo_form['assigned_users']['options'] = $c->get_users_option_data();
$promo_form["input_data"] = $promo;
$promo_form["input_data"]['assigned_users'] = json_decode($promo['assigned_users']);
$script[] = "select2";
?>
<div class="card p-5 col-12 col-md-8">
    <h5>Edit Promo</h5>
    <hr>
    <form action="" id="foo">
        <?php echo $c->create_form($promo_form); ?>
        <input type="hidden" name="ID" value='<?= $id ?>'>
        <input type="hidden" name="page" value="promo">
        <input type="hidden" name="edit_promo" value="">
        <div id="custommessage"></div>
        <input type="submit"  class="btn btn-primary" value="Update Promo">
    </form>
</div>