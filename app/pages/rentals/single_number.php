<?php 
 $service = "";
 $simplePage = $simlePage ?? null;
if(!$simplePage) {
    if(isset($rent['serviceName']) && $rent['serviceName'] != "") {
      $service = $rent['serviceName'];
    }else if(isset($rent['serviceCode']) && $rent['serviceCode'] != "") {
          $service =  $r->getServices($rent['broker_name'], $rent['type'], $rent['serviceCode'], fromCookie: true);
          $service = $service['name'];
      }else{
          $service = "unknown";
      }    
}

  ?>
<li class="col-lg-4 col-md-6 col-12 m-0  service-details p-2 mt-2 <?php if($simlePage) echo 'p-0 mt-0 shadow' ?> ">
  <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#bs-example-modal-md" id="chat_user_<?= $rent['accountID'] ?>" data-url="modal?p=rentals&action=view&accountID=<?= $rent['accountID'] ?>" data-title="<?= $rent['loginIDs'] ?>" onclick="modalcontent(this.id)"   class="px-4 py-3 bg-hover-light-black d-flex align-items-center chat-user bg-light" data-user-id="<?= $rent['ID'] ?>">
    <div class="ms-6 d-inline-block">
      <h6 class="mb-1 fw-semibold chat-title" data-username="<?= $rent['loginIDs'] ?> <?= $service ?>"><?= $rent['loginIDs'] ?> </h6>
      <span class="fs-2 text-body-color d-block" data-service-type='<?= $service ?>'><?= $service ?></span>
      <div 
      data-status="<?= (int)$rent['status'] ?>"
      data-countdown="<?= $r->getSecondsUntilExpiration($rent['expire_date']) ?>"
      ></div>
      </div>
    </a>
  </li>