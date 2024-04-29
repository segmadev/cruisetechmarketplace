<div class="card">
    <div class="card-header">
        <h3>Create Account</h3>
    </div>
    <div class="card-body">
        <form action="" id="foo">
            <div class="row">
                <?= $c->create_form($account_from); ?>
            </div>
            <input type="hidden" name="page" value="account">
            <input type="hidden" name="new_account" value="account">
            <div id="custommessage"></div><br>
            <button type="submit" class="btn btn-primary">
                Save Account
            </button>
        </form>
    </div>
</div>