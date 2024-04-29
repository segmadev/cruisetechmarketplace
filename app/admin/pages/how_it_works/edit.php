<div class="card">
    <div class="card-header">
        <h5>Edit how it works</h5>
    </div>
    <div class="card-body">
        <form action="" id="foo">
            <?php 
            echo $c->create_form($how_it_works)?>
            <input type="hidden" name="page" value="how_it_works">
            <input type="hidden" name="updatedetails" value="how_it_works">
            <div id="custommessage"></div>
            <input type="submit" value="Update" class="btn btn-primary">
        </form>
    </div>
</div>