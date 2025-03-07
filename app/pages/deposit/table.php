<div class="card w-100 mt-4">
    <div class="card-body bg-light">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <div class="mb-3 mb-sm-0">
                <h5 class="card-title fw-semibold">Deposit</h5>
                <p class='p-0 mb-0'>All payment from other payment option.</p>
                <div class='p-0 m-0'><small>All <span class="badge bg-light-warning text-warning fw-semibold fs-2"><small>Pending</small></span> payment i.e incomplete payement will automatically be delete after 2days.</small></div>
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
                <tbody id="depostTable"  class="border-top" data-load="deposit" data-limit='20' data-displayId="depostTable" data-path="passer?p=account&get_payments=yes"></tbody>
            </table>
        </div>
    </div>
</div>