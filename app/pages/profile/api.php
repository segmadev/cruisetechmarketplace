<?php
$script[] = "sweetalert";
?>
<div class="card">
    <div class="card-header">
        <h3> <i class="ti ti-key"></i> Your API key</h3>
        <p>Note that you can only see your API Key onces. <br> But you can always generate a new key.</p>
    </div>
    <div class="card-body">
        <form action="" id="foo">
            <form id="foo">
                <input type="hidden" name="page" value="profile">
                <input type="hidden" name="generate_key">
                <input type="password" name="password" class="form-control mb-2" placeholder="Your Password">
                <input type="hidden" name="confirm" value="You are about to generate a new API Key.">
                <div id="custommessage"></div>
                <input type="submit" value="Generate API key" class="btn btn-primary">
            </form>
        </form>
    </div>
</div>