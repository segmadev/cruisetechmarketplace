<?php 
    if(isset($_POST['buy']) && isset($_POST['ID']) && $_POST['ID'] != ""){
        $choices = json_decode($_POST['choices'], true)  ?? [];
        $accountID = htmlspecialchars($_POST['ID']);
        $qty = htmlspecialchars($_POST['qty']);
        echo $a->buy_account($userID, $accountID, $qty, $choices);
    }


if(isset($_POST['accountID']) && isset($_POST['limit']) && $_POST['accountID'] != ""){
    // Get parameters from AJAX request
    $accountID = isset($_POST['accountID']) ? $_POST['accountID'] : '';
    $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 20;
    $offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;
    $excludeIDs = isset($_POST['exclude']) ? $_POST['exclude'] : [];
    // Fetch logins
    $logins = $a->fetchLoginsByAccountID($accountID, $limit, $offset, $excludeIDs);
    header('Content-Type: application/json');
    echo json_encode(['logins' => $logins->fetchAll(PDO::FETCH_ASSOC)]);
}

if(isset($_POST['buynow'])) {

}






?>