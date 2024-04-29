<?php require_once "../content/textarea.php"; 
?>
<!-- logo settings -->


<div class="card col-12">
    <div class="border-bottom title-part-padding">
        <h4 class="card-title mb-0">Home Header</h4>
    </div>
    <div class="card-body">
        <form class="mt-4" action="" id="foo" novalidate="">
            <div class="row">
                <?php
                echo $c->create_form($content_home_header); ?>
                <input type="hidden" name="updatecontent" value="" id="">
                <input type="hidden" name="page" value="content" id="">
                <input type="hidden" name="content" value="home_header">
            </div>
            <div id="custommessage"></div>
            <input type="submit" value="Submit" class="btn btn-primary col-3">
        </form>
    </div>
</div>