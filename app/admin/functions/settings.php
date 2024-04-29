<?php 
    class settings extends content {
    private $accepted_tables = ["key_features",  "how_it_works", "testimonies"];
        function new_settings($data, $what = "settings") {
            if($data  == ""){ return null; }
            foreach($data as $key => $row) {
                if($key == "input_data") { continue; }
                if($this->getall("$what", "meta_name = ?", [$key], fetch: "") > 0) { continue; }
                $value = "";
                if(isset($data['input_data'][$key])) {
                    $value = $data['input_data'][$key];
                }
                $this->quick_insert("$what", ["meta_name"=>$key, "meta_value"=>$value]);
            }
        }

        function edit_admin($adminID, $admin_account) {
            if(!$this->validate_admin()) return false;
            $info = $this->validate_form($admin_account);
            if(!is_array($info)) return false;
            $admin = $this->getall("admins", "ID = ?", [$adminID]);
            if(!is_array($admin)) return false;
            if(!password_verify($info['current_password'], $admin['password'])) {
                $this->message("Current password is wrong", "error");
                return false;
            }
            $update_info = ["email"=>$info['email']];
            if($info['password'] != "" && $_POST['confirm_password'] != $info['password']) {
                $this->message("Passwords do not match", "error");
                return false;
            }
            if($info['password'] != "") $update_info['password'] = password_hash($info['password'], PASSWORD_DEFAULT);
            $this->update("admins", $update_info, "ID = '$adminID'", "Account Updated");
        }
        function update_settings($data, $what =  "settings") {
            if($data  == ""){ return null; }
            $info = $this->validate_form($data);
            // var_dump($info);
            if(!is_array($info) || $info == null || $info == false) { return null;}
            foreach ($info as $key => $value) {
                
                if($this->getall("$what", "meta_name = ?", [$key], fetch: "") == 0) { continue; }
                $update = $this->update("$what", ["meta_value"=>$value], "meta_name = '$key'");
            }
            $return = [
                "message" => ["Success", "$what Updated", "success"],
            ];
            return json_encode($return);  
        }

        function getdata($data, $what = "settings") {
            if($data == ""){ return null; }
            $info = [];
            foreach($data as $key => $row) {
                if($key == "input_data") { continue; }
                $datad = $this->getall("$what", "meta_name = ?", [$key], fetch: "details");
                if(!is_array($datad)) { continue; }
                $info[$key] = $datad['meta_value'];
                //  $this->quick_insert("settings", ["meta_name"=>$key, "meta_value"=>$value]);
            }
            return $info;
        }

        function new_details($data, $what = "key_features") {
            if(!in_array($what, $this->accepted_tables)){
                return null;
            }
            if(isset($data['ID'])) {
                unset($data['ID']);
            }
            $info = $this->validate_form($data, "$what");
            if(!is_array($info)) { return null; }
            $this->quick_insert("$what", $info, "New detail added.");
        }

        function edit_details($data, $what = "key_features") {
            if(!in_array($what, $this->accepted_tables)){
                return null;
            }
            $info = $this->validate_form($data, "$what");
            if(isset($info['image']) && $info['image'] == "") unset($info['image']);
            if(!is_array($info)) { return null; }
            $id = $info['ID'];
            unset($info['ID']);
            $this->update("$what", $info, "ID = '$id'", "Detail updated.");
        }

        function remove_details($id, $what = "key_features") {
            if(!in_array($what, $this->accepted_tables)){
                return null;
            }
            $delete = $this->delete("$what", "ID = ?", [$id]);
            $return = [
                "message" => ["Success", "one detail deleted", "success"],
                "function" => ["removediv", "data"=>[".detail-".$id, "success"]]
            ];
            return json_encode($return);
        }
    }