<div class="dropdown m-2">
                    <a href="javascript:void(0)" id="m2" class="btn btn-outline-primary col-6 col-md-3" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ti ti-filter fs-4"></i> <b id="PlaformName">All Catgories</b>
                    </a>
<ul class="dropdown-menu" aria-labelledby="m2" data-popper-placement="bottom-end" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(0px, 21px, 0px);">
    <li>
        <a class="dropdown-item" href="#" onclick="addPlatfrom('', 'All')">
            All Categories </a>
    </li>
    <?php if ($categories->rowCount() > 0) {
        foreach ($categories as $category) {
            $new_categories[] = $category;
    ?>
            <li>
                <a class="dropdown-item" href="index?action=view&category=<?= $category['ID'] ?>">
                    <?= $category['name'] ?> </a>
            </li>
    <?php  }
    $categories = $new_categories;
    }  ?>   
</ul>
</div>