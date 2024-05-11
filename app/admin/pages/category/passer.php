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
            echo $body;
        }
       } catch (\Throwable $th) {
        // print($th);
        //throw $th;
       }
        
    }
?>