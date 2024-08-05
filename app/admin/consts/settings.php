<?php 
    // $logo_path = ROOTFILE."assets/images/logos/";
    $logo_path = "../assets/images/logos/";
    $logo_from = [
        "light_logo"=>["input_type"=>"file", "is_required"=>false, "path"=>$logo_path],
        "dark_logo"=>["input_type"=>"file", "is_required"=>false, "path"=>$logo_path],
        "favicon"=>["input_type"=>"file", "is_required"=>false, "path"=>$logo_path],
    ];
    $logo_from['input_data'] = $s->getdata($logo_from);
    $settings_form = [
        "company_name"=>[],
        "website_url"=>[],
        "support_email"=>["input_type"=>"email"],
        "phone_number"=>["input_ype"=>"tel"],
        "company_address"=>["type"=>"textarea"],
        "default_currency"=>[],
        "welcome_note"=>["type"=>"textarea", "description"=>"Welcome note will display to new users who login to dashboard for the first time.", "global_class"=>"w-100"],
        "live_chat_widget"=>["type"=>"textarea", "global_class"=>"w-100"],
       ];
       $settings_form['input_data'] = $s->getdata($settings_form);
        $settings_social_media = [
            "facebook_link" => ["is_required"=>false],
            "instagram_link" => ["is_required"=>false],
            "x_link" => ["is_required"=>false],
            "tiktok_link" => ["is_required"=>false],
        ];
        
        $settings_social_media['input_data'] = $s->getdata($settings_social_media);
        $settings_seo = [
            "seo_title" => ["is_required"=>false],
            "seo_description" => ["is_required"=>false],
            "seo_tags" => ["is_required"=>false],
        ];
        
        $settings_seo['input_data'] = $s->getdata($settings_seo);
   
    // var_dump($settings_form);
    $settings_deposit_form = [
        "flutterwave_public_key"=>["input_type"=>"password", "is_required"=>false],
        "flutterwave_secret_key"=>["input_type"=>"password", "is_required"=>false],
        "flutterwave_encyption_key"=>["input_type"=>"password", "is_required"=>false],
        "bvn"=>["input_type"=>"number", "is_required"=>false],
        "min_deposit"=>["input_type"=>"number"],
        "max_deposit"=>["input_type"=>"number", "is_required"=>false],
        "input_data"=>array_merge(["flutterwave_public_key"=>"--placeholder", "flutterwave_secret_key"=>"--placeholder", "flutterwave_encyption_key"=>"--placeholder", "bvn"=>"00000000000"], $s->getdata(["min_deposit"=>[], "max_deposit"=>[]])),
    ];

    $term_and_policy_condition = [
        "terms_and_conditions"=>["type"=>"textarea", "id"=>"richtext", "global_class"=>"col-md-12"],
        "policy"=>["type"=>"textarea", "id"=>"richtext2", "global_class"=>"col-md-12"],
    ];
    $term_and_policy_condition['input_data'] = $s->getdata($term_and_policy_condition);

    $admin = $d->getall("admins", "ID = ?", [$adminID]);
    if(!is_array($admin)) {exit();}
    $admin_account = [
        "email"=>["input_type"=>"email"],
        "current_password"=>["input_type"=>"password"],
        "password"=>["title"=>"Change Password","input_type"=>"password", "is_required"=>false],
        "confirm_password"=>["title"=>"Re-type Change Password", "input_type"=>"password", "is_required"=>false],
        "input_data"=>["email"=>$admin['email']],
    ];