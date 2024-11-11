    <?php         
        $script[] = "sweetalert"; 
        $script[] = "select2"; 
    
    ?>
        <link rel="stylesheet" href="dist/libs/select2/dist/css/select2.min.css">
        <style>
.dropdown-container {
    position: relative;
    max-width: 300px;
    margin-left: 10px;
}

.dropdown-header {
    /* background-color: #f9f9f9; */
    padding: 5px;
    /* border: 1px solid #ccc; */
    cursor: pointer;
    text-align: left;
    position: relative;
}

.dropdown-header::after {
    content: '▼';
    position: absolute;
    right: 7px;
    top: 50%;
    font-size: 9px;
    transform: translateY(-50%);
}

.dropdown-list {
    position: absolute;
    width: 100%;
    max-height: 0;
    overflow: hidden;
    background-color: white;
    transition: max-height 0.1s ease;
    z-index: 100;
}

.dropdown-list.active {
    border: 1px solid #ccc;
    max-height: 300px;
    overflow-y: auto;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.dropdown-container #search {
    width: 100%;
    padding: 10px;
    box-sizing: border-box;
    border-bottom: 1px solid #ccc;
    position: sticky;
    border-radius: 0px!important;
}

.country-item {
    display: flex;
    align-items: center;
    padding: 10px;
    cursor: pointer;
}

.country-item:hover {
    background-color: #f0f0f0;
}

.flag {
    width: 30px;
    height: 20px;
    margin-right: 10px;
}

.country-name {
    font-size: 14px;
}

