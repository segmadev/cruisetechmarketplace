<?php 
$promo_form['assigned_users']['options'] = $c->get_users_option_data();
$script[] = "select2";
?>
<div class="card p-5 col-12 col-md-8">
    <h5>Create New Promo</h5>
    <hr>
    <form action="" id="foo">
        <?php echo $c->create_form($promo_form); ?>
        <input type="hidden" name="page" value="promo">
        <input type="hidden" name="new_promo" value="">
        <div id="custommessage"></div>
        <input type="submit"  class="btn btn-primary" value="Create Promo">
    </form>
</div>