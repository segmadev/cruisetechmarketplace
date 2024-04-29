<div class="card">
<?php 
        if($user['kyc_status'] == "Rejected") {
            echo "<div class='card bg-light-danger'><div class='card-body p-0 m-0 p-2'><p class='text-danger'>Your last KYC verification was rejected</p></div></div>";
        }
    ?>
    
    <div class="card-header">
        <h3 class="title">KYC Verification</h3>
        <p>Upload any government issued Identification Card (ID).</p>
        <?= $c->badge($user['kyc_status']) ?>
        <!-- <small></small> -->
    </div>
    <div class="card-body">
    <?php if($user['kyc_status'] == "approved") { 
            echo "<div class='card bg-light-success'><div class='card-body p-0 m-0 p-2'><p class='text-success'>KYC Verified</p></div></div>";
                
            }else{ ?>
        <form action="" id="foo">
            <?= $c->create_form($kyc_form) ?>
           
            <input type="hidden" name="page" value="profile">
            <input type="hidden" name="upload_kyc">
            <div id="custommessage"></div>
            <button type="submit" class="btn btn-primary">Upload KYC</button>
        </form>
        <?php } ?>
    </div>
</div>