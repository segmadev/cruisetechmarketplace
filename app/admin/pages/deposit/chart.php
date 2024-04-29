<?php
    $series = [$rejected['total'], $Approved['total'], $pending['total']];
    $labels = ["Rejected", "Approved", "Pending"];
?>
<script>
    donut_chart(<?=  json_encode($series) ?>, <?= json_encode($labels) ?>, "#deport-chart");
</script>