<hr>
<div class="row">
    <?php 
        $loginClass = 'row border border-1 p-3';
    ?>
    <h3 class="h5">Add Account Logins</h3>
    <div id="loginInfos" class="row">
       <div class="<?= $loginClass ?>"> 
        <h6><b>Login 1</b></h6>
        <?= $c->create_form($logininfo); ?>
    </div>
    <div class="<?= $loginClass ?>"> 
           <h6><b>Login 2</b></h6>
        <?= $c->create_form($logininfo); ?>
    </div>
    </div>
</div>
<button type="button" onclick="add_login()" class="btn text-primary"><b><i class="ti ti-plus"></i> Add more Login</b></button>
<hr>
<script>
    let i = 3;
    function add_login() {
        var template = `<h6><b>Login ${i++}</b></h6> <?= $c->create_form($logininfo);  ?>`;
        var new_row = document.createElement('div');
        new_row.className = "add-new w-100 <?= $loginClass ?>";
        new_row.innerHTML = template;
        document.getElementById("loginInfos").appendChild(new_row);

    }
</script>



