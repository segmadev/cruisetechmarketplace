<div class="card">
    <div class="card-header">
        <h5>Edit testimony</h5>
    </div>
    <div class="card-body">
        <form action="" id="foo">
            <?php echo $c->create_form($testimonies)?>
            <input type="hidden" name="page" value="testimonies">
            <input type="hidden" name="updatedetails" value="testimonies">
            <div id="custommessage"></div>
            <input type="submit" value="Update" class="btn btn-primary">
        </form>
    </div>
</div>