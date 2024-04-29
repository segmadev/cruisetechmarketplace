<div class="row">
    <?php
    if ($ref_programes->rowCount() > 0) {
        foreach ($ref_programes as $ref_programe) {
            echo $c->referral_list($ref_programe, "index?p=referral&action=new&activate=" . $ref_programe['ID'], "border-sucess");
        }
    }
    ?>
</div>