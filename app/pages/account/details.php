<?php
if (!$account || $account == "") {
    echo $c->empty_page("Account not found or sold.", h1: "Not Found");
} else if(!$a->can_buy($userID, $account['categoryID'])){ 
 echo $c->empty_page("You can't access this page");
}else{
    $accountPreview = $d->get_settings("account_preview");
    $script[] = "sweetalert";
    if($accountPreview == 1)  $script[] = "cart";
    ?>
    <style>
         .accountcontent {
            overflow: hidden!important;
            height: 40px!important;
            transition: height 0.3s ease;
        }
        .accountcontent.expanded {
            height: auto!important;
        }
        .read-more {
            cursor: pointer;
            display: none;
        }

    </style>
    <div class="card p-3">
        <div class="flex d-flex">
            <div>
                <img  id="platfromImage" class="img-fluid rounded-circle" width="60"
                    src="<?= $a->get_platfrom_img_url($account['platformID']) ?>" alt="">
            </div>
            <div class="ms-3">
                <h2><?= $account['title'] ?></h2>
                <input type="hidden" id="amountvalue" value="<?= $account['amount'] ?>">
                <div class="d-flex h5">
                    <p>Amount: </p>
                    <p><?= $d->money_format($account['amount']) ?></p>
                </div>
            </div>
        </div>
        <?php 
        if($account['description'] != "") { ?>
            <div class='text-mute accountcontent' id="accountcontent"><?= $d->handleLinkInText(htmlspecialchars_decode($account['description']));?></div>
            <div class="read-more text-primary" onclick="toggleContent()">Read More</div>
        <?php } ?>
        <hr>
        <form action="" id="foo">
            <div class="input-group input-group-sm rounded" id="qtynumberDiv">
                <button class="btn min-width-40 py-0 border-end border-secondary fs-5 border-end-0 text-secondary"
                    type="button" id="minusAmount"><i class="ti ti-minus"></i></button>
                <input type="number" name="qty" id="qtynumber"
                    class="flex-grow-0 border border-secondary text-secondary fs-4 fw-semibold form-control text-center qty"
                    style="width:100px"
                    placeholder="" value="1">
                <button class="btn min-width-40 py-0 border border-secondary fs-5 border-start-0 text-secondary"
                    type="button" id="addAmount"><i class="ti ti-plus"></i></button>
            </div>
            <div class="mt-3 d-flex gap-2">
                <div>
                <span
                    class="badge text-bg-success fs-2 fw-semibold rounded-3"><?= $a->get_num_of_login($account['ID']) ?> pcs
                    available</span>
                </div>
                    <a  id="copybtn" style='display: none' class='badge btn btn-sm btn-dark'><i class="ti ti-copy"></i> Copy cart</a>
                    <a  id="clearbtn" style='display: none' class='badge btn btn-sm btn-danger'><i class="ti ti-trash"></i> Clear cart</a>
                </div>

                    <div class="p-2 mt-2">
                        <div id="cart-container" class="row gap-3" data-account-id="<?= $account['ID'] ?>"></div>
                    </div>
                    
    <hr>
    <textarea name="choices" style="display: none" id="cartDetails">[]</textarea>
    <input type="hidden" name="ID" value="<?= $account['ID'] ?>">
    <input type="hidden" name="buy" value="yes">
    <input type="hidden" name="page" value='account'>
    <input type="hidden" name="confirm" value="Your account will be charged for <b><?= $account['title'] ?></b>">
    <div id="custommessage"></div>
    <button type="submit" class=" d-flex align-items-center gap-3 btn btn-sm btn-success" href="#"><i
            class="fs-4 ti ti-check"></i>Buy Account</button>
            <p><small>Total Amount: <b id="DisplayAmount"><?= $d->money_format($account['amount']) ?></b></small></p>
    </form>
    <?php 
        if($accountPreview == 1) {
            echo '<!-- Search input -->
                        <b class="text-primary fs-2"><small>Search by username or paste preview links copied from the cart.</small></b>
                        <input type="text" id="search-input" placeholder="Search by username or preview link..." onkeyup="searchLogins()" class="form-control mb-1">
                        <!-- Notification Area -->
                        <div id="notification" style="display: none;"></div>
                        <div><button id="add-all-to-cart-btn" class="btn btn-sm btn-primary mb-1 mt-1" onclick="addAllToCart()" style="display: none;"><i class="ti ti-plus"></i>Add All results to Cart</button></div>
                        ';
            echo '<div id="logins-container" class="row gap-2"></div>';
        }
    ?>

    </div>
<?php } ?>

<script>
    add = document.getElementById("addAmount");
    minus = document.getElementById("minusAmount");
    var qtynumber = document.getElementById("qtynumber")
    amount = document.getElementById('amountvalue').value;


    add.addEventListener("click", function () {
        currentValue = parseInt(qtynumber.value) + 1;
        if (currentValue > parseInt(<?= $a->get_num_of_login($account['ID']) ?>)) return;
        qtynumber.value = currentValue;
        updateDisplayAmount(qtynumber.value, amount);

        // document.getElementById("DisplayAmount").innerHTML = "<?= htmlspecialchars(currency ?? "N") ?>" + (parseInt(amount) * currentValue).toLocaleString('en-US');
        // document.getElementById("DisplayAmount").innerHTML += "<br><b>Quantity: "+qtynumber.value+"</b>";
    });
    minus.addEventListener("click", function () {
        currentValue = parseInt(qtynumber.value) - 1;
        if (currentValue <= 0) return;
        qtynumber.value = currentValue;
        updateDisplayAmount(qtynumber.value, amount);
        // document.getElementById("DisplayAmount").innerHTML = "<?= htmlspecialchars(currency ?? "N") ?>" + (parseInt(amount) * currentValue).toLocaleString('en-US');
        // document.getElementById("DisplayAmount").innerHTML += "<br><b>Quantity: "+qtynumber.value+"</b>";
    });


    qtynumber.addEventListener("input", function() {
        updateDisplayAmount(qtynumber.value, amount);
        // document.getElementById("DisplayAmount").innerHTML = "<?= htmlspecialchars(currency?? "N")?>" + (parseInt(parseInt(amount) * parseInt(qtynumber.value)).toLocaleString('en-US') || 0);
        // document.getElementById("DisplayAmount").innerHTML += "<br><b>Quantity: "+qtynumber.value+"</b>";
    });

    document.addEventListener("DOMContentLoaded", function() {
        var content = document.getElementById("accountcontent");
        var readMore = document.querySelector(".read-more");

        // Check if content overflows
        if (content.scrollHeight > content.clientHeight) {
            readMore.style.display = "inline"; // Show the Read More button if there's overflow
        }
    });

    function toggleContent() {
        var content = document.getElementById("accountcontent");
        var readMore = document.querySelector(".read-more");
        if (content.classList.contains("expanded")) {
            content.classList.remove("expanded");
            readMore.textContent = "Read More";
        } else {
            content.classList.add("expanded");
            readMore.textContent = "Read Less";
        }
    }
</script>

</script>