/* Responsive for smaller devices */
@media (max-width: 768px) {
    .dropdown-container {
        /* width: 100%; */
    }
}
        </style>
    <div class="card bg-white p-0">
        <div class="card-header p-4">
                <h6><b>SMS Verifications For: </b> </h6>
                <h4 class="text-primary">
               <b> <?php if($number_type == "short_term"){ 
                                        if(isset($_GET['networkName']) && $_GET['networkName'] != "") echo htmlspecialchars_decode($_GET['networkName']);
                                        if(!isset($_GET['networkName']) || $_GET['networkName'] == "") echo 'Short Term USA Number '.$network; 
                                    }else if($number_type != "short_term"){ echo str_replace("_", " ", $number_type). ' Network '.$network; } else{
                                        echo " Short term Numbers"; } ?></b>
                </h4>
                <p class="m-0">Get phone number to receive OTP for <a href="index?p=rentals&network=1&action=new">short term</a> or <a href="index?p=rentals&network=1&action=new&type=long_term"> long term</a> use.</p>
                <p class="m-0 text-black">You will receive an instant refund if you do not receive OTP. </p>
                <p class="text-muted m-0">To view rented numbers <a href="index?p=rentals" class="btn-sm">click here</a>.</p>

                <form class="position-relative">
                    <input type="text" 
                    data-search-list="search-items-details" 
                    data-search-attribute="name" 
                    class="form-control product-search ps-5" 
                    id="input-search-service"
                        placeholder="Search service..." />
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                </form>
            </div>
            <div class="card-body p-0 m-0">
                <p class="bg-light-danger text-black ps-4"><b>Note that the price are not fixed.
                   <?php if(!isset($_GET['currentprice'])) { ?> <a href="<?= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>&currentprice=true" class="btn-sm text-primary btn-light-dark">Update Price</a><?php } ?>
                </b></p>
                <div class="d-flex p-2 flex gap-2">
               
                    <div class="btn-group mb-2 print-ignore">
                            <button class="btn btn-sm <?php if($number_type == "short_term"){ echo 'btn-primary'; } else { echo 'btn-light-danger'; }; ?> dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-phone"></i> 
                                Click for all countries
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                                <li><a class="dropdown-item" href="index?p=rentals&action=new&networkName=Short Term Number 1 (USA)">Short Term Number 1 (USA)</a></li>
                                <li><a class="dropdown-item" href="index?p=rentals&network=2&action=new&networkName=Short Term  Number 2 (USA)">Short Term  Number 2 (USA)</a></li>
                                <li><a class="dropdown-item" href="index?p=rentals&network=3&action=new&countryCode=98&name=Germany&networkName=<?= htmlspecialchars('Germany/Netherlands (Short Term)') ?>">Germany & Netherlands (Short Term)</a></li>
                                <li><a class="dropdown-item" href="index?p=rentals&network=6&action=new&countryCode=16&symbol=GB&name=United%20Kingdom&networkName=All Countries (Short Term 1)">All Countries (Short Term 1)</a></li>
                                <li><a class="dropdown-item" href="index?p=rentals&network=5&action=new&countryCode=16&symbol=GB&name=England&networkName=All Countries (Short Term 2)">All Countries (Short Term 2)</a></li>
                            </ul>
                        </div>
                   
                    <div class="btn-group mb-2 print-ignore">
                            <button class="btn btn-sm <?php if($number_type != "short_term"){ echo 'btn-primary'; } else { echo 'btn-light-danger'; }; ?> dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Click for Long Terms No.
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                                <li><a class="dropdown-item" href="index?p=rentals&action=new&type=3days">Long Term Number (USA) <span class="text-primary">(3days)</span> </a></li>
                                <li><a class="dropdown-item" href="index?p=rentals&action=new&type=long_term">Long Term Number (USA) <span class="text-primary">(30days)</span></a></li>
                                <li><a class="dropdown-item" href="index?p=rentals&network=2&action=new&type=long_term">LTR (Network 2) (USA) <span class="text-primary">(30days)</span></a></li>
                            </ul>
                        </div>
                    </div>

                        <?php if($countries && is_array($countries)){ ?>

                            <div class="dropdown-container mb-2 ms-4">
                                <div class="dropdown-header btn btn-sm btn-light-danger" onclick="toggleDropdown()">
                                <?php if(isset($_GET['name']) && $_GET['name'] != "") {
                                     echo '<img src="'.($r->getKeyValue($_GET['name'], key: "name")  ? 'https://flagcdn.com/w320/'.strtolower($r->getKeyValue($_GET['name'], key: "name")).'.png' : 'https://t2.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=hsjdhsd.com&size=24').'" class="flag">';
                                     echo '<span class="country-name">' . $_GET['name'] .  '<small style="font-size: 10px"> - (click more Countries )</small></span>';
                                }else{
                                    echo "Select a country";
                                }    
                                ?>
                                </div>
                                <div class="dropdown-list" id="country-list">
                                    <input type="text" id="search" class="searchcountries form-control" placeholder="Search for a country..." onkeyup="filterCountries()">
                                    <div class="country-items">
                                        <?php
                                        foreach ($countries as $singleCountry) {
                                            $singleCountry = (array)$singleCountry;
                                            $countryName = $singleCountry['name'] ?? $singleCountry['country'] ?? $singleCountry['eng'];
                                            if($countryName == "USA") $countryName = "USA (Real)";
                                            if($countryName == "Southafrica") $countryName = "South Africa";
                                            if($network == "3" && ($countryName != "Germany" && $countryName != "Netherlands")) continue;
                                            if(($network == "4" || $network== "6") && $countryName == "Germany") continue;
                                            $code = $r->getKeyValue($countryName, key: "name");
                                            $countryID = $singleCountry['id'] ?? $singleCountry['ID'];
                                            $flagUrl = "https://t2.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=hsjdhsd.com&size=24";
                                            echo '<a href="index?p=rentals&network='.$network.'&action=new&countryCode='.$countryID.'&symbol='.$code.'&name='.$countryName.'" class="country-item text-black" onclick="selectCountry(\'' . $countryName . '\')">';
                                            echo '<img src="'.($code  ? 'https://flagcdn.com/w320/'.strtolower($code).'.png' : 'https://t2.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=hsjdhsd.com&size=24').'" alt="' . $countryName . ' flag" class="flag">';
                                            echo '<span class="country-name">' . $countryName . '</span>';
                                          
                                            echo '</a>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                </div>

               

                <?php 
                    if($number_type == "short_term" && ($network == 3 || $network == 4 || $network == 5 || $network == 6)) require_once "pages/rentals/anosim.php"; 
                    if($number_type == "short_term" && $network == 1 && $broker == "daisysms") require_once "pages/rentals/short.php"; 
                    if($number_type == "long_term" || $number_type == '3days' || $network == 2) require_once "pages/rentals/long.php";
                ?>
                <!-- table end -->
            </div>
        </div>

        
<script>
     document.querySelectorAll('.heart-button').forEach(button => {
            button.addEventListener('click', () => {
                const heartPath = button.querySelector('.heart-path');
                // Toggle fill color
                if (heartPath.getAttribute('fill') === '#fa5a15') {
                    heartPath.setAttribute('fill', 'none');
                } else {
                    heartPath.setAttribute('fill', '#fa5a15');
                }
            });
        });
</script>