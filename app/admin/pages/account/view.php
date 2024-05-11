<form action="" method="post">
    <?php
        foreach($_POST['details'] as $key => $value) {
            echo $value;
            echo "<hr>";
        }
     ?>
    <textarea name="details[]" id="" cols="30" rows="10"></textarea>
    <textarea name="details[]" id="" cols="30" rows="10"></textarea>
    <textarea name="details[]" id="" cols="30" rows="10"></textarea>
    <textarea name="details[]" id="" cols="30" rows="10"></textarea>
    <input type="submit" class="btn btn-primary" value="Create Account">
</form>