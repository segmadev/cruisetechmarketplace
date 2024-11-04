<?php
class content extends database
{
    private array $data;
    private array $datas;
    private String | int $key;
    private array $accepted_type = ["input", "textarea", "select", "countries"];
    private String $placeholder = "---placeholderforinput---";
    // Example code for create_form
    // $users_form = [
    //     "full_name"=>[
    //     "title"=>"Enter full name",
    //     "value"=>"",
    //     "id"=>"my_full_name_id",
    //     "class"=>"add this class",
    //      "atb"=>"data-info='data'",
    //      "global_class"=>"add gloabl class",
    //     "placeholder"=>"Enter your full name", 
    //     "description"=>"Enter both first name and last name", 
    //     "is_required"=>true, 
    //     "input_type"=>"text", 
    //     "type"=>"input",
    // ],
    //     "gender"=>["placeholder"=>"Select your gender", "is_required"=>true, "options"=>["Male"=>"Male", "Female"=>"Female"], "type"=>"input"],
    //     "tell_us_more"=>["placeholder"=>"Tell us more about your self", "is_required"=>false, "type"=>"textarea",],
    //     "input_data"=>["full_name"=>"seriki gbenga"],
    // ];
    function display_image($path, $ID = "") {
        echo '<div id="image-' . $ID . '" data-url="modal?p=viewer&path='.$path.'" data-title="Image Viewer" onclick="modalcontent(this.id)" data-bs-toggle="modal" data-bs-target="#bs-example-modal-md" class="rounded-2 overflow-hidden">
                <img src="'.$path.'" alt="uploaded"  style="width: 30px">
            </div>';
    }
    function create_form($datas)
    {
        if (!is_array($datas)) {
            return null;
        }
        $main_code = "";
        $this->datas = $datas;
        foreach ($datas as $key => $data) {
            if (!is_array($data)) {
                continue;
            }
            if ($key == "input_data") {
                continue;
            }
            $this->data = $data;
            $this->key = $key;
            $this->data['star'] = "";
            $this->data["value"] = $this->get_value($datas, $key);
            if (!isset($this->data['global_class'])) {
                $this->data['global_class'] = "";
            }
            if (!isset($this->data['atb'])) {
                $this->data['atb'] = "";
            }
            
            if (!isset($this->data['type'])) {
                $this->data['type'] = "input";
            }
            if (!isset($this->data['title'])) {
                $this->data['title'] = ucwords(str_replace("_", " ", $key));
            }
            
            if (!isset($this->data['is_required']) || $this->data['is_required'] == true) {
                $this->data['is_required'] = true;
                if($this->data['title'] != "") {
                    $this->data['star'] = "*";
                }
            }

            if (!isset($this->data['id'])) {
                $this->data['id'] = $this->key;
            }
            if (!isset($this->data['class'])) {
                $this->data['class'] = $this->key;
            }
            if (!isset($this->data['placeholder'])) {
                $this->data['placeholder'] = "Enter " . ucwords(str_replace("_", " ", $key));
                if($this->data['type'] == "select"){
                    if(isset($data['title'])){
                        $this->data['placeholder'] = $data['title'];
                    }else{
                        $this->data['placeholder'] = "Select " . ucwords(str_replace("_", " ", $key));
                    }
                }
            }
            if ($this->data['type'] == "input" && !isset($this->data['input_type'])) {
                $this->data['input_type'] = "text";
            }
            if (!in_array($this->data['type'], $this->accepted_type)) {
                continue;
            }
            $type = $this->data['type'];
            if ($this->data['type'] == "input" && isset($this->data['input_type']) && $this->data['input_type'] == "hidden") {
                $main_code .=  $this->$type();
                continue;
            }
            // echo $key;
            // if ($key == "password") {
            //     $main_code .= $this->showpassword();
            // }

            $main_code .= str_replace($this->placeholder, $this->$type(), $this->get_header());
        }
        
        return $main_code;
    }

    function showpassword() {
        return  "<input type='checkbox' onclick='showPassword()'>Show Password";
    }

    function  get_header()
    {
        $info =  "<div class='mb-3 form-group col-12 col-md-6 " . $this->data['global_class'] . "'>
        <label>" . ucwords($this->data['title']) . " <span class='text-danger'>" . $this->data['star'] . "</span></label>
        <div class='controls'>" . $this->placeholder;
        if (isset($this->data['description'])) {
            $info .= "<div class='form-control-feedback " . $this->data['class'] . "'>
            <div class='help-block'></div>
            <small>" . $this->data['description'] . "</small>
          </div>";
        }

        $info .= "
        </div>
      </div>";
        return $info;
    }

