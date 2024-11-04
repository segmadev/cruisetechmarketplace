<div class="table-responsive">
    <table id="zero_config" class="table border table-striped table-bordered text-nowrap">
        <thead>
            <!-- start row -->
            <tr>
                <th></th>
                <th>Status</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Date Created</th>
            </tr>
            <!-- end row -->
        </thead>
        <tbody>
            <?php 
            $admins = $d->getall("admins", fetch: "all");
            foreach ($admins as $row) { 
                $status = $row['status'] == 0 ? "inActive" : "Active";
                ?>
                <!-- start row -->
                <tr>
                    <td>
                        <div class="btn-group mb-2">
                            <button type="button" class="btn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class='ti ti-dots'></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="index?p=admins&action=new&id=<?= $row['ID']; ?>">Edit</a></li>
                                                <!-- <li>
                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                </li> -->
                                
                            </ul>
                        </div>
                    </td>
                    <td class="gap-2"><?= $c->badge($status) ?></td>
                    <td class="d-flex"><?= $row['first_name'] . ' ' . $row['last_name']; ?></td>
                    <td><a href="mailto:<?= $row['email'] ?>" target="_blank"><?= $row['email'] ?></a></td>
                    <td><a href="tel:<?= $row['phone_number'] ?>"><?= $row['phone_number'] ?></a></td>
                    <td><?= $d->date_format($row['date']) ?></td>

                </tr>
                <!-- end row -->
            <?php } ?>
            </tfoot>
    </table>
</div>