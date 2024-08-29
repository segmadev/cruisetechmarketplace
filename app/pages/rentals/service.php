
  <?php 
  $service = (array) $service;
  $service = isset($service['187']) ? (array) $service['187'] : $service;
  $names = explode("/", $service['name']);
  $cost = $d->money_format($r->valuedPrice($key, $service['cost'] ?? $service['price']));?>
  
  <li class="search-items-details col-lg-4 col-md-6 col-12 p-0 m-0">
                <div action="" class="w-100 py-6 d-flex align-items-center p-2 shadow-sm m-0">
                <form action="" id="foo" class="w-100">
                <div id="custommessage"></div>
                <input type="hidden" name="id" value="<?=  $key?>">
                <input type="hidden" name="page" value="rentals">
                <input type="hidden" name="type" value="<?= $number_type ?>">
                <input type="hidden" name="broker" value="<?= $broker ?>">
                <input type="hidden" name="network" value="<?= $network ?>">
                <input type="hidden" name="new_rent">
                <input type="hidden" name="confirm" value="Are you sure you want to rent number for <?=$service['name']?>"> 
                    <button type="submit" class="border-0 bg-transparent d-flex align-items-center text-align-start" style="text-align: start; width: 90%">
                        <div data-name="<?= $service['name'] ?>" class="flex-shrink-0 bg-transparent rounded-circle round d-flex align-items-center justify-content-center">
                          <img src="https://t2.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=http://<?=  trim(strtolower($names[0])) ?>.com&size=24" alt="" class="img-fluid" width="24" height="24">
                        </div>
                        <div class="ms-3">
                            <?php 
                                $avilable = null;
                                if(isset($service['count'])) $avilable = "Available: ".$d->short_no(((int)$service['count']), 8000);
                                if(isset($service['available'])) $avilable = "Available: ".$d->short_no((int)$service['available'], 8000);

                                if (strlen($service['name']) <= 23) {
                                    $name = $service['name'];
                                    echo '<h5 class="m-0 fs-3"> '. $d->short_text($name, 20) . '</h5>';
                                    $i = count($names);
                                } else {
                                    $name = $names[0];
                                    echo '<h6 class="m-0">' . $d->short_text($name, 20) . '</h6>';
                                    unset($names[0]);
                                    if(count($names) > 0) $name = implode(',', $names);
                                    echo '<span class="fs-2">'.$name.'</span>';
                                }
                                ?>
                          <!-- <h6 class="mb-1 fs-4 fw-semibold">PayPal</h6>
                          <p class="fs-3 mb-0">Big Brands</p> -->
                          <h6 class="fs-4 text-muted m-0 p-0"><?=$cost?></h6>
                          <span class="fs-1 m-0 p-0"><?= $avilable ?></span>
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