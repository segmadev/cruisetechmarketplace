<?php 
    $script[] ="modal";
    $script[] ="sweetalert";
?>
<div class="col-12 d-flex row">
    <div class="col-12 col-md-3 <?php if(isset($_GET['id'])){ echo "d-none d-sm-none d-md-block"; } ?>">
        <?php require_once "pages/chat/chat-list.php"; ?>
    </div>
    <div class="col-12 col-md-9 <?php if(!isset($_GET['id'])){ echo "d-none d-sm-none d-md-block"; } ?>">
        <?php 
        if(isset($chatID)) {
            require_once "pages/chat/messages.php"; 
        }else{
           echo $c->empty_page("No chat selected. Please select a chat first.", h1: "No chat.", icon: "<i class='ti ti-message text-warning h1'></i>");
        }
        ?>
        
    </div>
</div>

<!-- show: app-chat-offcanvas border-start app-chat-right -->
<!-- not show: app-chat-offcanvas border-start -->


<!-- mobile list of users chats -->

<div class="offcanvas offcanvas-start" tabindex="-1" id="example2" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <div class="offcanvas-title d-flex align-items-center justify-content-between" id="offcanvasExampleLabel">
            <h5 class="mb-0 fs-5 fw-semibold">Chats</h5>
            <span class="badge bg-primary rounded-4 px-3 py-1 lh-sm"></span>
        </div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul id="mobile-chat-list" class="chat-users">Loading chats...</ul>
    </div>
</div>
<!-- show: app-chat-offcanvas border-start app-chat-right -->
<!-- not show: app-chat-offcanvas border-start -->