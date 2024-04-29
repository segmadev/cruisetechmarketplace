<?php
if ($platforms == null || $platforms <= 0) {
    echo $c->empty_page("No platform created yet. <br> You can add new platform to start managing platform.", "<a href='index?p=platform&action=new' class='btn btn-default'>Add platform</a>");
}
$script[] = "modal";
$script[] = "sweetalert";
?>
<style>
    .table>:not(caption)>*>*  {
        background-color: transparent!important;
    }
</style>
<div class="card">
    <div class="card-body" data-load="get" data-page="platform" data-displayId="displayPlatforms">
        <div class="card-title">
            <h1>List of Platform</h1>
        </div>
        <div class="table-responsive bg-white p-3">
            <table class="table align-middle text-nowrap mb-0">
                <thead >
                    <tr class="text-muted fw-semibold">
                        <th scope="col" class="ps-0">Platfrom Name</th>
                        <th scope="col">No Accts</th>
                        <th scope="col">Date</th>
                    </tr>
                </thead>
                <tbody class="border-top" id="displayPlatforms"></tbody>
            </table>
        </div>
    </div>
</div>