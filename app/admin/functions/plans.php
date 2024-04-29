<?php 
    class plans extends database {
        private  $plan;
        function new_plan($plans) {
            if($this->validate_plan($plans)) {
                if(isset($this->plan['input_data'])) { unset($this->plan['input_data']); }
                $this->quick_insert("plans", $this->plan, message: "Plan created successfully.");
            }
            // return true;
        }
        
        function new_compound_profits($data) {
            $info = $this->validate_form($data, "compound_profits");
            if(!is_array($info)) {return null; }
            if(empty($info['assigned_users'])) {
                $info['assigned_users'] = json_encode([]);
            }
            $insert = $this->quick_insert("compound_profits", $info, "compound_profits Created.");
        }

        function update_compound_profits($data) {
            $info = $this->validate_form($data, "compound_profits");
            if(!is_array($info)) {return null; }
            if(empty($info['assigned_users'])) {
                $info['assigned_users'] = json_encode([]);
            }
            $id = $info['ID'];
            unset($info['ID']);
            $update = $this->update("compound_profits", $info, "ID = '$id'", "Updated");
        }
        function new_promo($data) {
            $info = $this->validate_form($data, "promo");
            if(!is_array($info)) {return null; }
            if(empty($info['assigned_users'])) {
                $info['assigned_users'] = json_encode([]);
            }
            $insert = $this->quick_insert("promo", $info, "Promo Created.");
        }

        function update_promo($data) {
            $info = $this->validate_form($data, "promo");
            if(!is_array($info)) {return null; }
            if(empty($info['assigned_users'])) {
                $info['assigned_users'] = json_encode([]);
            }
            $id = $info['ID'];
            unset($info['ID']);
            $update = $this->update("promo", $info, "ID = '$id'", "Updated");
        }
        function validate_plan($data)
        {
            $this->plan = $this->validate_form($data);
            if(!is_array($this->plan)) { return false; }
            $this->plan['min_amount'] = (float)$this->plan['min_amount'];
            $this->plan['max_amount'] = (float)$this->plan['max_amount'];
            $check = $this->getall("plans", "ID != ? and min_amount = ? and max_amount = ?", [$this->plan['ID'], $this->plan['min_amount'], $this->plan['max_amount']], "ID");
            if(is_array($check)) {
                $this->message("A plan that has this min and max amount already exist. <a class='text-primary' href='plans?action=edit&id=".$check['ID']."'>Edit Plan</a>", "error");
                return false;
            }
            if(!$this->plan['plan_name'] == "") {
                $check = $this->getall("plans", "ID != ? and plan_name = ?", [$this->plan['ID'], $this->plan['plan_name']], "ID");
                if(is_array($check)) {
                    $this->message("A plan that has this name (".$this->plan['plan_name'].") already exist. <a class='text-primary' href='plans?action=edit&id=".$check['ID']."'>Edit Plan</a>", "error");
                    return false;
                }
            }
            return true;
        }
        function edit_plan($plans) {
            if($this->validate_plan($plans)) {
                $id = $this->plan["ID"];
                unset($this->plan['ID']);
                unset($this->plan['input_data']);
                $this->update("plans", $this->plan, "ID = '$id'", message: "Plan Updated");
            }
        }

        function getallplans() {
            return $this->getall("plans", fetch: "moredetails");
        }
    }