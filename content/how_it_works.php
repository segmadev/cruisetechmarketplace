<?php
$hows = $d->getall("how_it_works", fetch: "moredetails");
?>

<section class="mx-auto max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 2xl:max-w-full">
    <div class="relative z-10 lg:grid">
        <div class="mb-10 lg:order-2 lg:col-span-6 lg:col-start-8 lg:mb-0">
        <div class="mx-auto mb-6 w-full space-y-1 text-center sm:w-1/2 lg:w-1/3">
                    <h2
                        class="text-balance text-2xl font-bold leading-tight text-neutral-800 dark:text-neutral-200 sm:text-3xl">
                        Getting is very easy <br> <span class="text-yellow-500 dark:text-yellow-400">HOW IT WORKS.</h2>
                    <p class="text-pretty leading-tight text-neutral-600 dark:text-neutral-400"></p>
                </div>

            
            <nav class="grid gap-8 sm:grid-cols-2 md:gap-12" aria-label="Tabs" role="tablist">
               <?php if($hows->rowCount() > 0) {
                foreach($hows as $how) {?> 
                <button type="button" class="active dark:hover:bg-neutral-700 rounded-xl p-4 text-start outline-none ring-zinc-500 transition duration-300 hover:bg-neutral-200 focus-visible:ring hs-tab-active:bg-neutral-50 hs-tab-active:shadow-md hs-tab-active:hover:border-transparent dark:ring-zinc-200 dark:focus:outline-none dark:hs-tab-active:bg-neutral-700/60 md:p-5" id="tabs-with-card-item-1" data-hs-tab="#tabs-with-card-1" aria-controls="tabs-with-card-1" role="tab">
                <center class="dark:bg-neutral-800 ">
                    <img class="animate-hero" src="app/assets/images/banners/<?= $how['image'] ?>" style="width: 250px" alt="<?= $how['title'] ?>">
                </center>    
                <span class="">

                        <span class="ms-6 grow">
                            <span class="block text-lg font-bold text-neutral-800 hs-tab-active:text-orange-400 dark:text-neutral-200 dark:hs-tab-active:text-orange-300"><?= $how['title'] ?></span>
                                <span class="mt-1 block text-neutral-500 hs-tab-active:text-neutral-600 dark:text-neutral-400 dark:hs-tab-active:text-neutral-200"><?= $how['description'] ?>
                            </span></span></span>
                        </button>
                <?php 
                }
               } ?>
            

            </nav>
        </div>

    </div>

</section>
<script src="scripts/vendor/preline/tabs/index.js"></script>