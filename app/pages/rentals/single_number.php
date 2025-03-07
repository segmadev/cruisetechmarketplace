<?php
$service = "";
$simplePage = $simlePage ?? "me";
if (!$simplePage) {
  if (isset($rent['serviceName']) && $rent['serviceName'] != "") {
    $service = $rent['serviceName'];
  } else if (isset($rent['serviceCode']) && $rent['serviceCode'] != "") {
    $service =  $r->getServices($rent['broker_name'], $rent['type'], $rent['serviceCode'], fromCookie: true);
    $service = ($rent['broker_name'] == "sms_bower" || !isset($service['name'])) ? ($r->getKeyValue($rent['serviceCode'], 'countrie/services.json') ?? "") : ($service['name'] ?? "");
  } else {
    $service = "unknown";
  }
}

?>
<li
    class="col-lg-4 col-md-6 col-12 m-0  service-details p-2 mt-2 <?php if ($simlePage != null && $simlePage !=  "me") echo 'p-0 mt-0 shadow' ?> ">
    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#bs-example-modal-md"
        id="chat_user_<?= $rent['accountID'] ?>"
        data-url="modal?p=rentals&action=view&accountID=<?= $rent['accountID'] ?>" data-title="<?= $rent['loginIDs'] ?>"
        class="px-4 py-3 bg-hover-light-black d-flex align-items-center chat-user bg-light"
        data-user-id="<?= $rent['ID'] ?>">
        <div class="ms-6 d-inline-block">
            <h6 class="mb-1 fw-semibold chat-title" data-username="<?= $rent['loginIDs'] ?> <?= $service ?>">
                <?= $rent['loginIDs'] ?> </h6>
            <span class="fs-2 text-body-color d-block" data-service-type='<?= $service ?>'><?= $service ?></span>

            <div data-status="<?= (int)$rent['status'] ?>"
                data-countdown="<?= $r->getSecondsUntilExpiration($rent['expire_date']) ?>"></div>
            <span href="javascript:void(0)" class="btn btn-sm btn-primary">View Details</span>
        </div>
    </a>
</li>