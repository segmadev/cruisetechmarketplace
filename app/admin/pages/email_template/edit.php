<?php require_once "../content/textarea.php"; ?>
<div class="card col-12">
    <div class="border-bottom title-part-padding">
        <h4 class="card-title mb-0">Edit Template</h4>
        <p><?= $template['name'] ?></p>
    </div>
    <div class="card-body">
    <form class="mt-4" action="" id="foo"  novalidate="">
        <div class="row">
            <?php 
            echo $c->create_form($email_template_from); ?>
            <input type="hidden" name="edit_email_template" value="" id="">
            <input type="hidden" name="page" value="email_template" id="">
        </div>
        <div id="custommessage"></div>
        <input type="submit" value="Submit" class="btn btn-primary col-3">
    </form>
    </div>
</div>