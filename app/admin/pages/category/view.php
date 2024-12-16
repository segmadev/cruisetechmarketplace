<div class="container mt-5">
    <h2 class="mb-4">Offline Categories Report.</h2>

    <!-- Date Filter Form -->
    <form method="GET" action="index" class="form-inline mb-4">
        <div class="form-group mr-2 row">
            <div class="form-group col-9 col-sm-10">
                <input type="hidden" name="p" value="category">
                <input type="hidden" name="action" value="view">
                <!-- <label for="filter_date" class="mr-2">Select Date:</label> -->
                <input type="date" id="filter_date" name="filter_date" class="form-control"
                    value="<?= htmlspecialchars($filterDate); ?>">
            </div>
            <div class="col-3 col-sm-2"><button type="submit" class="btn btn-primary">Filter</button></div>
        </div>
    </form>

    <!-- Display Data Table -->
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Account ID</th>
                <th>Amount</th>
                <th>Real Amount</th>
                <th>Effective Amount</th>
                <th>Login Count</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <!-- Overall Totals Placeholder -->
            <tr class="table-info font-weight-bold">
                <td colspan="4" class="text-right">Overall Totals:</td>
                <td id="overall-login-count"></td>
                <td id="overall-total"></td>
            </tr>

            <!-- Data Rows -->
            <?php if (!empty($oflineReport)): ?>
            <?php foreach ($oflineReport as $row): ?>
            <?php
                    // Accumulate totals
                    $overallLoginCount += $row['login_count'];
                    $overallTotal += $row['total'];
                    ?>
            <tr>
                <td><?= htmlspecialchars($row['accountID']); ?></td>
                <td><?= htmlspecialchars($row['amount']); ?></td>
                <td><?= htmlspecialchars($row['real_amount']); ?></td>
                <td><?= htmlspecialchars($row['effective_amount']); ?></td>
                <td><?= htmlspecialchars($row['login_count']); ?></td>
                <td><?= htmlspecialchars($row['total']); ?></td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">No data found</td>
            </tr>
            <?php endif; ?>

            <!-- Update Overall Totals -->
            <script>
            document.getElementById("overall-login-count").textContent = <?= $overallLoginCount; ?>;
            document.getElementById("overall-total").textContent = <?= $overallTotal; ?>;
            </script>
        </tbody>
    </table>
</div>