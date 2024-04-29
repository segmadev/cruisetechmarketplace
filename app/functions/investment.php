<?php
class investment extends user
{
    //topup investment
    function topup_investment($userID, $data) {
        $data = $this->validate_form($data);
        if(!is_array($data)) return false;
        $invest = $this->getall("investment", "ID = ? and userID = ?", [$data['ID'], $userID]);
        if(!is_array($invest)) return false;
        $topup_bonus = (float)$this->get_settings('topup_bonus');
        $amount = (float)$data['amount'];
        $bonus = $this->money_percentage($topup_bonus, $amount);
        // debit amount from balance
        $credit = $this->credit_debit($userID, $amount, 'balance', 'debit', 'Investment topup', $data['ID']);
        if(!$credit) return false;
        // update the investment amount, trade_amount 
        $id = $invest['ID'];
        $update = $this->update("investment", ["amount"=>(float)$invest['amount'] + $amount, "trade_amount"=>(float)$invest['trade_amount'] + $amount], "ID = '$id'");
        if(!$update){
            $this->credit_debit($userID, $amount, 'balance', 'credit', 'Reverse: Investment topup', $data['ID']);
            return false;
        } 
        // credit the trade_bonus
        $credit = $this->credit_debit($userID, $bonus, 'trade_bonus', 'credit', 'Investment topup bonus', $data['ID']);
        if(!$credit) return $this->message("Your Investment topup was successfull. <b class='text-danger'>We are unable to credit you your bonus of $bonus, please email us at ".$this->get_settings('support_email')." for help.</b>", "success");
        // return $this->message("Invesment topup successfull. You might have to reload page to see effect", "success");
        $return = [
                "message" => ["Sucess", "Invesment topup successfull.", "success"],
                "function" => ["loadpage", "data" => ["index?p=investment&action=view&id=$id", "success"]],
            ];
        return json_encode($return);
    }
    function get_X_promo($userID) {
        $data = $this->getall("promo_assigned", "userID = ? and start_date <= ? and end_date >= ? and status = ?", [$userID, time(), time(), "active"]);
        if(!is_array($data)) { return 0; }
        $promo = $this->getall("promo", "ID = ? and status = ?", [$data['promoID'], "active"]);
        if(!is_array($promo)) { return 0; }
        return (int)$promo['rate'];
    }
    // apply for promo
    function activate_promo($data, $userID) {
        $data = $this->validate_form($data);
        if(!is_array($data)) { return null; }
        $data['userID'] = $userID;
        $userID = $data['userID'];
        $promo = $this->getall("promo", "ID = ? and assigned_users LIKE '%$userID%' and status = ?", [$data['promoID'], "active"], fetch: "details");
        if(!is_array($promo)) { 
            return $this->message("Promo not active.", "error");
        }
        // check if promo exit
        if($this->getall("promo_assigned", "userID = ? and end_date >= ?", [$data['userID'], time()], fetch:'') > 0) {
           return $this->message("You have active promo on your account.", "error");
        }
        //  debit amount
        if (!$this->credit_debit($data['userID'], $promo['purchase_price'], "balance", "debit", for: "Promo")) {
            return;
        }
        $data['start_date'] = time();
        $data['end_date'] = strtotime('+3 days', $data['start_date']);
        $this->quick_insert("promo_assigned", $data, "Congratuations Promo Activated.");
    }

    // generate daily notification 
    function daily_report_notification($userID = "",  $date = null) {
        if($date == null) {$date  = date("Y-m-d");}
        // generate notification
        // message: Our AI Bot general report for today (10-09-2023)
        // Trades taken: 
        // profit:
        // lost: 
        // check if report already exit   
            if($this->getall("notifications", "userID = ? and n_for = ? and date_set = ?", ["all", "global_report", $date], fetch: "") == 0){
                $tr = $this->get_trade_report(date: $date, type: "global");
                $data = [];
                $data['userID'] = "all";
                $data['n_for'] = "global_report";
                $data['title'] = "Our AI bot general report for today ($date)";
                $data['description']  = "Trade(s): ".number_format($tr['trade_no'])." Profit: ".$this->money_format($tr['profit'], currency ?? "$")." Lost: ".$this->money_format($tr['lost'], currency ?? "$");
                $data['time_set'] = time();
                $data['date_set'] = $date;
                $data['url'] = ROOT."index?type=global&p=investment&action=trades&date=$date";
                $this->new_notification($data);
                // send email to user's email.
                $message = $this->replace_word([' ${first_name}'=>"", '${message_here}'=>$data['description']], $this->get_email_template("default")['template']);
                $sendmessage = $this->smtpmailer($this->get_all_emails(), $data['title'], $message);
            }
           
        // user notification
        // message: Trades taken on your account for today (10-09-2023)
        if($userID != "") {
            if($this->getall("notifications", "userID = ? and n_for = ? and date_set = ?", [$userID, "my_report", $date], fetch: "") > 0) {
                return false;
            }
            $tr = $this->get_trade_report(uid: $userID, date: $date, type: "");
            $data = [];
            $data['userID'] = $userID;
            $data['n_for'] = "my_report";
            $data['title'] = "Trades taken on your account for today ($date)";
            $data['description']  = "Trade(s): ".number_format($tr['trade_no'])." Profit: ".$this->money_format($tr['profit'], currency ?? "$")." Lost: ".$this->money_format($tr['lost'], currency ?? "$");
            $data['time_set'] = time();
            $data['date_set'] = $date;
            $data['url'] = ROOT."index?type=&p=investment&action=trades&date=$date";
            $this->new_notification($data);
            // send email to users
            $message = $this->replace_word([' ${first_name}'=>$this->get_name($data['userID']), '${message_here}'=>$data['description']], $this->get_email_template("default")['template']);
            $sendmessage = $this->smtpmailer($this->get_all_emails(), $data['title'], $message);
        }

    }

