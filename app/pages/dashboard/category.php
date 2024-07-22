<?php 
    $accounts = $d->getall("account", "categoryID = ? order by date DESC LIMIT 10", [$category['ID']], fetch: "all");
?>
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <div class="title bg-primary p-2 fs-3"><b><?= $category['name'] ?></b></div>
        <div class="buttons"><a href="index?action=view&category=<?= $category['ID'] ?>" class="btn btn-sm btn-primary">View All</a></div>
    </div>
    <div class="card-body w-100 row row-cols-1 row-cols-lg-3 row-cols-md-2 g-1 g-lg-3 m-0 p-0">
        <?php 
            if($accounts->rowCount() > 0) {
                require_once "functions/account.php";
                $a = new account;
                foreach($accounts as $account) {
                    echo $a->display_account($account);
                }
            }else{
                echo $c->empty_page("No account found for this category");
            }
        ?>
    </div>
</div>