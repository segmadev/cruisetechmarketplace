<?php
if (isset($_GET['get']) && $_GET['get'] == "orders") {
    $start = htmlspecialchars($_POST['start']);
    $limit = htmlspecialchars($_POST['limit']);
    try {
        $data = $a->getOrders($start, $limit);
        $body = "";
        if ($data->rowCount() > 0) {
            foreach ($data as $value) {
                $body .= $a->displayOrder($value);
            }
            $return = ["status" => "ok", "data" => "$body"];
            echo json_encode($return);
            return;
        }
        $return = ["status" => "null", "data" => ""];
        echo json_encode($return);
        return;
    } catch (\Throwable $th) {
        $return = ["status" => "null", "data" => ""];
        echo json_encode($return);
    }
}