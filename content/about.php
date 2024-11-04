<?php $features = $d->getall("features", fetch: "moredetails") ?>
<section class="mx-auto max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 2xl:max-w-full">
                <!-- <div class="relative mb-6 overflow-hidden md:mb-8"><img
                        src="_astro/features-image.BEGIe8fA_Z2c6sgV.avif" alt="ScrewFast products in floating boxes"
                        class="h-full w-full object-cover object-center" draggable="false" loading="eager" width="4375"
                        height="2369" decoding="async"></div> -->
                <div class="mt-5 grid gap-8 lg:mt-16 lg:grid-cols-3 lg:gap-12">
                    <div class="lg:col-span-1">
                        <h2 class="text-balance text-2xl font-bold text-neutral-800 dark:text-neutral-200 md:text-3xl">
                        <?= $d->get_settings("about_us_title", 'content') ?></h2>
                        <p class="mt-2 text-pretty text-neutral-600 dark:text-neutral-400 md:mt-4"><?= $d->get_settings("about_us_details", 'content') ?></p>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="grid gap-8 sm:grid-cols-2 md:gap-12">
                            <?php 
                                if($features->rowCount() > 0) {
                                    foreach($features as $feature) {
                            ?>
                            <div class="flex gap-x-5">
                            <svg
                                    class="mt-1 h-8 w-8 flex-shrink-0 fill-orange-400 dark:fill-orange-300" height="48"
                                    viewBox="0 -960 960 960" width="48">
                                    <title></title>
                                    <path
                                        d="m346-60-76-130-151-31 17-147-96-112 96-111-17-147 151-31 76-131 134 62 134-62 77 131 150 31-17 147 96 111-96 112 17 147-150 31-77 130-134-62-134 62Zm27-79 107-45 110 45 67-100 117-30-12-119 81-92-81-94 12-119-117-28-69-100-108 45-110-45-67 100-117 28 12 119-81 94 81 92-12 121 117 28 70 100Zm107-341Zm-43 133 227-225-45-41-182 180-95-99-46 45 141 140Z"
                                        class></path>
                                </svg>
                                <div class="grow">
                                    <h3 class="text-balance text-lg font-bold text-neutral-800 dark:text-neutral-200">
                                       <?= $feature['title'] ?></h3>
                                    <p class="mt-1 text-pretty text-neutral-700 dark:text-neutral-300"><?= $feature['description'] ?></p>
                                </div>
                            </div>
                        <?php }}?>
                            
                        </div>
                    </div>
                </div>
            </section>