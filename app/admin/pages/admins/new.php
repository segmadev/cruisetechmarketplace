<div class="card">
    <div class="card-header">
        <h3>Admin</h3> | <a href="index?p=admins" target="_blank" >List of admins</a>
    </div>
    <div class="card-body">
        <form action="" method="post" id="foo">
            <?php             
            echo $c->create_form($admin_from); ?>
            <input type="hidden" name="page" value="admins">
            <input type="hidden" name="update_admin" value="j">
            <div id="custommessage"></div>
            <input type="submit" value="Update" class="btn btn-primary">
        </form>
    </div>
</div>