<div class="card">
    <div class="card-header">
        <h5>Add new key feature</h5>
    </div>
    <div class="card-body">
        <form action="" id="foo">
            <?php echo $c->create_form($key_features)?>
            <input type="hidden" name="page" value="features">
            <input type="hidden" name="newdetails" value="key_features">
            <div id="custommessage"></div>
            <input type="submit" value="Add" class="btn btn-primary">
        </form>
    </div>
</div>