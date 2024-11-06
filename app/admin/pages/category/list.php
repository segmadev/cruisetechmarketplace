<?php
if ($categories == null || $categories <= 0) {
    echo $c->empty_page("No category created yet. <br> You can add new category to start managing category.", "<a href='index?p=category&action=new' class='btn btn-default'>Add category</a>");
}
$script[] = "modal";
$script[] = "sweetalert";
$script[] = "fetcher";
?>
<style>
    .table>:not(caption)>*>*  {
        background-color: transparent!important;
    }
</style>
<div class="card">
    <div class="card-body" data-limit="100" data-start = "0" data-path="passer?get=category" data-load="category" data-page="category" data-displayId="displaycategories">
        <div class="card-title">
            <h1>List of category</h1>
        </div>
        <div class="table-responsive bg-white p-3">
            <table class="table align-middle text-nowrap mb-0">
                <thead >
                    <tr class="text-muted fw-semibold">
                        <th scope="col" class="ps-0">Category Name</th>
                        <th scope="col">No Accts</th>
                        <th scope="col">Date</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody class="border-top" id="displaycategories"></tbody>
            </table>
        </div>
    </div>
</div>