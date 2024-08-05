<?php
    $service = (array)$service;
    $service = (array)$service['187'];
    $names = explode("/", $service['name']);
    $cost = $d->money_format($r->valuedPrice($key, $service['cost']));
?>
<tr class="search-items card-body">
<td>    
<div class="d-flex align-items-center">
        <!-- <img src="../dist/images/profile/user-1.jpg" alt="avatar" class="rounded-circle" width="35" /> -->
        <div class="ms-3">
            <form action="" id="foo">
                <div id="custommessage"></div>
                <input type="hidden" name="id" value="<?= $key?>">
                <input type="hidden" name="page" value="rentals">
                <input type="hidden" name="new_rent">
                <input type="hidden" name="confirm" value="Are you sure you want to rent number for <?= $service['name'] ?>">
            <button class="user-meta-info btn w-100 h-100" >
                <?php 
                if(strlen($service['name']) <= 23) {
                    $name = $service['name'];
                    echo '<h6 class="user-name mb-0 fs-2 m-0" data-name="'.$name.'">'. $d->short_text($name, 20).'</h6>';
                    $i = count($names);
                }else{
                    for ($i=0; $i < count($names); $i++) { 
                        $name = $names[$i];
                        echo '<h6 class="user-name mb-0 fs-2 m-0" data-name="'.$name.'">'. $d->short_text($name, 20).'</h6>';
                    } 
                }
                    
                ?>
            </button>
            </form>
        </div>
    </div>
    </td>
    <td>
        <span class="usr-email-addr" data-price=""><?= $cost ?></span>
    </td>
    <td>
        <div class="action-btn">
            <a href="javascript:void(0)" class="text-info edit">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-heart" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                </svg>
            </a>
            <a href="javascript:void(0)" class="text-dark delete ms-2">
                <i class="ti ti-trash fs-5"></i>
            </a>
        </div>
    </td>
</tr>