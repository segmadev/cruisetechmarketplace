<div class="card col-12">
    <div class="border-bottom title-part-padding">
        <h4 class="card-title mb-0">Create new Plan</h4>
    </div>
    <div class="card-body">
    <form class="mt-4" action="" id="foo"  novalidate="">
        <div class="row">
            <?php 
            echo $c->create_form($user_form); ?>
            <input type="hidden" name="newuser" value="" id="">
            <input type="hidden" name="page" value="users" id="">
        </div>
        <div id="custommessage"></div>
        <input type="submit" value="Create New User" class="btn btn-primary col-3">
    </form>
    </div>
</div>