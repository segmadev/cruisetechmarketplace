<?php 
    class Faq extends database {
        function add_faq($data) {
            $info = $this->validate_form($data, "faq");
            if(!is_array($info)) {
                return null;
            }
            if($this->quick_insert("faq", $info)){
               return $this->message("FAQ Added successfully", "success", "json");
            }
        }
    }
?>