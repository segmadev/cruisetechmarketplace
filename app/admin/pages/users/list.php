<?php 
    if($users->rowCount() < 1) {
        $c->empty_page("No user found.");
    }else{
        // require_once "pages/users/table.php";
        ?>
        <div class="card">
            <div class="card-header">
                <h3 class="title">Search User</h3>
                <p>Search User by name, Email, phone number, or userID</p>
            </div>
            <div class="card-body">
            <form action="" id="foo">
            <input type="search" placeholder="Search User" name="s" class="form-control w-100" minlength="4" required>
            <input type="hidden" name="search_user" value="">
            <input type="hidden" name="page" value="users">
            <input type="submit" value="Search" class="btn btn-primary mt-3">
            <hr>
            <div id="custommessage"></div>
        </form>
            </div>
        </div>
 <?php   }
?>