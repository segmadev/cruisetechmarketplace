<?php $email_templates = $d->getall("email_template", "template != ? order by name ASC", [""], fetch: "moredetails") ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="border-bottom title-part-padding">
                <h4 class="card-title mb-0">Email Templates</h4>
            </div>
            <div class="card-body">
                <table class="table border text-nowrap customize-table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date Created</th>
                        </tr>
                        <?php foreach($email_templates as $row) { ?>
                            <tr>
                      <td>
                        <b><?= $row['name'] ?></b>
                      </td>
                      <td><p class="mb-0 fw-normal fs-4"><?= $d->date_format($row['date']) ?></p></td>
                      <td>
                        <div class="dropdown dropstart">
                          <a href="#" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-dots-vertical fs-6"></i>
                          </a>
                          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li>
                              <a class="dropdown-item d-flex align-items-center gap-3" href="email_template?action=edit&id=<?= $row['ID'] ?>"><i class="fs-4 ti ti-edit"></i>Edit</a>
                            </li>
                            
                          </ul>
                        </div>
                      </td>

                      
                      
                    </tr>
                        <?php } ?>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>