<table class="table align-middle text-nowrap mb-0 bg-transparent">
    <thead>
        <tr class="text-muted fw-semibold" style="background-color: green" ;>
            <th scope="col">Amount</th>
            <th scope="col">Status</th>
            <th scope="col">Title</th>
            <th scope="col" class="ps-0">Ref No</th>
            <th scope="col">Date</th>
        </tr>
    </thead>
    <tbody id="depostTable" class="border-top" data-load="deposit" data-displayId="depostTable" data-path="passer?p=deposit&get_payments=yes">
        <?php 
            if(isset($pay_table)) {
                foreach ($pay_table as $pay) {
                    require "../pages/deposit/pay_table.php";
                }
            }
        ?>
    </tbody>
</table>