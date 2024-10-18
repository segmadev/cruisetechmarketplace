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
        "tiny_API"=>["is_required"=>false, "input_type"=>"password"],
        "account_preview"=>["type"=>"select", "Title"=>"Allow account preview", "options"=>["1"=>"Yes", "0"=>"No"]],
        "support_email"=>["input_type"=>"email"],
        "phone_number"=>["input_ype"=>"tel"],
        "company_address"=>["type"=>"textarea"],
        "default_currency"=>[],
        "welcome_note"=>["type"=>"textarea", "description"=>"Welcome note will display to new users who login to dashboard for the first time.", "global_class"=>"w-100"],
        "live_chat_widget"=>["type"=>"textarea", "global_class"=>"w-100"],
       ];
       $settings_form['input_data'] = $s->getdata($settings_form);
       $settings_form['input_data']['tiny_API'] = "--placeholder";
       $settings_help =["help_title"=>[], "get_help"=>["type"=>"textarea", "id"=>"richtext_help", "global_class"=>"w-100"]];
       $s->create_settings($settings_help);
       $settings_help['input_data'] = $s->getdata($settings_help);
       $settings_backup = [
            "dropbox_API"=>["input_type"=>"password", "is_required"=>false],
            "backup_interval"=>["input_type"=>"number", "title"=>"Backup Interval (in hour)", "description"=>"Backup Interval in hour"],
        ];
        $settings_backup['input_data'] = $s->getdata($settings_backup);
        $settings_backup['input_data']['dropbox_API'] = "--placeholder";
        $s->create_settings($settings_backup);

        $settings_social_media = [
            "telegram_link" => ["is_required"=>false],
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
        "exchange_rate"=>[],
        "exchange_rate_update_interval"=>["input_type"=>"number","atb"=>'step="0.0001"', "description"=>"value in minutes"],
        "fix_exchange_rate"=>["type"=>"select", "options"=>["yes"=>"Yes", "no"=>"No"]],
        "exchange_rate_API"=>["input_type"=>"password", "is_required"=>false, "description"=>"You can get your API key <a target='_BLANK' href='https://www.exchangerate-api.com'>here</a>"],
        "input_data"=>array_merge(["exchange_rate_API"=>"--placeholder", "flutterwave_public_key"=>"--placeholder", "flutterwave_secret_key"=>"--placeholder", "flutterwave_encyption_key"=>"--placeholder", "bvn"=>"00000000000"], $s->getdata(["min_deposit"=>[], "exchange_rate_update_interval"=>[], "max_deposit"=>[], "fix_exchange_rate"=>[], "exchange_rate"=>[]])),
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

    $rentals_settings = [
        "rental_number_expire_time"=>["input_type"=>"number","title"=>"rental number expire time (In mins)", "description"=>"How long should it take for rented numbers to expire, value in mins."],
        "rentals_API"=>["input_type"=>"password", "title"=>"Daisysms API Key", "is_required"=>false],
        "anosim_API"=>["input_type"=>"password", "title"=>"Anosim API Key", "is_required"=>false],
        "sms_bower_API"=>["input_type"=>"password", "title"=>"sms bower API API Key", "is_required"=>false],
        "sms_activation_API"=>["input_type"=>"password", "title"=>"sms activation service API API Key", "is_required"=>false],
        "nonvoipusnumber_email"=>["input_type"=>"password", "is_required"=>false],
        "nonvoipusnumber_password"=>["input_type"=>"password", "is_required"=>false],
        "added_value_amount_daisysms_short_term"=>["title"=>"Added Value Amount for dailysms (In NGN)", "description"=>"Amount to be added on the rental's API price. Value in <b class='text-danger'>NGN</b> "],
        "added_value_amount_anosim_short_term"=>["title"=>"Added Value Amount for Anosim (In NGN)", "description"=>"Amount to be added on the rental's API price. Value in <b class='text-danger'>NGN</b> "],
        "added_value_amount_sms_bower_short_term"=>["title"=>"Added Value Amount for smsbower.net (In NGN)", "description"=>"Amount to be added on the rental's API price. Value in <b class='text-danger'>NGN</b> "],
        "added_value_amount_sms_activate_two_short_term"=>["title"=>"Added Value Amount for sms-activate.io (In NGN)", "description"=>"Amount to be added on the rental's API price. Value in <b class='text-danger'>NGN</b> "],
        "added_value_amount_sms_activation_short_term"=>["title"=>"Added Value Amount for sms activation service (In NGN)", "description"=>"Amount to be added on the rental's API price. Value in <b class='text-danger'>NGN</b> "],
        "added_value_amount_nonvoipusnumber_short_term"=>["title"=>"Added Value Amount for nonvoipusnumber short (In NGN)", "description"=>"Amount to be added on the rental's API price. Value in <b class='text-danger'>NGN</b> "],
        "added_value_amount_nonvoipusnumber_long_term"=>[ "description"=>"Amount to be added on the rental's API price. Value in <b class='text-danger'>NGN</b> "],
        "added_value_amount_nonvoipusnumber_3days"=>[ "description"=>"Amount to be added on the rental's API price. Value in <b class='text-danger'>NGN</b> "],
        "notification_email"=>["input_type"=>"email", "description"=>"Active email to get notification on low balance"],
        "notify_low_balance_amount"=>["is_required"=>false, "description"=>"At what balance do you want to get notifications. Set to (0)zero to turn off notifications"],
        "notify_low_balance_amount_sms_activate_two"=>["is_required"=>false, "description"=>"At what balance do you want to get notifications."]
    ];
    $rentals_settings['input_data'] = $s->getdata($rentals_settings);
    $rentals_settings['input_data']['rentals_API'] = "--placeholder";
    $rentals_settings['input_data']['exchange_rate_API'] = '--placeholder';
    $rentals_settings['input_data']['nonvoipusnumber_email'] = '--placeholder';
    $rentals_settings['input_data']['nonvoipusnumber_password'] = '--placeholder';
    $rentals_settings['input_data']['sms_activation_API'] = '--placeholder';
    $rentals_settings['input_data']['sms_bower_API'] = '--placeholder';
    $rentals_settings['input_data']['anosim_API'] = '--placeholder';