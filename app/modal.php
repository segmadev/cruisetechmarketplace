<?php 
if(isset($_GET['p']) && $_GET['p'] == "viwer") {
    require_once "pages/viewer/list.php";  
    // require_once "content/foot.php";
    return ;
}
    session_start();
    require_once "include/ini.php";
    require_once "pages/page-ini.php";
    require_once "content/foot.php";
?>
<script>
//     function submitform(id = "foo2", messageid = "custommessage") {
//     var myform = document.getElementById(id);
//     document.getElementById("Button").disabled = true;
//     document.getElementById(messageid).innerHTML = "Please wait...";
//     var fd = new FormData(myform);
//     $.ajax({
//         url: "passer",
//         data: fd,
//         cache: false,
//         processData: false,
//         contentType: false,
//         type: 'POST',
//         success: function (response) {
//             if(testJSON(response)){
//                 proceessjson(response);
//                 document.getElementById(messageid).innerHTML = "";
//             }else{
//             document.getElementById(messageid).innerHTML = response;
//             }
//             document.getElementById("Button").disabled = false;
//         }
//     });
// }

</script>