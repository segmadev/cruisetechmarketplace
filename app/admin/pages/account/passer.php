<?php 
    if(isset($_POST['new_account'])) echo $a->manage_account($account_from);
    if(isset($_POST['upadate_account'])) echo $a->manage_account($account_from, "update");
    if(isset($_POST['delete_account'])) echo $a->delete_account(htmlspecialchars($_POST['ID'] ?? ""));
    if(isset($_POST['what']) && $_POST['what'] == "get") {
        $start = htmlspecialchars($_POST['start']);
        $limit = htmlspecialchars($_POST['limit']);
       try {
        $data = $a->fetch_account(start: $start, limit: $limit, status: "");
        if($data->rowCount() > 0 ) {
            $body = "";
            foreach ($data as $value) {
                $body .= $a->display_account($value);
            }
            echo $body;
        }
       } catch (\Throwable $th) {
        // var_dump($th);
        //throw $th;
       }
        
    }
?>