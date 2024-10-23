<?php
// Discount levels data
$stages = [
    "stage0" => ["name" => "Level 0", "totalCredit" => 0, "discount" => "0", "discount_type" => "percentage", "no_order" => "1"],
    "stage1" => ["name" => "Level 1", "totalCredit" => 10000, "discount" => "2", "discount_type" => "percentage", "no_order" => "1"],
    "stage2" => ["name" => "Level 2", "totalCredit" => 500000, "discount" => "3", "discount_type" => "percentage", "no_order" => "2"],
    "stage3" => ["name" => "Level 3", "totalCredit" => 2000000, "discount" => "5", "discount_type" => "percentage", "no_order" => "1"],
    "stage4" => ["name" => "Level 4", "totalCredit" => 10000000, "discount" => "7", "discount_type" => "percentage", "no_order" => "1"],
    "stage5" => ["name" => "Level 5", "totalCredit" => 20000000, "discount" => "10", "discount_type" => "percentage", "no_order" => "1"],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discount Table</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Discount Levels</h2>
        <table class="tablesaw no-wrap table-bordered table-hover table tablesaw-stack" data-tablesaw="" id="tablesaw-3281">
            <thead>
                <tr>
                    <th scope="col" class="border">Level</th>
                    <th scope="col" class="border">Total Credit Required</th>
                    <th scope="col" class="border">Discount (%)</th>
                    <th scope="col" class="border">Minimum Order</th>
                </tr>
            </thead>
            <tbody id="checkall-target">
                <?php foreach ($stages as $stage): ?>
                    <tr>
                        <td class="title">
                            <a href="javascript:void(0)"><?= $stage['name']; ?></a>
                        </td>
                        <td>
                            <span class="tablesaw-cell-content"><?= number_format($stage['totalCredit']); ?></span>
                        </td>
                        <td>
                            <span class="tablesaw-cell-content"><?= $stage['discount']; ?>%</span>
                        </td>
                        <td>
                            <span class="tablesaw-cell-content"><?= $stage['no_order']; ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
