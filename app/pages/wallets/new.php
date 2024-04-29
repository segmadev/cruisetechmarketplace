<div class="col-12 col-md-12">
    <div class="card p-4">
        <h4>Add new wallet</h4/>
        <p></p>
        <form action="" id="foo" class="row">
            <?= $c->create_form($wallet_from); ?>
            <input type="hidden" name="new_wallet" value="">
            <input type="hidden" name="page" value="wallets">
           <div id="custommessage"></div>           
           <input type="submit" value="Add Wallet" class="btn btn-primary col-12 col-md-4">
        </form>
    </div>
</div>