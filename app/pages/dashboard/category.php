<?php 
    // echo "No access";
if(isset($category['cat_type']) && $category['cat_type'] == "0" && !$d->is_ofline_buyer($userID)) {
    $accounts = [];
}else{
    $accounts = $d->getall(
        "account", 
        "categoryID = ? 
         AND EXISTS (
            SELECT 1 FROM logininfo 
            WHERE logininfo.accountID = account.ID 
            AND (logininfo.sold_to IS NULL OR logininfo.sold_to = '')
         )
         ORDER BY date DESC 
         LIMIT 4", 
        [$category['ID']], 
        fetch: "all"
    );
    
}
?>


<div class="card">
    <div class="card-header d-flex justify-content-between">
        <div class="title btn btn-primary text-white p-2 fs-3"><b><?= $category['name'] ?></b></div>
        <div class="buttons"><a data-url="index?action=view&category=<?= $category['ID'] ?>"
                class="btn btn-sm btn-primary">View All</a></div>
    </div>
    <?php if($accounts->rowCount() > 0) {
        require_once "functions/account.php";
        $a = new account;
        ?>
        <div class="card-body w-100 row row-cols-1 row-cols-lg-3 row-cols-md-2 g-1 g-lg-3 m-0 p-0"
    id="accountList<?= $category['ID'] ?>" data-limit="4" data-start="4" data-load="account" data-displayId="accountList<?= $category['ID'] ?>" data-path="passer?p=account&category=<?= $category['ID'] ?>"
    >
<?php 
                foreach($accounts as $account) {
                    echo $a->display_account($account);
                }
?>
</div>
    <?php }else{
        echo $c->empty_page("No account found for this category");
    } ?>
</div>


<?php 
            // if($accounts->rowCount() > 0) {
            //     require_once "functions/account.php";
            //     $a = new account;
            //     foreach($accounts as $account) {
            //         echo $a->display_account($account);
            //     }
            // }else{
            //     echo $c->empty_page("No account found for this category");
            // }
        ?>