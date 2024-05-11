<div class="card">
    <div class="card-header">
        <h3>Create New Category</h3>
    </div>
    <div class="card-body">
        <form action="" method="post" id="foo">
            <?= $c->create_form($category_form); ?>
            <input type="hidden" name="page" value="category">
            <input type="hidden" name="new_category" value="j">
            <div id="custommessage"></div>
            <input type="submit" value="Create" class="btn btn-primary">
        </form>
    </div>
</div>