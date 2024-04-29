<?php 
    $platforms = $d->getall("platform", fetch: "moredetails");
    if($platforms->rowCount() > 0) {
?>
<style>
    .gray-image {
        filter: grayscale(80%);
        background-color:  white;
        clip-path: circle();
        padding: 10px;
        width: 100px;
    }
</style>
<section class="mx-auto max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 2xl:max-w-full">
                <div class="mx-auto mb-6 w-full space-y-1 text-center sm:w-1/2 lg:w-1/3">
                    <h2
                        class="text-balance text-2xl font-bold leading-tight text-neutral-800 dark:text-neutral-200 sm:text-3xl">
                        Platfroms</h2>
                    <p class="text-pretty leading-tight text-neutral-600 dark:text-neutral-400">We sell account for all your favourite platfroms. Below are some of them</p>
                </div>
                <div
                    class="flex flex-col items-center justify-center gap-y-2 sm:flex-row sm:gap-x-12 sm:gap-y-0 lg:gap-x-24">
                    <?php 
                        foreach($platforms as $platform) {
                    ?>
                    <img class="gray-image mx-auto h-auto w-32 py-3 sm:mx-0 lg:w-40 lg:py-5" src="app/assets/images/icons/<?= $platform['icon'] ?>" alt="" srcset="">
                    <?php } ?>
                </div>
            </section>

            <?php } ?>