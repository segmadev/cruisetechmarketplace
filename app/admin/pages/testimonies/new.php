<div class="card">
    <div class="card-header">
        <h5>Add new Testimony</h5>
    </div>
    <div class="card-body">
        <form action="" id="foo">
            <?php echo $c->create_form($testimonies)?>
            <input type="hidden" name="page" value="testimonies">
            <input type="hidden" name="newdetails" value="testimonies">
            <div id="custommessage"></div>
            <input type="submit" value="Add" class="btn btn-primary">
        </form>
    </div>
</div>