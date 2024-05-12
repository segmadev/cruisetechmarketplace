<?php 
    if(isset($_POST['new_category'])) {
        $_POST['ID'] = uniqid();
        echo $ca->manage_category($category_form);
    }
    if(isset($_POST['edit_category'])) {
        $category_form['name']['unique'] = "ID";
        echo $ca->manage_category($category_form, "update");
    }

    if(isset($_POST['delete_category'])) {
        echo $ca->delete_category(htmlspecialchars($_POST['ID']));
    }
    if(isset($_POST['what']) && $_POST['what'] == "get") {
        $start = htmlspecialchars($_POST['start']);
        $limit = htmlspecialchars($_POST['limit']);
       try {
        $data = $ca->get_categories($start, $limit);
        if($data->rowCount() > 0 ) {
            $body = "";
            foreach ($data as $value) {
                $body .= $ca->display_category($value);
            }
            $return = ["status"=>"ok", "data"=>"$body"];
            echo json_encode($return);
            return ;
        }
        $return = ["status"=>"null", "data"=>""];
        echo json_encode($return);
       } catch (\Throwable $th) {
        $return = ["status"=>"null", "data"=>""];
        echo json_encode($return);
        // print($th);
        //throw $th;
       }
        
    }
?>