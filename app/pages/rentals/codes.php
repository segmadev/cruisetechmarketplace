<?php
if ($codes != "") {
    $i =0;
    foreach ($codes as $code) {
        $i++
        ?>
        <div class="hstack gap-3 align-items-start mb-7 justify-content-start">
            <div class="text-start">
                <h6 class="fs-2 text-muted"><?= $d->date_format($code['date']) ?></h6>
                <div class="p-2 bg-light-info text-dark rounded-1 d-inline-block fs-3"> <?= $code['NumberCode'] ?> <?= $c->copy_text($code['NumberCode']) ?></div>
            </div>
        </div>
        <?php
    }
    if($i == 0) {
        echo $c->empty_page("No code received yet.");
    }
}else {
    echo $c->empty_page("No code received yet.");
}
?>