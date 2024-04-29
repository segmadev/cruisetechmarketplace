<div class="card">
    <div class="card-header">
        <h5 class="title">Create Referral Program</h5>
    </div>
    <div class="card-body">
        <form action="" id="foo">
            <?= $c->create_form($referral_from); ?>
            <input type="hidden" name="page" value="referral">
            <input type="hidden" name="new_referral" value="">
            <div id="custommessage"></div>
            <input type="submit" value="Create" class="btn btn-success">
        </form>
    </div>
</div>