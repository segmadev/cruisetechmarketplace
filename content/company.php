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

/* Force container width to be 90% of the screen */
.platform-logos-container {
    width: 90%;
    margin: 0 auto; /* Center the container */
    overflow-x: auto; /* Allow manual scrolling */
    white-space: nowrap; /* Prevent wrapping of logos */
    position: relative;
    scroll-behavior: smooth; /* Ensure smooth manual scrolling */
}

/* Make logos container scrollable and create endless scrolling effect */
.platform-logos {
    display: flex;
    white-space: nowrap; /* Prevent wrapping */
    animation: scrollEndless 10s linear infinite; /* Smooth automatic scrolling */
    padding: 10px;
    scroll-behavior: smooth; /* Smooth scrolling for manual interaction */
}

/* Endless scrolling keyframes */
@keyframes scrollEndless {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%); /* Move halfway to create looping effect */
    }
}

/* Ensure smooth manual scrolling and hide scrollbars */
.platform-logos-container::-webkit-scrollbar {
    display: none; /* Hide scrollbar for WebKit browsers */
}

.platform-logos-container {
    scrollbar-width: none; /* Hide scrollbar for Firefox */
}

/* Platform logo styles */
.platform-logo {
    display: inline-block;
    margin-right: 16px;
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
    <div class="platform-logos flex items-center gap-x-8">
        <?php 
            $newPlatfroms = [];
            foreach($platforms as $platform) {
                $newPlatfroms[] = $platform;
        ?>
        <img class="platform-logo gray-image h-auto w-32 py-3 lg:w-40 lg:py-5" src="app/assets/images/icons/<?= $platform['icon'] ?>" alt="">
        <?php } ?>
        <!-- Duplicate logos to create the endless scrolling effect -->
        <?php 
            foreach($newPlatfroms as $platform) {
        ?>
        <img class="platform-logo gray-image h-auto w-32 py-3 lg:w-40 lg:py-5" src="app/assets/images/icons/<?= $platform['icon'] ?>" alt="">
        <?php } ?>
    </div>
</div>



                
          



            </section>

            <?php } ?>