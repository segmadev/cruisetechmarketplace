
  <?php 
  $service = (array) $service;
  $service = isset($service['187']) ? (array) $service['187'] : $service;
  $names = explode("/", $service['name']);
  $cost = (!is_array($service['cost'] ?? $service['price'])) ?  $d->money_format($r->valuedPrice($number_type, $broker, $service['cost'] ?? $service['price'])) : $service['cost'] ;?>

  <li class="search-items-details col-lg-4 col-md-6 col-12 p-0 m-0"> 
    
                        
                <div action="" class="w-100 py-6 d-flex align-items-center p-2 shadow-sm m-0">
                <form action="" id="foo" class="w-100">
                <div id="custommessage"></div>
                <input type="hidden" name="id" value="<?=  $key?>">
                <input type="hidden" name="page" value="rentals">
                <input type="hidden" name="type" value="<?= $number_type ?>">
                <input type="hidden" name="broker" value="<?= base64_encode($broker) ?>">
                <input type="hidden" name="network" value="<?= $network ?>">
                <input type="hidden" name="countryCode" value="<?= $countryCode ?? "" ?>">
                <input type="hidden" name="new_rent">
                <input type="hidden" name="confirm" value='
                <div class="d-flex mb-3 align-items-center">
                Are you sure you want to rent number for <?=$service['name']?>'> 
                    <button type="submit" class="border-0 bg-transparent d-flex align-items-center text-align-start" style="text-align: start; width: 90%">
                        <div data-name="<?= $service['name'] ?>" class="flex-shrink-0 bg-transparent rounded-circle round d-flex align-items-center justify-content-center">
                            <?php 
                                $domain = trim(strtolower($names[0])).".com";
                                if(trim(strtolower($names[0])) == "telegram" || trim(strtolower($names[0])) == "signal") {
                                    $domain = trim(strtolower($names[0])).".org";
                                }
                                
                                ?>
                          <img src="https://t2.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=http://<?=  $domain ?>&size=24" alt="" class="img-fluid" width="24" height="24">
                        </div>
                        <div class="ms-3">
                            <?php 
                                $avilable = null;
                                if(isset($service['count'])) $avilable = "Available: ".$d->short_no(((int)$service['count']), 8000);
                                if(isset($service['available'])) $avilable = "Available: ".$d->short_no((int)$service['available'], 8000);
                                if(isset($service['quantity'])) $avilable = "Available: ".$d->short_no((int)$service['quantity'], 8000);
                                if (strlen($service['name']) <= 23) {
                                    $name = $service['name'];
                                    echo '<h5 class="m-0 fs-3"> '. $d->short_text($name, 20) . '</h5>';
                                    $i = count($names);
                                } else {
                                    $name = $names[0];
                                    echo '<h6 class="m-0">' . $d->short_text($name, 20) . '</h6>';
                                    unset($names[0]);
                                    if(count($names) > 0) $name = implode(',', $names);
                                    echo '<span class="fs-2" style="color: black!important">'.$name.'</span>';
                                }
                                ?>
                          <!-- <h6 class="mb-1 fs-4 fw-semibold">PayPal</h6>
                          <p class="fs-3 mb-0">Big Brands</p> -->
                          <h6 class="fs-4 text-muted m-0 p-0">
                              <?php 
                                if(is_array($cost)) {
                                    $cost = $r->getKeyStats($cost);   
                                    if($cost == null) {
                                        echo ` <input type="hidden" name="maxPrice" value="0">`;
                                        echo "<p class='text-danger'><small>Not Available.</small></p>";
                                    }else{
                                        
                                        $i = 1;
                                        foreach ($cost as $value => $price) {
                                            $costID = $i++;
                                            $checked = "";
                                            // if($value < 20 || $price > 190) continue; 
                                            if($costID == 1) $checked = "checked";
                                           echo '
                                           <input type="radio" value="'.base64_encode($price).'" class="btn-check" name="maxPrice" id="option'.$costID.'" autocomplete="off" '.$checked .'/>
                                           <label class="btn btn-sm btn-outline-dark rounded p-0 p-1  font-medium" for="option'.$costID.'">'.$d->money_format($r->valuedPrice($number_type, $broker, $price)).'</label> ';
                                            
                                        }
                                        // echo "<p><small>Select max price you can pay.</small></p>";
                                    }
                                    
                                }else{ 
                                    
                                    // var_dump($service);
                                    ?> 
                                    <input type="hidden" name="maxPrice" value="<?= base64_encode($service['maxPrice'] ?? ($service['cost'] ?? $service['price'])) ?>">
                                    <?= $cost ?>
                               <?php }
                            ?>
                            <!-- <label class="btn btn-sm btn-outline-dark text-muted rounded p-0 p-1  font-medium" for="option1">N20,000</label>
                            <label class="btn btn-sm btn-outline-dark text-muted rounded p-0 p-1  font-medium" for="option1">N2,000</label> -->

                        </h6>
                        <span class="fs-1 m-0 p-0"><?= // $avilable ?></span>
                        
                    </div>
                        </form>
                    </button>
                    
                        <div class=" action-btn">
                            <form action="" id="foo">
                            <div id="custommessage"></div>
                                <input type="hidden" name="id" value="<?=$key?>">
                                <input type="hidden" name="page" value="rentals">
                                <input type="hidden" name="like_rental">
                            <button class="text-info heart-button edit bg-transparent border-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-heart" width="20" height="20" viewBox="0 0 24 24"  
                                <?php if (!in_array($key, $likeds)) {echo 'stroke-width="1.5" stroke="#2c3e50"';}?>
                                 fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill=""/>
                                    <path class="heart-path" fill="<?php if (in_array($key, $likeds)) {echo '#fa5a15';}?> " d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                                </svg>
                            </button>
                            </form>

                        </div>
                   
                    </li>