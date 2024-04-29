<?php
if (!isset($_GET['id'])) {
    echo "No Record found";
} else {
    $id = htmlspecialchars($_GET['id']);
    $activity = $d->getall("activities", "ID = ?", [$id]);
    if (is_array($activity)) { ?>
        <div class='card'>
            <div class="card-header">
                <h5><?= $activity['action_name'] ?></h5>
                <small><?= $d->date_format($activity['date_time']); ?></small>
            </div>
            <div class="card-body">
                <p><b>Details: </b><?= $activity['description'] ?></p><hr>
                <p><b>IP Address: </b> <?= $activity['ip_address'] ?></p>  <hr>
                <p><b>Location: </b><?= $activity['postal_code'].' '.$activity['city'].' '.$activity['state'].' '.$activity['country'] ?></p>
                <hr>
                <p><b>Device Info: </b> <?= $activity['device'] ?></p>
            </div>
        </div>
<?php } else {
        echo "No Record found";
    }
}
?>