<div class="card w-100">
    <div class="card-body bg-light">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <div class="mb-3 mb-sm-0">
                <h5 class="card-title fw-semibold">Deposit</h5>
            </div>
        </div>
        <div class="table-responsive ">
            <table class="table align-middle text-nowrap mb-0 bg-transparent">
                <thead>
                    <tr class="text-muted fw-semibold" style="background-color: green";>
                        <th scope="col">Amount</th>
                        <th scope="col">Status</th>
                        <th scope="col">Title</th>
                        <th scope="col" class="ps-0">Ref No</th>
                        <th scope="col">Date</th>
                    </tr>
                </thead>
                <tbody id="depostTable"  class="border-top" data-load="deposit" data-displayId="depostTable" data-path="passer?p=account&get_payments=yes"></tbody>
            </table>
        </div>
    </div>
</div>