    function input()
    {
        $info = "";
        $onchange = "";
    
        // Handle file input type with image preview
        if (isset($this->data['path']) && $this->data['input_type'] == "file") {
            $path = "";
            if (isset($this->datas["input_data"][$this->key])) {
                $path = $this->data['path'] . $this->datas["input_data"][$this->key];
            }
            $onchange = "onchange=\"showPreview(event, 'image-preview-" . $this->key . "')\"";
            $info .= "<div id='image-preview-" . $this->key . "' class='card shadow-md w-30 h-20 bg-gray p-3'><img src='$path?n=" . rand(10, 100) . "' style='width: 100px' alt=''></div>";
        }
    
        $required = "";
        if ($this->data['is_required']) {
            $required = "required";
        }
    
        // Build the input field with input-group for Bootstrap styling
        $info .= "<div class='input-group'>";
        $info .= "<input $onchange name='" . $this->key . "' value='" . $this->data['value'] . "' id='" . $this->data['id'] . "' type='" . $this->data['input_type'] . "' class='form-control " . $this->data['class'] . "' placeholder='" . $this->data['placeholder'] . "'" . $this->data['atb'];
    
        // Add validation if present
        if (isset(Regex[$this->key]['value'])) {
            $info .= " data-validation-required-message='" . Regex[$this->key]['error_message'] . "' aria-invalid='false'";
        }
    
        // Add required attribute if necessary
        $info .= " $required/>";
    
        // If input type is password, add the icon button for show/hide functionality
        if ($this->data['input_type'] == "password") {
            $info .= "<button class='btn btn-primary' type='button' id='toggle-password-" . $this->key . "' onclick='togglePassword(\"" . $this->data['id'] . "\", this)'>";
            $info .= "<i class='fa fa-eye' id='icon-" . $this->key . "'></i>"; // Bootstrap icon for "eye"
            $info .= "</button>";
        }
    
        $info .= "</div>"; // End of input-group
        return $info;
    }
    

    function get_value($datas, $key)
    {
        if (isset($_POST[$key])) {
            return htmlspecialchars($_POST[$key]);
        }
        if (isset($datas['input_data'][$key])) {
            return $datas['input_data'][$key];
        }
        return "";
    }
    function textarea()
    {
        return "<textarea id='" . $this->data['id'] . "' class='form-control' placeholder='" . $this->data['placeholder'] . "'  name='" . $this->key . "'>" . $this->data['value'] . "</textarea>";
    }
    function select()
    {
        if (isset($this->data['options'])) {
            $bracket = "";
            $placeholder = "";
            $word = "multiple='multiple'";
            if (strpos($this->data['atb'], $word) !== false) {
                $bracket = "[]";
                $placeholder = $this->data['placeholder'];
                unset($this->data['placeholder']);
            }

            $info = "<select data-placeholder='$placeholder' class='form-control " . $this->data['class'] . "' id='" . $this->data['class'] . "' " . $this->data['atb'] . " name='" . $this->key . $bracket . "'>";
            if (isset($this->data['placeholder'])) {
                $info .= "<option value=''>" . $this->data['placeholder'] . "</option>";
            }
            foreach ($this->data['options'] as $key => $value) {
                // if($key )
                $selected = "";
                if(is_array($this->data['value']) && in_array($key, $this->data['value'])) {
                    $selected = "selected";
                }
                if ($key == $this->data['value'] && !is_array($this->data['value'])) {
                    $selected = "selected";
                }
                $info .= "<option value='$key' $selected>$value</option>";
            }
            $info .= "</select>";
            return $info;
        }

        return null;
    }

    function radio() {
        if (isset($this->data['options'])) {
            $bracket = "";
            $placeholder = "";
            $word = "multiple='multiple'";
            if (strpos($this->data['atb'], $word) !== false) {
                $bracket = "[]";
                $placeholder = $this->data['placeholder'];
                unset($this->data['placeholder']);
            }

            $info = "<select data-placeholder='$placeholder' class='form-control " . $this->data['class'] . "' id='" . $this->data['class'] . "' " . $this->data['atb'] . " name='" . $this->key . $bracket . "'>";
            if (isset($this->data['placeholder'])) {
                $info .= "<option value=''>" . $this->data['placeholder'] . "</option>";
            }
            foreach ($this->data['options'] as $key => $value) {
                // if($key )
                $selected = "";
                if(is_array($this->data['value']) && in_array($key, $this->data['value'])) {
                    $selected = "selected";
                }
                if ($key == $this->data['value'] && !is_array($this->data['value'])) {
                    $selected = "selected";
                }
                $info .= "<option value='$key' $selected>$value</option>";
            }
            $info .= "</select>";
            return $info;
        }

        return null;
    }

