<?php 
    $kyc = $d->getall("users", "valid_ID != ?  and valid_ID IS NOT NULL ORDER BY kyc_status DESC", [""], fetch: "moredetails");
