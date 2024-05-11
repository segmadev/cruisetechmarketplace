<hr>
<div class="row">
    <h3 class="h5">Add Account Logins</h3>
    <div id="loginInfos" class="row">
        <?= $c->create_form($loginInfo); ?>
        <?= $c->create_form($loginInfo); ?>
    </div>
</div>
<button type="button" onclick="add_login()" class="btn text-primary"><b><i class="ti ti-plus"></i> Add more Login</b></button>
<hr>
<script>
    function add_login() {
        var template = `<?= $c->create_form($loginInfo);  ?> <?= $c->create_form($loginInfo);  ?>`;
        var new_row = document.createElement('div');
        new_row.className = "add-new w-100 row m-0 p-0";
        new_row.innerHTML = template;
        document.getElementById("loginInfos").appendChild(new_row);

    }
</script>



