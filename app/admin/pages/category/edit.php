<div class="card">
    <div class="card-header">
        <h3>Edit category</h3> | <a href="index?p=category" target="_blank" >All categories</a>
    </div>
    <div class="card-body">
        <form action="" method="post" id="foo">
            <?php             
            echo $c->create_form($category_form); ?>
            <input type="hidden" name="page" value="category">
            <input type="hidden" name="edit_category" value="j">
            <div id="custommessage"></div>
            <input type="submit" value="Edit" class="btn btn-primary">
        </form>
    </div>
</div>