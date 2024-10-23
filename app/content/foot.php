<script>
    function showall(id) {
        // Get the element by its ID
        var pElement = document.getElementById(id);
        // Replace the innerHTML with the data-fulltext attribute's value
        pElement.innerHTML = pElement.getAttribute('data-fulltext');
    }
</script>
<!--  Import Js Files -->
<script src="dist/libs/jquery/dist/jquery.min.js"></script>
<script src="dist/libs/simplebar/dist/simplebar.min.js"></script>
<script src="dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<!--  core files -->
<script src="dist/js/app.min.js"></script>
<script src="dist/js/app.init.js"></script>
<script src="dist/js/app-style-switcher.js"></script>
<script src="dist/js/sidebarmenu.js"></script>
<script src="dist/js/custom.js?n=2"></script>
<script src="dist/js/my.js?n=1184747"></script>
<script src="countrie/script.js?n=62524"></script>
<script src="dist/js/badge.js?=9497"></script>
<?php  require_once "include/ini-footer.php"; ?>