<?php
$script[] = "stacktable";
$stages = json_decode($d->get_settings("discounts"), true);
require_once "pages/profile/stage.php";
?>

<link rel="stylesheet" href="dist/libs/tablesaw/dist/tablesaw.css">
<div class="container mt-5">
<h1>How Our Discount Plan Works</h1>
    <p>
      Our discount plan is to rewards users for being loyal and actively funding their accounts. The more Deposit you accumulate, the higher your level and the better your discount on future orders. Each level comes with:
    </p>
    <ul>
      <li><b>Total Deposit:</b> The total amount you need to accumulate to unlock a level.</li>
      <li><b>Discount:</b> A percentage off your orders at that level.</li>
      <li><b>Minimum Order:</b> The minimum number of accounts you need to order in one transaction to enjoy the discount.</li>
    </ul>
        <p class='p-2 bg-danger text-white'>
            <b>Note:</b>
             Discount plans do not apply to numbers. <br>
             This Discount plans can be reviewed and adjust without notifying users.
        </p>
    <h2>Levels and Discounts</h2>
    <p>
      Below is a table that shows the different levels, required Deposits, discounts, and the minimum number of accounts you need to order to access the discount.
    </p>
        <table class="tablesaw no-wrap table-bordered table-hover table" data-tablesaw-mode="stack">
            <thead>
                <tr>
                        <th scope="col" data-tablesaw-priority="persist" class="border">
                        Level
                        </th>
                        <th scope="col" data-tablesaw-sortable-default-col data-tablesaw-priority="3" class="border">
                        Total Deposit Required
                        </th>
                        <th scope="col" data-tablesaw-priority="2" class="border">
                        Discount (%)
                        </th>

                        <th scope="col" data-tablesaw-priority="4" class="border">
                        Minimum Order
                        </th>
                      </tr>

            </thead>
            <tbody id="checkall-target">
                <?php 
                $i = 1;
                foreach ($stages as$stage): ?>
                    <tr>
                        <td class="title">
                            <a href="javascript:void(0)"><?php echo $stage['name'];
                                echo "<div style='zoom: 0.5'>".$u->stagesBadge(stage: (int)$i)."</div>";
                                $i++;
                            ?></a>
                        </td>
                        <td>
                            <span class="tablesaw-cell-content"><?= $d->money_format($stage['totalCredit']);?></span>
                        </td>
                        <td>
                            <span class="tablesaw-cell-content"><?=$stage['discount'];?>%</span>
                        </td>
                        <td>
                            <span class="tablesaw-cell-content"><?=$stage['no_order'];?></span>
                        </td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>

