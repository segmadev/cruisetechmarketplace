<script src="dist/libs/apexcharts/dist/apexcharts.min.js"></script>
<!-- trades -->
<?php
if (isset($chat_trades)) {
    $invet_chart_data = $i->extract_chart_data($chat_trades);
?>
    <script>
        lines_chart(<?= json_encode($invet_chart_data) ?>, "#invest-chat");
    </script>
<?php
}
?>