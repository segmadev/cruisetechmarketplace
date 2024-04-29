<div class="card col-12">
    <div class="border-bottom title-part-padding">
        <h4 class="card-title mb-0">Edit Referral</h4>
        <p><?= $referral_from['input_data']['plan_amount'] ?></p>
    </div>
    <div class="card-body">
    <form class="mt-4" action="passer?id=<?= $referral_from['input_data']['ID']; ?>" id="foo"  novalidate="">
        <div class="row">
            <?php 
            echo $c->create_form($referral_from); ?>
            <input type="hidden" name="edit_referral" value="" id="">
            <input type="hidden" name="page" value="referral" id="">
        </div>
        <div id="custommessage"></div>
        <input type="submit" value="Save Changes" class="btn btn-primary col-3">
    </form>
    </div>
</div>