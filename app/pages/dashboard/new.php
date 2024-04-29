<?php $script[] = "showme"; ?>
<div class="modal fade" id="al-success-alert" tabindex="-1" aria-labelledby="vertical-center-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-filled bg-light-success text-success">
            <div class="modal-body p-4">
                <div class="text-center text-success">
                    <i class="ti ti-circle-check fs-7"></i>
                    <h4 class="mt-2">Welcome <?= ucfirst($user['last_name']) . "," ?></h4>
                    <p class="mt-3">
                        <?= $d->get_settings("welcome_note"); ?>    
                    </p>
                    <a href="index?p=investment&action=overview" class="btn btn-success my-2">
                        Create Plan
                    </a><br>
                    <!-- <a href="index?type=global&p=investment&action=trades" class='text-primary fs-2'>See our AI recent trades</a> -->
                </div>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    Want to make enquires?
                    <a href="mailto:<?= $d->get_settings("support_email") ?>"><?= $d->get_settings("support_email") ?></a> or use the live chat at the buttom right.
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
<button id="showme" style="display: none;" type="button" class="btn mb-1 btn-light-info text-info font-medium btn-lg px-4 fs-4 font-medium" data-bs-toggle="modal" data-bs-target="#al-success-alert">
    Info Alert
</button>