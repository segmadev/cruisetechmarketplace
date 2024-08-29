<?php 
    if(isset($_POST['username'])) {
        var_dump($_POST['username']);
    }
?>
<form method="POST" action="">
    <input type="text" name="username[]" id="">
    <input type="text" name="username[]" id="">
    <input type="text" name="username[]" id="">
    <input type="submit" value="Submit">
</form>