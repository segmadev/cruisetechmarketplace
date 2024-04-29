<?php
    require_once "content/header.php";
    $page = "wallets";
    require_once "pages/page-ini.php";
    require_once "content/footer.php";
    ?>
<script src="<?= ROOT ?>qrcodejs/qrcode.min.js"></script>
<script src="../dist/libs/select2/dist/js/select2.full.min.js"></script>
<script src="../dist/libs/select2/dist/js/select2.min.js"></script>
<script src="../dist/js/forms/select2.init.js"></script>
<script src="../assets/extra-libs/jqbootstrapvalidation/validation.js"></script>
<script>
    const qrcodelist = document.querySelectorAll('#genqr');
    
    qrcodelist.forEach(element => {
        
            console.log(element);
            data = element.getAttribute("data-info");
            showhere = element.getAttribute("data-id");
            // Get the container element
            var container = document.getElementById(showhere);
            // Text or data you want to encode in the QR code
            // QR code options (optional)
            var options = {
                // width: 200, // Width of the QR code (pixels)
                // height: 200, // Height of the QR code (pixels)
            };

            // Generate the QR code
            var qrcode = new QRCode(container, options);
            
            qrcode.makeCode(data);
       
    });

    !(function(window, document, $) {
        "use strict";
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    })(window, document, jQuery);
</script>