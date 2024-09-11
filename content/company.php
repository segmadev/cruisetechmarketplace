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

    /* Scope the animation to .platform-logos container */
.platform-logos {
    overflow: hidden; /* Ensures logos stay within viewable area */
    position: relative;
}

/* Specific to platform logos only */
.platform-logos .platform-logo {
    display: inline-block;
    animation: platformMoveLeft 5s linear infinite; /* Adjust time as needed */
    will-change: transform;
}

/* Keyframes scoped specifically to platform logo animation */
@keyframes platformMoveLeft {
    0% {
        transform: translateX(100%);
    }
    100% {
        transform: translateX(-100%);
    }
}

/* Responsive design scoped for .platform-logo */
@media (min-width: 640px) {
    .platform-logos .platform-logo {
        width: 40px;
        padding-top: 5px;
    }
}

@media (min-width: 1024px) {
    .platform-logos .platform-logo {
        width: 50px;
        padding-top: 10px;
    }
}

</style>
<section class="mx-auto max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 2xl:max-w-full">
                <div class="mx-auto mb-6 w-full space-y-1 text-center sm:w-1/2 lg:w-1/3">
                    <h2
                        class="text-balance text-2xl font-bold leading-tight text-neutral-800 dark:text-neutral-200 sm:text-3xl">
                        Platforms</h2>
                    <p class="text-pretty leading-tight text-neutral-600 dark:text-neutral-400">We sell account for all your favourite Platforms. Below are some of them</p>
                </div>
                <div class="platform-logos flex flex-col items-center justify-center gap-y-2 sm:flex-row sm:gap-x-12 sm:gap-y-0 lg:gap-x-24">
                    <?php 
                        foreach($platforms as $platform) {
                    ?>
                    <img class="platform-logo gray-image mx-auto h-auto w-32 py-3 sm:mx-0 lg:w-40 lg:py-5" src="app/assets/images/icons/<?= $platform['icon'] ?>" alt="" srcset="">
                    <?php } ?>
                </div>

            </section>

            <?php } ?>