    function countries()
    {
        if (isset($this->data['data'])) {
            $info = "<select name='" . $this->key . "' class='form-control select2 " . $this->data['class'] . "' id='template-with-flag-icons " . $this->data['class'] . "' " . $this->data['atb'] . ">";
            if ($this->data['placeholder']) {
                $info .= "<option value=''>" . $this->data['placeholder'] . "</option>";
            }
            foreach ($this->data['data'] as $value) {
                $selected = "";
                $key = $value['id'];
                if ($key == $this->data['value']) {
                    $selected = "selected";
                }
                $info .= "<option value='$key' data-flag='ad' $selected> " . $value['name'] . "</option>";
            }
            $info .= "</select>";
            return $info;
        }

        return null;
    }


    function badge($data)
    {
        $data = (string)ucfirst($data);
        if($data == "1") $data = "Active";
        if($data == "0") $data = "Expired";
        if($data == "2") $data = "Closed";
        $info = "<span class='badge bg-light-primary text-primary fw-semibold fs-2'>$data</span>";
        
        try {
           return match ($data) {
                  'Active', 'Approved','Success', 'Successful', "Allocated", "Completed"   => "<span class='badge bg-light-success text-success fw-semibold fs-2'>$data</span>",
                'Disable', 'Expired', 'Reject', 'Rejected' => "<span class='badge bg-light-danger text-danger fw-semibold fs-2'>$data</span>",
                'initiate', 'Pending' => "<span class='badge bg-light-warning text-warning fw-semibold fs-2'>$data</span>",
                "","Bot","Closed" => "<span class='badge bg-dark text-white fw-semibold fs-2'>$data</span>"
            };
        } catch (\Throwable $th) {
           return "<span class='badge bg-light-primary text-primary fw-semibold fs-2'>$data</span>";
        }
        
        return $info;
    }

    function empty_page($message, $btn= "", $h1 = "Nothing here!!", $icon = "<i class='ti ti-alert-square-rounded text-warning h1'></i>")
    {
        return "
        <div class='mt-3 col-12 text-center'>
    $icon
    <h4 class=''>$h1</h4>
    <p>$message</p>
    <div class='mt-3'>
        $btn
    </div>
</div>
        ";
    }
    

   



    function referral_list($data, $link, $class = "")
    {
        return "<a href='$link' class='card shadow-md p-3 col-12 col-md-5 m-1 zoom $class'>
                <div class='d-flex align-items-start justify-content-between'><h6 class='mr-auto p-2 m-0'>Referral " . $data['no_of_users'] . " People</h6> <ahref='$link' class='btn btn-sm btn-success'>Join.</ahref=></div>
                <b class='ps-2 text-success'>You will get:</b>
                
                <li class='ps-2 ml-auto text-right '>- Automatic free <b class='text-success'>" . $this->money_format($data['plan_amount'], currency) . "</b> investment when " . $data['no_of_users'] . " people are compeleted.</li>
                <hr>
                <li class='ps-2 ml-auto text-right '>- <b class='text-success'>" . $data['percentage_return_on_deposit'] . "% </b> on each referral first deposit will be automatically credited to your balance and can be withdrawn immediately.
                </li>
            </a>";
    }
    function terms_message()
    {
        return "<b>By proceeding you agree to our <a href='#' target='_blank'>team and conditions</a> and <a target='_blank' href='#'> privacy policy</a>.</b>";
    }

    function arrow_percentage($percent, $word = "profit")
    {
        $percent = round($percent, 2);
        $arrow = "up";
        $color = "success";
        if ($percent < 0) {
            $arrow = "down";
            $color = "danger";
        }

        return "<div class='d-flex align-items-center pb-1'>
        <span class='me-2 rounded-circle bg-light-$color round-20 d-flex align-items-center justify-content-center'>
          <i class='ti ti-arrow-$arrow-right text-$color'></i>
        </span>
        <p class='text-dark me-1 fs-3 mb-0'> $percent%</p>
        <p class='fs-3 mb-0'>$word</p>
      </div>";
    }

    function get_percent_theme($percent){
        $p = str_replace('%', "", $percent);
        if($p <= 33){
            return "danger";
        }
        if($p >= 33 && $p < 60){
            return "warning";
        }
        if($p >= 60){
            return "success";
        }
        return "primary";

    }