    function get_trade_report($uid = "", $date = "",  $type = "global", $start = 0) {
        $more = "";
        $tmore = "";
        $more_value = "";
        $userinfo = "userID = ? and ";
        if($type == "global") {
            $userinfo = "";
            $uid = "";
        }
        if($date != "") {
          $date = htmlspecialchars($_GET['date']);
          $more = "trade_date = ? and ";
          $tmore = "and date <= ?";
          $more_value = $date;
        }
        $invest = $this->getall("investment", "$userinfo status = ? $tmore", array_values(array_filter([$uid, "active", $more_value])), "COUNT(*) as no, SUM(amount) as amount");
        $condition = "$userinfo $more  status = ?  order by trade_time DESC";
        $trade_no = $this->getall("trades", "$condition", array_values(array_filter([$uid, $more_value, "closed"], 'strlen')), fetch: "");
        $trades = $this->getall("trades", "$condition  LIMIT $start, 20", array_values(array_filter([$uid, $more_value, "closed"], 'strlen')), fetch: "moredetails");
        $lost = $this->getall("trades", "$userinfo  intrest_amount < ? and $more  status = ?  order by trade_time DESC", array_values(array_filter([$uid, 0, $more_value, "closed"], 'strlen')), "SUM(intrest_amount) as amount");
        $profit = $this->getall("trades", "$userinfo  intrest_amount > ? and $more  status = ?  order by trade_time DESC", array_values(array_filter([$uid, 0, $more_value, "closed"], 'strlen')), "SUM(intrest_amount) as amount");
        return ["trades"=>$trades, "no_invest"=>$invest['no'], "total_ivest"=>$invest['amount'], "lost"=>$lost, "profit"=>$profit, "trade_no"=>$trade_no];
    }   

    function new_investment($data, $type = "tranding_balance")
    {
        $_POST['ID'] = uniqid();
        if (!is_array($data)) {
            echo $this->message("Err: No data passed.", "error");
            return null;
        }
        $info = $this->validate_form($data);
        if (!is_array($info)) {
            return null;
        }
        $user = $this->getall("users", 'ID = ?', [$info['userID']], "balance, trading_balance");
        // var_dump($user);
        if (!is_array($user)) {
            $this->message("User Not found", "error");
            return null;
        }
        $url = "?p=investment&action=view&id=".$info['ID'];
        if ((float)$info['amount'] > (float)$user['trading_balance']) {
            if($type == "quick") {
                $info['status'] = "pending";
                $url = "index?p=deposit&action=new&amount=".$info['amount'];
            }else{
                echo $this->message("You do not have enough in your trading account. <a href='index?p=deposit&action=new'>Make a deposit</a> or <a href='#' class='btn text-primary' data-url=\"modal?p=investment&action=transfer&funds_to=trading_account\" data-title=\"Transfer Funds\" onclick=\"modalcontent(this.id)\" data-bs-toggle=\"modal\" data-bs-target=\"#bs-example-modal-md\" id='transfer-modal'>transfer</a> from your balance to your trading account", "error");
                return null;
            } 
        }
        if (!$this->check_plan($info['planID'], $info['amount'])) {
            // $this->message("This plan is no more active", "error");
            return null;
        }
        $info['trade_amount'] = $info['amount'];
        $insert = $this->quick_insert("investment", $info);
        if (!$insert) {
            $this->message("We are having issue creating your plan.", "error");
            return null;
        }
        $this->activate_pending_compound($info['userID']);
        $actInfo = ["userID" => $info['userID'],  "date_time" => date("Y-m-d H:i:s"),"action_name" => "Create Investment", "description" => "An investment of ".$this->money_format($info['amount'], currency)." was created.", "action_for"=>"investment", "action_for_ID"=>$info['ID']];
        $this->new_activity($actInfo);
        $userID = $info['userID'];
        if($type != "quick") {
            $new_balance =  (float)$user['trading_balance'] - (float)$info['amount'];
            $update = $this->update("users", ['trading_balance' => $new_balance], "ID = '$userID'"); 
        }
        
        $id = $info['ID'];
        $return = [
            "message" => ["Sucess", "Invesment Plan created successfully.", "success"],
            "function" => ["loadpage", "data" => ["$url", "success"]],
        ];
        return json_encode($return);
    }

    function activate_pending_compound($userID) {
        $compound = $this->getall("compound_profits_assigned", "userID = ?", [$userID], "compound_profits");
        if(is_array($compound)) {
            $this->activate_compound_on_all_investment($compound['compound_profits'], $userID);
        }
    } 
    function activate_compound_on_all_investment($compound_profitID, $userID) : bool {
        // get all users investment that are not in compund_profit
        // loop through it and insert the new data.
        $compound = $this->getall("compound_profits", "ID = ? and assigned_users LIKE '%$userID%' and status = ? ", [$compound_profitID, 'active'], fetch: "");
        if($compound == 0) { return false; }
        // $investments = $this->getall("investment JOIN compound_profits_assigned on investment.ID != compound_profits_assigned.investmentID", "investment.userID = ? and compound_profits_assigned.userID = ?", [$userID, $userID], "investment*", fetch: "moredetails");
        $investments = $this->getall("investment inner join compound_profits_assigned on investment.ID != compound_profits_assigned.investmentID", "investment.userID = ? GROUP BY investment.userID", [$userID], "investment.*", fetch: "moredetails");
        // var_dump($investments->rowCount()); 
        if($investments->rowCount() == 0) { return true; }
        
        foreach($investments as $row) {
            // var_dump($row); 
            $data = ["compound_profits"=>$compound_profitID, "investmentID"=>$row['ID'], "userID"=>$userID];
            // check again
             if($this->getall("compound_profits_assigned", "investmentID = ?", [$row['ID']], fetch: "") > 0) { continue; }
             $this->quick_insert("compound_profits_assigned", $data);
        }
        return true;
    }

