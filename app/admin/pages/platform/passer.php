<?php 
    if(isset($_POST['new_platform'])) {
        $_POST['ID'] = uniqid();
        echo $p->manage_platform($platform_form);
    }
    if(isset($_POST['edit_platform'])) {
        $platform_form['name']['unique'] = "ID";
        $platform_form['icon']['is_required'] = false;
        echo $p->manage_platform($platform_form, "update");
    }

    if(isset($_POST['delete_platform'])) {
        echo $p->delete_platform(htmlspecialchars($_POST['ID']));
    }
    if(isset($_POST['what']) && $_POST['what'] == "get") {
        $start = htmlspecialchars($_POST['start']);
        $limit = htmlspecialchars($_POST['limit']);
       try {
        $data = $p->get_platforms($start, $limit);
        if($data->rowCount() > 0 ) {
            $body = "";
            foreach ($data as $value) {
                $body .= $p->display_platform($value);
            }
            $return = ["status"=>"ok", "data"=>$body];
            echo json_encode($return);
           return ;
        }
        $return = ["status"=>"null", "data"=>""];
        echo json_encode($return);
        return ;
       } catch (\Throwable $th) {
        $return = ["status"=>"null", "data"=>""];
        echo json_encode($return);
        return ;
        //throw $th;
       }
        
    }
?>