
        <table class="table border text-nowrap customize-table mb-0 align-middle">
          <thead>
            <tr>
              <th>Plan</th>
              <th>Retrun Amount</th>
              <th>% on retrun on deposit</th>
              <th>No of users</th>
              <th>Status</th>
            </tr>
            <?php foreach ($ref_p as $row) { 
                $plan = $d->getall("plans", "ID = ?", [$row['planID']]);
                ?>
              <tr>
                <td>
                  <b><?= number_format($plan['min_amount']) . ' - ' . number_format($plan['max_amount']) . " " . $plan['plan_name'] ?></b>
                </td>
                <td>
                  <?= $d->money_format($row['plan_amount'], currency) ?>
                </td>
                <td>
                  <?= $row['percentage_return_on_deposit'] ?>%
                </td>

                <td>
                    <?= number_format($row['no_of_users']) ?>
                </td>
                <td>
                  <?php echo $c->badge($row['status']); ?>

                </td>
                <td>
                  <div class="dropdown dropstart">
                    <a href="#" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="ti ti-dots-vertical fs-6"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <li>
                        <a class="dropdown-item d-flex align-items-center gap-3" href="index?p=referral&action=edit&id=<?= $row['ID'] ?>"><i class="fs-4 ti ti-edit"></i>Edit</a>
                      </li>
                     <li><a class="dropdown-item d-flex align-items-center gap-3" href="index?p=referral&action=view&id=<?= $row['ID'] ?>"><i class="fs-4 ti ti-eye"></i>View</a></li>
                    </ul>
                  </div>
                </td>
              </tr>
            <?php } ?>
          </thead>
          <tbody>

          </tbody>
        </table>
      