    function activate_compound_profits($data)
    {
        $info = $this->validate_form($data, "compound_profits_assigned");
        if (!is_array($info)) {
            return;
        }
            // echo "yes";
        // chceck of active one 
        // if active and amount is gater the minus

        $roll = $this->getall("compound_profits", "ID = ? and status = ?", [$info['compound_profits'], "active"]);
        if (!is_array($roll)) {
            $this->message("Compound profits deleted or not active anymore", "error");
            return;
        }
        $check = $this->getall("compound_profits_assigned", "userID = ? and status = ?", [$info['userID'], "active"]);
        if(is_array($check)){
            $compound = $this->getall("compound_profits", "ID = ?", [$check['compound_profits']]);
            $roll['purchase_price'] = $roll['purchase_price'] - $compound['purchase_price'];
            if($roll['purchase_price'] < 0){
                $this->message("Sorry we can not activate this compound profit for you at the moment.", "error");
                return false;
            }

        }
        //  debit amount
        if (!$this->credit_debit($info['userID'], $roll['purchase_price'], "balance", "debit", for: "Compound_profits")) {
            return;
        }
        //   credit profit
        $check = $this->getall("compound_profits_assigned", "userID = ?", [$info['userID']]);
        if ($check == 0) {
            if (!$this->credit_debit($info['userID'], $roll['bonus_price'], "trade_bonus", for: "Trade Bonus")) {
                return;
            }
        }

        if(isset($check['ID'])){
            $insert = $this->update("compound_profits_assigned", ["compound_profits"=>$info['compound_profits']], "ID = '".$check['ID']."' and userID = '".$info['userID']."'", "Compound profits assigned to investments successfully");
        }else{
        $insert = $this->quick_insert("compound_profits_assigned", $info, "Compound profits assigned to investments successfully");
        }

        if($insert) {
            $this->activate_compound_on_all_investment($info['compound_profits'], $info['userID']);
            $actInfo = ["userID" => $info['userID'],  "date_time" => date("Y-m-d H:i:s"),"action_name" => "compound_profits Activated", "description" => "A compound_profits was actiaved on an investment.", "action_for"=>"compound_profits_assigned", "action_for_ID"=>$roll['ID']];
        $this->new_activity($actInfo);
        }
    }

    function pause_compound_profits($id, $status = null, $userID = "admin")
    {
        if($status != "active" &&  $status != "deactive" && $status != null) {
            return null;
        }
        $data = $this->getall("compound_profits_assigned", "ID = ? and userID = ?", [$id, $userID]);
        if (!is_array($data)) {
            return null;
        }
        if ($status == null) {
            if ($data['status'] == 'active') {
                $status = "deactive";
            } else {
                $status = "active";
            }
        }
        $update = $this->update("compound_profits_assigned", ["status" => $status], "ID = '$id'");
        if($update) {
            $actInfo = ["userID" => $userID,  "date_time" => date("Y-m-d H:i:s"),"action_name" => "compound_profits $status", "description" => "A compound_profits was set to be $status.", "action_for"=>"compound_profits_assigned", "action_for_ID"=>$id];
            $this->new_activity($actInfo);
        }
    }

    function get_compound_profits($investID) {
        $data = $this->getall("compound_profits_assigned", "investmentID = ? or userID = ?", [$investID, $investID]);
        if(!is_array($data)) { return false; }
        return $data;
    }
    function apply_compound_profits()
    {
    }

