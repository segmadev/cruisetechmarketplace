<div class="border-bottom title-part-padding">
        <h4 class="card-title mb-0">Edit Admin</h4>
    </div>
    <div class="card card-body">
        <form class="mt-4" action="" id="foo" novalidate="">
            <div class="row">
                <?php
                echo $c->create_form($admin_account); ?>
                <input type="hidden" name="updatesettings" value="" id="">
                <input type="hidden" name="page" value="settings" id="">
                <input type="hidden" name="settings" value="edit_admin">
            </div>
            <div id="custommessage"></div>
            <input type="submit" value="Update" class="btn btn-primary col-3">
        </form>
    </div>
</div>