<?php 
    require_once "content/header.php"; 
    $register_form = [
        "ID"=>["input_type"=>"hidden", "is_required"=>false],
        "email"=>["is_required"=>false],
        "phone_number"=>["input_type"=>"number"],
        "username"=>[],
        "password"=>[],

    ];
    $d->create_table("allusers", $register_form);
?>
<div class="row">
    <form action="">
    <?php echo  $c->create_form($register_form); ?>
    <input type="submit" value="Submit" class="btn btn-primary">
    </form>
</div>
<?php require_once "content/footer.php"; ?>