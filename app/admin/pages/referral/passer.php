<?php
if(isset($_POST['new_referral'])) {
    $r->new_referral($referral_from);
}
if(isset($_POST['edit_referral'])) {
    $r->edit_referral($referral_from);
}