    function modal_attributes($url, $title = "Modal", $id = null) {
        if($id == null) $id = uniqid("modal-viewer-");
        return "href='javascript:void(0)' 
        data-bs-toggle='modal' 
        data-bs-target='#bs-example-modal-md' 
        id='$id' 
        data-url='$url' 
        data-title='$title' 
        onclick='modalcontent(this.id)'";
    }

    function copy_text($text, $class = "") {
        return "<a href='javascript:void(0)' onclick='copy_text(`$text`)' class='btn btn-sm $class'><i class='ti ti-copy'></i></a>";
    }
    

    // navigation 
    
    function renderSingleLink($linkKey, $action, $mainIcon = "ti ti-circle") {
        echo '<li class="sidebar-item">';
        echo '<a class="sidebar-link" href="' . htmlspecialchars($action['a']) . '" aria-expanded="false">';
        echo '<span><i class="' . htmlspecialchars($mainIcon) . '"></i></span>';
        echo '<span class="hide-menu">' . htmlspecialchars($action['title']) . '</span>';
        echo '</a>';
        echo '</li>';
    }

    function renderCollapsibleMenu($linkKey, $link, $r) {
        if($r->validate_action($linkKey)){
            $mainIcon = $link['icon'] ?? 'ti ti-dot'; // Use main icon if specified, otherwise default to ti ti-dot
            echo '<li class="sidebar-item">';
            echo '<a class="sidebar-link has-arrow" href="#" aria-expanded="false">';
            echo '<span><i class="' . htmlspecialchars($mainIcon) . '"></i></span>';
            echo '<span class="hide-menu">' . str_replace("_", " ", $linkKey) . '</span>';
            echo '</a>';
        
            echo '<ul aria-expanded="false" class="collapse first-level">';
            foreach ($link as $actionKey => $action) {
                // Skip the icon attribute when looping through actions
                if ($actionKey === 'icon' || !$r->validate_action([$linkKey=>$actionKey])) continue;
                echo '<li class="sidebar-item">';
                echo '<a href="' . htmlspecialchars($action['a']) . '" class="sidebar-link">';
                echo '<div class="round-16 d-flex align-items-center justify-content-center">';
                echo '<i class="ti ti-dot"></i>'; // Use ti ti-dot for all sub-links
                echo '</div>';
                echo '<span class="hide-menu">' . htmlspecialchars($action['title']) . '</span>';
                echo '</a>';
                echo '</li>';
            }
            echo '</ul>';
            echo '</li>';
        }   
    }

    function generateNavigation($navs, $r) {
        foreach ($navs as $sectionKey => $section) {
            $hasValidLink = false; // Flag to check if the section has any valid links
    
            // Loop through each link in the section to check for validity
            foreach ($section['links'] as $linkKey => $link) {
                $actionCount = count(array_filter($link, 'is_array')); // Filter to count only actions, ignoring icon
    
                if ($actionCount === 1) {
                    // Check if the single link is valid
                    $action = reset($link);
                    if ($r->validate_action([$linkKey => array_key_first($link)])) {
                        $hasValidLink = true;
                        break; // No need to check further links in this section
                    }
                } else {
                    // Check if any action in the collapsible menu is valid
                    foreach ($link as $actionKey => $action) {
                        if ($actionKey !== 'icon' && $r->validate_action([$linkKey => $actionKey])) {
                            $hasValidLink = true;
                            break 2; // Exit both inner and outer loop if a valid link is found
                        }
                    }
                }
            }
    
            // Only render the section title if there's at least one valid link
            if ($hasValidLink) {
                echo '<li class="nav-small-cap">';
                echo '<i class="ti ti-dots nav-small-cap-icon fs-4"></i>';
                echo '<span class="hide-menu">' . htmlspecialchars($section['title']) . '</span>';
                echo '</li>';
    
                // Render the valid links in this section
                foreach ($section['links'] as $linkKey => $link) {
                    $actionCount = count(array_filter($link, 'is_array'));
    
                    if ($actionCount === 1) {
                        $action = reset($link);
                        $mainIcon = $link['icon'] ?? 'ti ti-circle';
                        if ($r->validate_action([$linkKey => array_key_first($link)])) {
                            $this->renderSingleLink($linkKey, $action, $mainIcon);
                        }
                    } else {
                        $this->renderCollapsibleMenu($linkKey, $link, $r);
                    }
                }
            }
        }
    }
    
}
