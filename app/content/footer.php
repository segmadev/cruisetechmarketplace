</div>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <?php require_once "pages/notifications/list.php"; ?>
</div>
<!--  Customizer -->
<!-- <button class="btn btn-primary p-3 rounded-circle d-flex align-items-center justify-content-center customizer-btn" type="button" >
  <i class="ti ti-settings fs-7" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Settings"></i>
</button> -->
<div class="offcanvas offcanvas-end customizer offcanvas-size-xxl" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel" data-simplebar="">
  <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
    <h6 class="offcanvas-title fw-semibold" id="offcanvasExampleLabel">Users in group</h6>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body p-4">
    <form class="position-relative mb-4">
      <input type="search" class="form-control py-2 ps-5" oninput="search_div(this.value, 'grouplist')" id="text-srh-user" placeholder="Search User">
      <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
    </form>
    <div id="grouplist"></div>
  </div>
</div>
<br><br><br>
<style>
  .telegram-icon {
    position: fixed;
    bottom: 125px!important;
    right: 20px;
    z-index: 10000;
    cursor: pointer;
    width: 100px;
    border-radius: 10px;
    padding: 5px;
    font-size: 12px;
    background-color: deepskyblue;
    
    display: flex;
  }
  .telegram-icon img{
    width: 20px;
    margin-right:5px;
  }
</style>
<div class="telegram-icon"><a style="color: white!important;" target="_BLANK" href="https://t.me/cruisetechsupport"><img src="https://cdn.pixabay.com/photo/2021/12/27/10/50/telegram-icon-6896828_1280.png" alt=""> <b>Chat Us</b></a></div>
<footer class="d-flex justify-content-center bottom-nav" style="margin-top: 50px">
  <div class="col-11 shadow d-flex justify-content-around p-1 rounded botton-navs bg-light-primary">
    <a data-url="index" class="btn btn-sm p-2 m-0 <?php if ($page == "dashboard") {
                                  // echo "btn-primary";
                                } ?>"><i class='ti ti-home fs-6'></i>
                              <h6 class="p-0 m-0 fs-2">Home</h6>    
                              </a>

                              <a href="index?p=rentals&action=new" class="btn btn-sm p-2 m-0 <?php if ($page == "rentals" && $action != "list") {
                                  // echo "btn-primary";
                                } ?>"><i class='ti ti-phone fs-6'></i>
                              <h6 class="p-0 m-0 fs-2">Number</h6>    
                              </a>
                              <a data-url="index?p=rentals" class="btn btn-sm p-2 m-0 <?php if ($page == "rentals" && $action == "list") {
                                  // echo "btn-primary";
                                } ?>"><i class='ti ti-inbox fs-6'></i>
                              <h6 class="p-0 m-0 fs-2">Active No.</h6>    
                              </a>
    <a data-url="index?p=orders&type=account" class="btn btn-sm <?php if ($page == "orders") {
                                              // echo "btn-primary";
                                            } ?>"><i class='fs-6 ti ti-social'></i>
                                          <h6 class="p-0 m-0 fs-2">Accounts</h6>  
                                          </a>
    <a data-url="index?p=deposit" class="btn btn-sm <?php if ($page == "deposit") {
                                          // echo "btn-primary";
                                        } ?>"><i class='fs-6 ti ti-wallet'></i>
                                      <h6 class="p-0 m-0 fs-2">Wallet</h6>    
                                      </a>

    <!-- <a href="index?p=profile" class="btn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Settings"><img src="<?= $u->get_profile_icon_link($userID) ?>" alt="" width="40" height="40"></a> -->
  </div>

</footer>
<script>
  function search_div(keyword, id) {
    // const div = document.getElementById(id);
    // const children = div.querySelectorAll('*');

    for (const a of document.getElementById(id).querySelectorAll('a')) {
      const h6 = a.querySelector('h6');
      if (h6.innerHTML.toLowerCase().includes(keyword.toLowerCase()) || keyword == "") {
        // console.log(h6.innerHTML);
        a.style.setProperty("display", "block", "important");
      } else {
        a.style.setProperty("display", "none", "important");
      }
    }
  }
</script>

<?php require_once "content/foot.php"; ?>
<script>
  function getBrowserTheme() {
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }
</script>

</body>
<!--  footercdd nav -->
<!-- Mirrored from demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/html/main/ by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 14 Aug 2023 16:01:44 GMT -->
</html>
<script>

  // Function to adjust jdiv button on mobile view
function adjustJdivButtonOnMobile() {
    // Check if the viewport is mobile size (width <= 768px)
    if (window.innerWidth <= 968) {
        // Select the jdiv element with the zoom style
      //  const jdivButton = document.querySelector('.chaport-launcher-button');
        const mainjdivButton = document.querySelector('.chatway--trigger-container');
        // const mainjdivButtonVisible = document.querySelector('.brevo-conversations--visible');

        // If the jdivButton exists, modify its style
        
        // if (jdivButton) {
        //   jdivButton.style.transform = "scale(0.7)";  // Use transform instead of zoom
        //     // jdivButton.style.zoom = "0.2";  // Use transform instead of zoom
        // }
        // Adjust button position dynamically based on viewport height
        if (mainjdivButton) {
            mainjdivButton.style.transform = "scale(0.8)";
            const viewportHeight = window.innerHeight;
            const buttonHeight = mainjdivButton.offsetHeight;
            const pushAmount = Math.min(viewportHeight * 0.3, 60); // Push by 10% of the height, max 90px
            mainjdivButton.style.marginRight = `3px`;
            mainjdivButton.style.marginBottom = `${pushAmount}px`;
        }

        
    }
}

// Call the function initially
adjustJdivButtonOnMobile();

// Keep adjusting at intervals in case of dynamic layout changes
const fallbackInterval = setInterval(() => {
    adjustJdivButtonOnMobile();
}, 1000);

// // Function to adjust jdiv button on mobile view
// function adjustJdivButtonOnMobile() {
//     // Check if the viewport is mobile size (width <= 768px)
//     if (window.innerWidth <= 768) {
//         // Select the jdiv element with the zoom style
//         const jdivButton = document.querySelector('jdiv[style*="zoom"]');
//         mainjdivButton = document.querySelector('[class*="button__"]');
//         // console.log(jdivButton);
//         // If the jdivButton exists, modify its style
//         if (jdivButton)  jdivButton.style.zoom = "0.57";  // Reduce the zoom
//         if(mainjdivButton) mainjdivButton.style.marginTop  = "-150px";  // Push the button up
//     }
// }

// // Call the function initially
// adjustJdivButtonOnMobile();

// // Add an event listener to handle screen resizing
// // window.addEventListener('resize', adjustJdivButtonOnMobile);

// const fallbackInterval = setInterval(() => {
//   adjustJdivButtonOnMobile();
// }, 1000);
// if (typeof modalelements === 'undefined') {
// modalelements = document.querySelectorAll('[data-url]');
// iniModal(modalelements);

// function iniModal(modalelements) {
//     modalelements.forEach(element => {
//         element.style.cursor = 'pointer';

//         // Check if the event listener has already been added
//         if (!element.dataset.listenerAdded) {
//             element.addEventListener('click', function(e) {
//               console.log("it is me")
//                 modalcontentv2(element);
//             });

//             // Mark that the listener has been added
//             element.dataset.listenerAdded = 'true';
//         }
//     });
// }
// // }

  </script>
<?php ob_end_flush(); ?>