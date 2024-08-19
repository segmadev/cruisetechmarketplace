<style>
    .animate-hero {
        animation: myAnim 2s ease-in-out 1s infinite normal forwards;
    }

    @keyframes myAnim {
	0% {
		transform: translate(0);
	}

	20% {
		transform: translate(2px, -2px);
	}

	40% {
		transform: translate(2px, 2px);
	}

	60% {
		transform: translate(-2px, 2px);
	}

	80% {
		transform: translate(-2px, -2px);
	}

	100% {
		transform: translate(0);
	}
}
</style>
<section
                class="mx-auto grid max-w-[85rem] gap-4 px-4 py-14 sm:px-6 md:grid-cols-2 md:items-center md:gap-8 lg:px-8 2xl:max-w-full">
                <div>
                    <h1
                        class="block text-balance text-3xl font-bold tracking-tight text-neutral-800 dark:text-neutral-200 sm:text-4xl lg:text-6xl lg:leading-tight">
                        <?php 
                            $hero_title = $d->get_settings("home_header_title", 'content');
                            $hero_title = explode( " ", $hero_title );
                            $last = array_splice($hero_title, -1 );
                            echo implode( " ", $hero_title );
                            // echo $hero_title;
                        ?> <span class="text-yellow-500 dark:text-yellow-400"><?= $last[0]; ?></span>
                    </h1>
                    <p class="mt-3 text-pretty text-lg leading-relaxed text-neutral-700 dark:text-neutral-400 lg:w-4/5">
                        <?= $d->get_settings("home_header_short_description", 'content') ?></p>
                    <div class="mt-7 grid w-full gap-3 sm:inline-flex"><a
                            class="group inline-flex items-center justify-center gap-x-2 rounded-lg px-4 py-3 text-sm font-bold text-neutral-50 ring-zinc-500 transition duration-300 focus-visible:ring outline-none border border-transparent bg-orange-400 hover:bg-orange-500 active:bg-orange-500 dark:focus:outline-none disabled:pointer-events-none disabled:opacity-50 2xl:text-base dark:ring-zinc-200"
                            href="<?= $d->get_settings("home_header_btn_url", 'content') ?>"><?= $d->get_settings("home_header_btn_name", 'content') ?> <svg
                                class="h-4 w-4 flex-shrink-0 transition duration-300 group-hover:translate-x-1"
                                height="24" viewBox="0 0 24 24" width="24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <title></title>
                                <path d="m9 18 6-6-6-6" class></path>
                            </svg> </a><a
                            class="inline-flex items-center justify-center gap-x-2 rounded-lg px-4 py-3 text-center text-sm font-medium text-neutral-600 shadow-sm outline-none ring-zinc-500 focus-visible:ring transition duration-300 border border-neutral-200 bg-neutral-300 hover:bg-neutral-400/50 hover:text-neutral-600 active:text-neutral-700 disabled:pointer-events-none disabled:opacity-50 2xl:text-base ring-zinc-500 dark:border-neutral-700 dark:bg-zinc-700 dark:text-neutral-300 dark:ring-zinc-200 dark:hover:bg-zinc-600 dark:focus:outline-none"
                            href="app/login">Login</a></div>
                            <?php if($d->get_settings("telegram")) { ?>

                <a class="sidebar-link inline-flex mt-2"  target="_BLANK" href="<?= $d->get_settings("telegram") ?>" aria-expanded="false">
                    <img src="https://cdn.pixabay.com/photo/2021/12/27/10/50/telegram-icon-6896828_960_720.png" style="width:20px" srcset="Join out telegram channel">
                    <b><span class="hide-menu ms-2 text-pretty"> Join Our Telegram for news and update</span></b>
                    
                </a>
                
            <?php } ?>
                </div>

           

                <div class="flex w-full">
                    <div class="top-12 overflow-hidden"><img class='animate-hero' src="app/assets/images/banners/<?= $d->get_settings("home_header_img", "content") ?>"
                            alt="Stack of ScrewFast product boxes containing assorted hardware tools"
                            class="h-full w-full scale-110 object-cover object-center" draggable="false" loading="eager"
                            width="4067" height="2576" decoding="async"></div>
                </div>
            </section>