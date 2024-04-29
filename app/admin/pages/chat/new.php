<?php if(isset($_GET['id'])){
    require_once "pages/chat/options.php";
   
}else if(isset($_GET['messageID'])){ 
    require_once ("pages/chat/edit.php");
 }else{
   $script = array_diff($script, ["chat"]);
    ?>
<div class="card">
    <div class="card-header">
        <h1 class="card-title">Generate Conversation.</h1>
    </div>
    <div class="card-body">
        <form id="foo" action="passer?place=convo" class="row">
            <?= $c->create_form($from_generate); ?>
            <input type="hidden" name="generate_conversation" value="">
            <input type="hidden" name="page" value="chat">
            <div id="custommessage"></div>
            <input type="submit" value="Generate conversation" class="btn btn-primary">
        </form>
    </div>
</div>

<?php } ?>