    function apply_weekly_compound_profits()
    {
        if (date("N") == 7) {
            $date = date("Y-m-d");
            $time = date("H:i:s");
            $compound_profits = $this->getall("
            compound_profits_assigned 
            JOIN compound_profits ON compound_profits_assigned.compound_profits = compound_profits.ID AND compound_profits.assigned_users LIKE CONCAT('%', compound_profits_assigned.userID, '%')
            ", "compound_profits_assigned.last_date <= ? and compound_profits_assigned.status = ? and compound_profits.type = ?", [$date, 'active', 'weekly'], "compound_profits_assigned.ID as compound_profits_assignedID, compound_profits_assigned.investmentID as investmentID, compound_profits_assigned.userID as userID", fetch: "moredetails");
            if ($compound_profits->rowCount() == 0) {
                return true;
            }

            $lastMonday = date("Y-m-d", strtotime("last Monday"));
            $lastSaturday = date("Y-m-d", strtotime("last Saturday"));
            foreach ($compound_profits as $row) {
                $invests = $this->getall("investment", "userID = ?", [$row['userID']], fetch: "moredetails");
                if ($invests->rowCount() == 0) {
                    continue;
                }
                foreach ($invests as $ins) {
                $row['investmentID'] = $ins['ID'];
                $sum = $this->getall("trades", "investmentID = ? and intrest_amount > ? and trade_date >= ? and trade_date <= ?", [$row['investmentID'],  0, $lastMonday, $lastSaturday], "userID as userID, SUM(intrest_amount) as total_intrest");
                $investID = $row['investmentID'];
                $invest = $this->getall("investment", 'ID = ?', [$investID], 'trade_amount');
                $trade_amount = (float)$invest['trade_amount'] + (float)$sum['total_intrest'];
                $update = $this->update("investment", ["trade_amount" => $trade_amount], "ID  = '$investID'");
                if (!$update) {
                    continue;
                }
                // Debit fund from trading_balance 
                // $update = $this->credit_debit($sum['userID'], $sum['total_intrest'], "trading_balance", 'debit', for: "compound_profits");
                // UPDATE DATE FOR THE compound_profits
                
                    echo "Applied for investID: ".$investID;
                    $id = $row['compound_profits_assignedID'];
                    $this->update("compound_profits_assigned", ["last_date" => $date, "last_time" => $time], "ID = '$id'");
                    $actInfo = ["userID" => $row['userID'],  "date_time" => date("Y-m-d H:i:s"),"action_name" => "compound_profits Applied", "description" => "Weekly compound_profits applied on your investment with the ID: ".$row['investmentID'], "action_for"=>"compound_profits_assigned", "action_for_ID"=>$row['ID']];
                    $this->new_activity($actInfo);
                
                // $total_trades = $this->getall("trades", "");
                // $trades = $this->get_all_trades_btw_dates($lastMonday, $lastSaturday, $row['investmentID']);
            }
            }
        }
    }

    function apply_daily_compound_profits()
    {
        $today =  date("Y-m-d");
        $yesterday = date("Y-m-d", strtotime("-1 day"));
        $time = date("H:i:s");
        $compound_profits = $this->getall("
        compound_profits_assigned 
        JOIN compound_profits ON compound_profits_assigned.compound_profits = compound_profits.ID AND compound_profits.assigned_users LIKE CONCAT('%', compound_profits_assigned.userID, '%')
        ", "compound_profits_assigned.last_date <= ? and compound_profits_assigned.status = ? and compound_profits.type = ?", [$today, 'active', 'daily'], "compound_profits_assigned.ID as compound_profits_assignedID, compound_profits_assigned.investmentID as investmentID, compound_profits_assigned.userID as userID", fetch: "moredetails");
        if ($compound_profits->rowCount() == 0) {
            return true;
        }
        foreach ($compound_profits as $row) {
            $invests = $this->getall("investment", "userID = ?", [$row['userID']], fetch: "moredetails");
            if ($invests->rowCount() == 0) {
                continue;
            }
            foreach ($invests as $ins) {
                $row['investmentID'] = $ins['ID'];
                $sum = $this->getall("trades", "investmentID = ? and intrest_amount > ? and trade_date = ?", [$row['investmentID'],  0, $yesterday], "userID as userID, SUM(intrest_amount) as total_intrest");
                if (!is_array($sum) || $sum['total_intrest'] < 1) {
                    continue;
                }
                $investID = $row['investmentID'];
                $invest = $this->getall("investment", 'ID = ?', [$investID], 'trade_amount');
                $trade_amount = (float)$invest['trade_amount'] + (float)$sum['total_intrest'];
                $update = $this->update("investment", ["trade_amount" => $trade_amount], "ID  = '$investID'");
                if (!$update) {
                    continue;
                }
                // Debit fund from trading_balance 
                // $update = $this->credit_debit($sum['userID'], $sum['total_intrest'], "trading_balance", 'debit', for: "compound_profits");
                // UPDATE DATE FOR THE compound_profits
                if ($update) {
                    $id = $row['compound_profits_assignedID'];
                    $this->update("compound_profits_assigned", ["last_date" => $today, "last_time" => $time], "ID = '$id'");
                    $actInfo = ["userID" => $row['userID'],  "date_time" => date("Y-m-d H:i:s"), "action_name" => "compound_profits Applied", "description" => "Daily compound_profits applied on your investment with the ID: " . $row['investmentID'], "action_for" => "compound_profits_assigned", "action_for_ID" => $row['compound_profits_assignedID']];
                    $this->new_activity($actInfo);
                }
            }

            // $total_trades = $this->getall("trades", "");
            // $trades = $this->get_all_trades_btw_dates($lastMonday, $lastSaturday, $row['investmentID']);
        }
    }
    private function get_all_sum_trades_btw_dates($start, $end, $investID = "all")
    {
    }
    function get_user_roll_over($userID)
    {
        $data = $this->getall("compound_profits", "assigned_users LIKE '%$userID%' and status = ?", ["active"], fetch: "moredetails");
        if ($data->rowCount() == 0) {
            return [];
        }
        $info = [];
        foreach ($data as $row) {
            $place = "";
            // check if the compound is active or amount is less than the active one
            $compound_a = $this->getall("compound_profits_assigned", "userID = ? and status = ?", [$userID, "active"]);
            if(is_array($compound_a)){
                $compound = $this->getall("compound_profits", "ID = ?", [$compound_a['compound_profits']]);
                if($compound_a['compound_profits'] == $row['ID'] || $compound['purchase_price'] > $row['purchase_price']){
                    continue;
                }
                $row['purchase_price'] =   $row['purchase_price'] - $compound['purchase_price'];
                $place = "Upgrade to: ";
            }
            $info[$row['ID']] = $place.ucfirst($row['type']) . ' Compound Profits - Purchase Price: ' . $this->money_format($row['purchase_price'], currency) . ' <b class="text-success">(Bonus of: ' . $this->money_format($row['bonus_price'], currency) . ")</b>";
            
        }
        return $info;
    }
    function get_user_promo($userID)
    {
        $data = $this->getall("promo", "assigned_users LIKE '%$userID%' and status = ?", ["active"], fetch: "moredetails");
        if ($data->rowCount() == 0) {
            return [];
        }
        $info = [];
        foreach ($data as $row) {
            $info[$row['ID']] = ucfirst($row['rate']) . 'X profit for '.$row['number_of_days'].'day(s) Promo - Purchase Price: ' . $this->money_format($row['purchase_price'], currency);
        }
        return $info;
    }

    private function check_if_user_in_compound_profits($rollID, $userID)
    {
        $check = $this->getall("compound_profits", "ID = ? and assigned_users LIKE '%$userID%' and status = ?", [$rollID, "active"], fetch: "");
        if ($check > 0) {
            return true;
        }
        return false;
    }
    function invest_data($userID)
    {
        $info = [];
        $data = $this->getall("investment", "userID = ?", [$userID], "SUM(amount) as total_amount");
        $info['invest_amount'] = $data['total_amount'];
        $data = $this->getall("investment", "userID = ? and status =? ", [$userID, "active"], "COUNT(*) as active_invest");
        $info['active_invest'] = $data['active_invest'];


        // $info['invest_num'] = $invest->rowCount();
        // $info['invest_amount'] = 0;
        // $info['invest_active'] = 0;
        // $info['invest_close'] = 0;
        // foreach($invest as $row) {
        //     $info['invest_amount'] = $info['invest_amount'] + (float)$row['amount'];
        //     if(isset($info['invest_'.$row['status']])){
        //         $info['invest_'.$row['status']] = $info['invest_'.$row['status']] + 1;
        //     }

        // }
    }
    function extract_chart_data($data, $date = null)
    {
        if ($date == null && isset($data[0])) {
            $holder = json_decode($data[0]['trade_candles']);
            // echo "yes";
            // var_dump($holder[0][0]);
            $chart_info = [[$holder[0][0], 0]];
        } else {
            $chart_info = [[$date, 0]];
        }

        // if($data->rowCount() == 0 && !is_array($data)) { return $chart_info;}
        // $i = 0;

        foreach ($data as $row) {
            $candles = json_decode($row['trade_candles']);
            $no =  count($candles) - 1;
            $time = $candles[0][0];
            $interest = $row['intrest_amount'];
            $chart_info[] = [$time, $interest];

            // var_dump(json_encode($chart_info));
            // echo $row['ID']."<br>";
            // echo date("Y-m-d H:i:s", '1633417200000' / 1000)."<br>";
            // echo date("Y-m-d H:i:s", '1693597137');
            // echo "<hr>";
        }
        return $chart_info;
    }
    function check_plan($planID, $amount)
    {
        $plan = $this->getall("plans", "ID = ?", [$planID]);
        // $this->message("This plan is no more active", "error");
        if (!is_array($plan)) {
            return false;
        }
        if ($plan['status'] != "active") {
            $this->message("This plan is no more active", "error");
            return false;
        }
        if ((float)$plan['min_amount'] > (float)$amount) {
            $this->message("The minimum amount for this plan is " . $this->money_format($plan['min_amount']), "error");
            return false;
        }
        if ($plan['max_amount'] != 0 &&  (float)$amount > (float)$plan['max_amount']) {
            $this->message("The maximum amount for this plan is " . $this->money_format($plan['max_amount']), "error");
            return false;
        }
        return true;
    }

    function credit_bot() {

    }
    function invetment_bot(array $investment_form,  $limit = 0)
    {
        $i = 0;
        $more = "";
        if($limit > 0) {
            $more = "LIMIT $limit";
        }
        $bots = $this->getall("users", "acct_type = ?  and status = ? $more", ['bot', 'active'], fetch: "moredetails");
        if($bots->rowCount() < 1) { return false; }
        foreach($bots as $bot) {
            $passed = true;
            // select a radom plan 
            $plan = $this->getall("plans", "status =  ? ORDER BY RAND()", ["active"]);
            if (!is_array($plan)) { continue; }
            // gererate rand no btw plan min and max 
            $amount = rand($plan['min_amount'], $plan['max_amount']);
            // check if trading balance match up with the no
            if($this->user_data($bot['ID'])['trading_balance'] < $amount) {
                // if not add the no to the current trading balance
                if(!$this->credit_debit($bot['ID'], $amount, "trading_balance", for:"Trading Bot Balance Credit")){
                    $passed = false;
                } 
            }
            if($passed == false) {
                continue;
            }
            // now create the investment.
            $investment_form['date'] = [];
            $_POST['planID'] = $plan['ID'];
            $_POST['userID'] = $bot['ID'];
            $_POST['amount'] = $amount;
            $_POST['date'] = $this->generateRandomDateTime();
            if($this->new_investment($investment_form)){
                $i++;
            }
        }
        $this->message("Investment created for $i", "success");
    }


    function change_trade_date() {
        $trades = $this->getall("trades", "done_date = ?", [0], "ID, trade_date, done_date", "moredetails");
        if($trades->rowCount() < 1) { return null;  }
        foreach($trades as $trade) {
            $date = date("Y-m-d", strtotime($trade['trade_date']));
            $id = $trade['ID'];
            $this->update("trades", ["trade_date"=>$date, "done_date"=>1], "ID = '$id'");
        }
    }

    function generate_trade_per_day() {
        $last_date = $this->get_settings("last_date");
        // $today = date("Y-m-d");
        if($last_date <= "2023-01-01") { return true; }
        $rand_no = rand(250, 350);
        $check = $this->getall("trades", 'trade_date = ?', [$last_date], fetch: "");
        if($check >=  $rand_no) { 
            $update = $this->update("settings", ["meta_value"=>date('Y-m-d', strtotime($last_date.' -1 day'))], "meta_name = 'last_date'");
            if($update) {
                return $this->generate_trade_per_day();
            }
        }
        $generate_trades = $this->auto_genarate_trading_days("bot", (int)$rand_no - (int)$check, $last_date);
        if($generate_trades >= (int)$rand_no - (int)$check) {
            $update = $this->update("settings", ["meta_value"=>date('Y-m-d', strtotime($last_date.' -1 day'))], "meta_name = 'last_date'");
        }
    }
 

    function auto_genarate_trading_days($type = null, $no = null, $trade_date = null)
    {

        $today = date("Y-m-d");
        
        // get all active investments  
        $plans = $this->get_plan("active", $type);
        if ($plans->rowCount() == 0) {
            return true;
        }
        $date = 'today';
        $no_trade_gen = 0;
        // insert into database as pending trade
        foreach ($plans as $row) {
            if($type == "bot") {
                if($trade_date == null) {
                    $today = $this->generateRandomDateTime();
                    $date = $today;
                }else{
                    $date = $trade_date;
                }
                
            }else{
                $type = "user";
            }
            // check if investment is not in trades where date is equals today
            $check = $this->getall("trades", "investmentID = ? and trade_date = ?", [$row['ID'], $today], fetch: "");
            // var_dump($check);
            if ($check > 5) {
                continue;
            }
            $times = $this->get_times($date);
            foreach ($times as $key => $value) {
                if($no != null) { if($no_trade_gen >= $no) { return $no_trade_gen; } }
                $this->quick_insert("trades", ["investmentID" => $row['ID'], "userID" => $row['userID'], "trade_date" => date("Y-m-d", $value), "trade_time" => $value, "trade_for"=>$type]);
                $no_trade_gen++;
            }
        }

        $this->message("$no_trade_gen generated.", "success");
        return $no_trade_gen;
        // $form = [
        //     "ID"=>["input_type"=>"number"],
        //     "investmentID"=>[],
        //     "amount"=>["input_type"=>"number"],
        //     "trade_date"=>[],
        //     "trade_time"=>[],
        //     "status"=>[],
        // ];
        // $this->create_table("trades", $form);

    }

    function get_plan($status, $type = "normal")
    {
        if($type == "bot") {
            return $this->getall("investment as i JOIN users as u ON i.userID = u.ID", "i.status = ? and u.acct_type = ? order by RAND() LIMIT 20", [$status, "bot"], "i.*", fetch: "moredetails");
        }else{
            return $this->getall("investment as i JOIN users as u ON i.userID = u.ID", "i.status = ? and u.acct_type = ? order by date ASC", [$status, "user"], "i.*", fetch: "moredetails");
            // return $this->getall("investment", "status = ? order by date ASC", [$status], fetch: "moredetails");
        }
    }

    function compounded_profit($userID) {
        $invest = $this->getall("investment", "userID = ? and trade_amount > amount", [$userID], "sum(amount) as amount, sum(trade_amount) as trade_amount");
        if(!is_array($invest)){ return $this->money_format(0, currency); }
        // var_dump($invest);
        return $this->money_format((float)$invest['trade_amount'] - (float)$invest['amount'], currency);
    }
    function take_pending_trades($no = 25, $type = "user", $order = "ASC")
    {
        $coins = $this->get_settings("trade_coins");
        $coins = explode(",", $coins);
        // var_dump($coins[array_rand($coins)]."USDT");
        // get all pending plans where date less or equal today
        $today = date("Y-m-d");
        //$trades = $this->getall("trades", 'status = ? order by trade_time ASC LIMIT 50', ["pending"], fetch: "moredetails");
        $trades = $this->getall("trades", 'trade_date <= ? and trade_time <= ? and status = ? and trade_for = ? GROUP BY investmentID order by trade_time '.$order.' LIMIT '.$no, [$today, time(), "pending", $type], fetch: "moredetails");
        // $trades = $this->getall("trades", 'trade_candles = ? or trade_candles = ?', ["", null], fetch: "moredetails");
        // var_dump($trades->rowCount());
        if ($trades->rowCount() == 0) {
            return true;
        }
        $totals = [];
        $limitvalue = rand(10, 30);
        $inters = ["1m", "5m", "15m", "30m", "1h"];
        $interval = $inters[array_rand($inters)];
        if($interval == "45m" || $interval == "1h" || $interval == "30m") {
            $limitvalue = rand(2, 4);
        }
        $coins = $this->get_settings("trade_coins");
        $coins = explode(",", $coins);
        $i = 0;
        foreach ($trades as $row) {
            $id = $row['ID'];
            if (!isset($totals[$row['investmentID']])) {
                $totals[$row['investmentID']] = [];
            }
            if (isset($totals[$row['investmentID']][$row['trade_date']]) && $totals[$row['investmentID']][$row['trade_date']] == "closed") {

                continue;
            }
            $startTimestamp = (int)$row["trade_time"] * 1000;
            // echo "<br>";
            $coin = trim($coins[array_rand($coins)])."USDT";
            // $data https://api-testnet.bybit.com/v5/market/kline?category=inverse&symbol=$coin&interval=$interval&start=$startTimestamp&limit=$limitvalue
            $data = $this->api_call("https://api.binance.us/api/v3/klines?symbol=$coin&interval=$interval&limit=$limitvalue&startTime=$startTimestamp");
            // $data = $this->api_call("https://api-testnet.bybit.com/v5/market/kline?category=inverse&symbol=$coin&interval=$interval&start=$startTimestamp&limit=$limitvalue");
            if (!is_array($data) || count($data) < $limitvalue) {
                // var_dump($data);
                if($data->msg) {
                    echo $data->msg;
                }
                // var_dump($data);
                // echo "https://api.binance.com/api/v2/klines?symbol=$coin&interval=$interval&limit=$limitvalue&startTime=$startTimestamp";
                echo $i++." ID: ".$row['ID']." error Date: ".$row['trade_date'];
                // echo "https://api-testnet.bybit.com/v5/market/kline?category=inverse&symbol=$coin&interval=$interval&start=$startTimestamp&limit=$limitvalue";
                 echo "<br>";
                continue;
            }
            // echo var_dump($this->get_times());
            // echo "https://api.binance.com/api/v3/klines?symbol=$coin&interval=$interval&limit=$limitvalue&startTime=$startTimestamp";
            // var_dump($data);
            // return;
            $info = $this->cal_trade_percent($data, $row);
            if (!is_array($info)) {
                continue;
            }
            $maxProfit = $this->get_investment_max_profit($row['investmentID']);
            $minProfit = $this->get_investment_max_profit($row['investmentID'], "min");
            $maxProfit = ($maxProfit + $minProfit) / 2;
            if($info['percentage'] > $maxProfit)  {
                continue;
            }
            $info['coinname'] = $coin;
            $info['status'] = "closed";
            if (!isset($totals[$row['investmentID']][$row['trade_date']])) {
                $totals[$row['investmentID']][$row['trade_date']] = $this->total_daily_profit($row['investmentID'], $row['trade_date']);
            }
            
            $currentPercent = $this->total_daily_profit($row['investmentID'], $row['trade_date']);
            var_dump("CP: ".$currentPercent);
            var_dump("MP: ".$maxProfit );
            if ($currentPercent >= $maxProfit || $totals[$row['investmentID']][$row['trade_date']] >= $maxProfit) {
                $this->close_all_pending_trades($row['investmentID'], $row['trade_date']);
                $totals[$row['investmentID']][$row['trade_date']] =  "closed";
                echo "Trade Closed";
                continue;
            }
            $totals[$row['investmentID']][$row['trade_date']] = $totals[$row['investmentID']][$row['trade_date']] + $info['intrest_amount'];
            // var_dump($info);
            $update = $this->update("trades",  $info, "ID = '$id'",);
            if ($update) {
                $date = $this->date_format(date("Y-m-d H:i:s", (int)($row['trade_time']  / 1000)));
                // $actInfo = ["userID" => $row['userID'],  "date_time" => date("Y-m-d H:i:s"),"action_name" => "New trade taken", "description" => "A new trade was taken on your investment with an intrest of ".$info['percentage'], "action_for"=>"trades", "action_for_ID"=>$id];
                // $this->new_activity($actInfo);
                $update = $this->credit_debit($row['userID'], $info['intrest_amount'], "trading_balance", for: "trades", forID: $id);
                echo "Success: for ".$row['ID']." <br>";
            }
            // echo "success: ".$row['investmentID']." <br>";
            // foreach ($data as $row) {
            // echo   $row[0];
            // $start_time = date("Y-m-d H:i:s", $row[0] / 1000);
            // $end_time = date("H:i:s", $row[6] / 1000);
            // echo 'Start Time: ' . $start_time . "<br>";
            // echo 'End Time: ' . $end_time . "<hr>";
            // }

        }
        // var_dump($totals);


        // $start = '2022-08-31 12:00:00';
        // $startTimestamp = strtotime($start) * 1000;
        // $data = $this->api_call("https://api.binance.com/api/v3/klines?symbol=ETHUSDT&interval=1d&limit=20&startTime=$startTimestamp");
        // $this->cal_trade_percent($data, 19);
        // var_dump($data);
        // echo "<br>";
        // foreach($data as $row) {
        //     // echo   $row[0];
        //     $start_time = date("Y-m-d H:i:s", $row[0] / 1000);
        //     $end_time = date("H:i:s", $row[6] / 1000);
        //     echo 'Start Time: '.$start_time ."<br>";
        //     echo 'End Time: '.$end_time ."<hr>";

        // }
        echo $this->message("done", "success");
    }

    function get_all_trades()
    {
    }
    function close_all_pending_trades($investID, $date)
    {
        $update = $this->delete("trades", "investmentID = ? and trade_date = ? and status = ? ", [$investID, $date, "pending"]);
        if (!$update) {
            return false;
        }
        return true;
    }
    function get_investment_max_profit($investID, $type = "max")
    {
        $invest = $this->getall("investment", "ID = ?", [$investID], "planID");
        if (!is_array($invest)) {
            return 0;
        }
        $plan = $this->getall("plans", "ID = ?", [$invest['planID']], "return_range_from, return_range_to, retrun_interval");
        if (!is_array($invest)) {
            return 0;
        }
        if($type == "max") {
            return  (float)$plan['return_range_to'];
        }else{
            return  (float)$plan['return_range_from'];
        }
    }

    function total_daily_profit($investID, $date)
    {
        $trades = $this->getall("trades", 'investmentID = ? and trade_date = ? and status = ?', [$investID, $date, 'closed'], "SUM(percentage) as percentage", fetch: "details");
        if (!is_array($trades)) {
            return 0;
        }
        $total = $trades['percentage'];
        return $total;
    }

   

    function total_profit($investID, $date = "all", $return_type = "all")
    {
        $info = ["total" => 0, "percentage" => 0];
        if ($date == "all") {
            $trades = $this->getall("trades", 'investmentID = ? and status = ?', [$investID, "closed"], "intrest_amount, percentage", fetch: "moredetails");
        } else {
            $trades = $this->getall("trades", 'investmentID = ? and trade_date = ? and status = ?', [$investID, $date, "closed"], "intrest_amount, percentage", fetch: "moredetails");
        }
        if ($trades->rowCount() == 0) {
            return $info;
        }
        foreach ($trades as $row) {
            $info['total'] = $info['total'] + (float)$row['intrest_amount'];
            $info['percentage'] = $info['percentage'] + $row['percentage'];
        }
        if ($return_type == "all") {
            return $info;
        }
        if (isset($info[$return_type])) {
            return $info[$return_type];
        }
        return 0;
    }

    function cal_trade_percent($data, $trade)
    {
        $x = 1;
        $open_price = $data[0][1];
        $close_price = $data[count($data) - 1][1];
        // echo $this->calculateProfitPercentage($open_price, $close_price) . "% <br>";
        // echo "$open_price" . "<br>";
        // echo "$close_price" . "<br>";
        $n1 = $open_price;
        $n2 = $close_price;
        $trade_type = "buy";
        $percentage = (float)$this->calculateProfitPercentage($n1, $n2);
        $Xpromo = $this->get_X_promo($trade['investmentID']);
        if ($percentage < 0 && $percentage > (-0.3)) {
            if ($this->no_of_lost($trade['investmentID'], $trade['trade_date']) > 1) {
                return null;
            }
            $x = 1;
        } else if ($open_price > $close_price) {
            $trade_type = "sell";
            $n1 = $close_price;
            $n2 = $open_price;
            $percentage = (float)$this->calculateProfitPercentage($n1, $n2);
        } else if ((int)$percentage < 0) {
            return ["intrest_amount" => 0, "trade_candles" => null, "percentage" => 0, "trade_type" => null];
            // return null;
        }
        if ($percentage > 0 && $percentage < 2) {
            $percentage = $percentage + $Xpromo;
            if ($percentage < 1) {
                $x = rand(5, 20);
            } else {
                $x = rand(2, 10);
            }
        }
        $percentage = $percentage * $x;
        $trade_amount = $this->get_invest_trade_amount($trade['investmentID']);
        $amount = $this->calculateIncreasedValue($trade_amount, $percentage);
        return ["amount" => $trade_amount, "intrest_amount" => round($amount, 3), "trade_candles" => json_encode($data), "percentage" => $percentage, "trade_type" => $trade_type, "Xtrade" => $x, "Xpromo"=>$Xpromo];
    }

    function apply_pending_promo(){
        $datas = $this->getall("promo_assigned", "start_date <= ? and end_date >= ? and status = ?", [time(), time(), "active"], fetch: "moredetails");
        if($datas->rowCount() == 0) { return 0; }
        // echo time();
        // var_dump($datas->rowCount());
        foreach($datas as $data){
            // echo "Start: ".$data['start_date'];
            // echo "End: ".$data['end_date'];
            // echo "userID: ".$data['userID'];
            // echo "<hr>";
            $promo = $this->getall("promo", "ID = ? and status = ?", [$data['promoID'], "active"]);
            if(!is_array($promo)) { continue; }
            $rate = (int)$promo['rate'];
            // $trades = $this->getall("trades", "userID = ? and trade_time >= ? and trade_time <= ? and Xpromo = ? and status = ?", 
            // [$data['userID'], $data['start_date'], $data['end_date'], 0, "closed"], fetch: "moredetails");
            // var_dump($trades->rowCount());
            // intrest_amount = intrest_amount * $rate 
            // $query = $this->db->prepare("UPDATE trades SET  `percentage` = `percentage` * $rate,  Xtrade = Xtrade + $rate 
            // WHERE userID = '".$data['userID']."' and trade_time >= ".$data['start_date']." and trade_time <= ".$data['end_date']." and Xpromo > 0 and status = 'closed'");
            // $query->execute([]);
        }

        
    }
    // get all active promo 
    // get all trades within the range for the userID
    // loop throgh them and apply the promo on the profit.


    function get_invest_trade_amount($investID)
    {
        $check = $this->getall("investment", "ID = ?", [$investID], "trade_amount");
        if (!is_array($check)) {
            return 0;
        }
        return (float)$check['trade_amount'];
    }
    function no_of_lost($id, $date)
    {
        $check = $this->getall("trades", "investmentID = ? and date = ? and percentage < ?", [$id, $date, 0], fetch: "");
        return $check;
    }
    function get_times($date = 'today')
    {
        $no = rand(10, 30);
        // genrate roundam time today in x time, time should be btw one hour away and the rest of the day
        // $now = time() + 900;
        $now = strtotime($date);
        //  $now = strtotime("-2 day");
        $random_time = [];
        $endOfDay = strtotime($date . ' +1 day');
        // if($now == strtotime('today')){
        //     $endOfDay = time();
        // }
        for ($i = 0; $i < $no; $i++) {
            $random_time[] = rand($now, $endOfDay);
        }
        // var_dump($random_time);
        return $random_time;
    }
}