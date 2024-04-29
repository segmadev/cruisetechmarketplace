<?php 
 class referral extends users {
    function new_referral(array $data) {
        if(is_array($this->validate_form($data, "referral_programs", "insert"))){
            $this->message("Referral program added successfully", "success");
        }
    }

    function edit_referral(array $data) {
        $info = $this->validate_form($data, "referral_programs", "update");
        if(is_array($info)){
            return $this->message("Referral program updated successfully", "success");
            $id = $info['ID'];
            unset($info['ID']);
            $this->update("referral_programs", $info, "ID = '$id'", "Referral program updated successfully");
            // $this->message("", "success");
        }
    }
 }