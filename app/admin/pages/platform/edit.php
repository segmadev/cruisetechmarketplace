<div class="card">
    <div class="card-header">
        <h3>Edit Platform</h3> | <a href="index?p=platform" target="_blank" >All platforms</a>
    </div>
    <div class="card-body">
        <form action="" method="post" id="foo">
            <?php 
            $platform_form['icon']['is_required'] = false;
           
            
            echo $c->create_form($platform_form); ?>
            <input type="hidden" name="page" value="platform">
            <input type="hidden" name="edit_platform" value="j">
            <div id="custommessage"></div>
            <input type="submit" value="Edit" class="btn btn-primary">
        </form>
    </div>
</div>