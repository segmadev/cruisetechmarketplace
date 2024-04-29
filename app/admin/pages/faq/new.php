<?php  $script[] = "sweetalert" ?>
<div class="card">
    <div class="card-header">
        <h5>Add FAQ</h5>
        <p>This will display to your visitor on home.</p>
    </div>
    <div class="card-body">
        <form action="" id="foo">
           <div class="row">
           <?php 
            $script[] = "textarea";
            echo $c->create_form($faq_data); ?>
           </div>
           <input type="hidden" name="page" value="faq">
           <input type="hidden" name="new_faq" value="">
           <input type="hidden" name="confirm" value="Are you sure?">
           <div id="custommessage"></div>
        <input type="submit" value="Add FAQ" class="btn btn-primary">
        </form>
    </div>
</div>