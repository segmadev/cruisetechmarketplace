
<?php 
    $script[] = "modal"; 
    $script = array_diff($script, ["chat"]);

?>
<div class="card">
    <div class="card-header">
        <h5>More Message Options</h5>
    </div>
    <form action="chat-passer?imageandvideo=" id="foo" onsubmit="return void(0);" class="card-body">
        <?php
        if(isset($_GET['reply_to']) && !empty($_GET['reply_to'])) {
            $message_form['reply_to'] = ["is_required"=>false, "input_type"=>"hidden"];
            $message_form['input_data']['reply_to'] = htmlspecialchars($_GET['reply_to']);
            if(isset($_GET['message'])) {
                echo "<p class='bg-light-success p-3'><b class='text-success'>Reply to:</b> ".$d->short_text($_GET['message'], 100)."<p>";
            }
        }
        $message_form['message']["type"] = "textarea";
        // $message_form['upload']["formart"] = ["png", "jpeg", "jpg", "mov", "mp4"];
        $message_form['message']["title"] = "Message";
        $message_form['message']["class"] = "form-control";
        $message_form['message']["global_class"] = "w-100";
        $message_form["time_sent"] = ['type'=>'input','input_type' => "datetime-local", "is_required" => false, "description" => '<small class="text-danger">You can leave it empty if you want to use current time. Only administrators can view a future message, but it will be automatically sent to users when the specified date arrives.</small>'];
        $message_form['senderID']['type'] = 'select';
        
        $users = [];
        // var_dump($message_form['input_data']['receiverID']);
        if (isset($message_form['input_data']['receiverID'])) {
            $users_group = $ch->get_group_users_list($message_form['input_data']['receiverID'], 1, 100000, "first_name ASC");
            if ($users_group->rowCount() > 0) {
                foreach ($users_group as $row) {
                    $no_messages = $d->getall("message", "senderID = ? and receiverID = ?", [$row['user1'], $message_form['input_data']['receiverID']], fetch: "");
                    $users[$row['user1']] = $ch->get_name($row['user1'], type: true)." - $no_messages Messages";
                }
                $message_form['senderID']['options'] = $users;
            }
        }
        echo $c->create_form($message_form);
        ?>
        <div id="custommessage"></div>
        <input type="hidden" name="custom" value="">
        <input type="hidden" name="page" value="chat">
        <input type="hidden" name="send_message" value="">
        <button type="submit" class="btn btn-primary">Send Message</button>
    </form>
</div>