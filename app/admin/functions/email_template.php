<?php 
    class email_template extends content {
        function update_template () {
            $data = $this->checkmessage(["ID", "template"]);
            if(!is_array($data)){
                return ;
            }
            
            $where = "ID = '".$data['ID']."'";
            unset($data['ID']);
            $update = $this->update("email_template", $data, $where);
            if($update) {
                $return = [
                    "message" => ["Success", "Email Updated", "success"],
                ];
                return json_encode($return);
            }
            }
    }