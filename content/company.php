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


    /* Container to control overflow and positioning */
.platform-logos-container {
    overflow: hidden;
    width: 100%; /* Ensures the container is full-width */
    position: relative;
}

/* Make logos container scrollable and auto-scrolling */
.platform-logos {
    display: flex;
    overflow-x: auto; /* Allow manual scrolling */
    white-space: nowrap; /* Prevent logos from wrapping */
    padding: 10px;
    scroll-behavior: smooth; /* Smooth scrolling for manual scroll */
    animation: scrollLogos 20s linear infinite; /* Automatic scrolling */
}

/* Hide scrollbar for WebKit browsers (Chrome, Safari) */
.platform-logos::-webkit-scrollbar {
    display: none;
}

/* Hide scrollbar for Firefox */
.platform-logos {
    scrollbar-width: none; /* For Firefox */
}

/* Automatic scrolling keyframes */
@keyframes scrollLogos {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-100%);
    }
}

/* Specific to platform logos only */
.platform-logos .platform-logo {
    display: inline-block;
    margin-right: 16px; /* Adjust spacing between logos */
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
                <div class="platform-logos-container">
                    <div class="platform-logos flex items-center gap-x-8 overflow-x-auto">
                        <?php 
                            foreach($platforms as $platform) {
                        ?>
                        <img class="platform-logo gray-image h-auto w-32 py-3 lg:w-40 lg:py-5" src="app/assets/images/icons/<?= $platform['icon'] ?>" alt="">
                        <?php } ?>
                    </div>
                </div>



            </section>

            <?php } ?>