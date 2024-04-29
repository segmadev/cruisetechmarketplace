
<?php 
    $script[] = "modal"; 
    $script = array_diff($script, ["chat"]);

?>
<div class="card">
    <div class="card-header">
        <h5>More Message Options.</h5>
    </div>
    <form action="chat-passer" id="foo" onsubmit="return void(0);" class="card-body">
        <?php
        $message = $d->getall("message", "ID = ?", [htmlspecialchars($_GET['messageID'] ?? "")]);
        if(!is_array($message)) { echo "Message not found.".htmlspecialchars($_GET['messageID'] ?? ""); return null; }
        $message_form = ["message"=>["type"=>"textarea", ], 
        "time_sent"=>['type'=>'input','input_type' => "datetime-local", "is_required" => true], 
        "input_data"=>["message"=>$message['message'], "time_sent"=>date("Y-m-d H:m:s", $message['time_sent'])]
    ];
        echo $c->create_form($message_form);
        ?>
        <div id="custommessage"></div>
        <input type="hidden" name="custom" value="">
        <input type="hidden" name="page" value="chat">
        <input type="hidden" name="edit_message" value="<?= htmlspecialchars($_GET['messageID'] ?? ""); ?>">
        <button type="submit" class="btn btn-primary">Edit Message</button>
    </form>
</div>