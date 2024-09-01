<!DOCTYPE html>
<?php 
    require_once "include/ini.php";
?>
<html lang="en" class="scrollbar-hide lenis lenis-smooth scroll-pt-16 astro-ouamjn2i light">
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

<head>
    <meta charset="utf-8">
    <meta name="web_author" content="Segma.dev">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=5,minimum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta property="og:locale" content="en_US">
    <meta name="description" content="<?= $d->get_settings("seo_description") ?>" />
    <link rel="icon" href="app/assets/images/logos/<?= $d->get_settings("favicon") ?>" />
    <link rel="sitemap" href="sitemap-index.xml">
    <meta name="theme-color" content="#fa5a15">
    <title><?= $d->get_settings("company_name") ?></title>
    <script>
        //     "" != localStorage.getItem("hs_theme") || ("hs_theme" in localStorage) && window.matchMedia(
        //     "(prefers-color-scheme: )").matches ? document.documentElement.classList.add("") : document
        // .documentElement.classList.remove("")
        // document.documentElement.classList.add("");
        // document.documentElement.classList.remove("")
    </script>
    <script src="scripts/vendor/lenis/lenis.js"></script>
    <script>
    const lenis = new Lenis;

    function raf(n) {
        lenis.raf(n), requestAnimationFrame(raf)
    }
    requestAnimationFrame(raf)
    </script>
    <link rel="stylesheet" href="_astro/index.DlmQ6ydn.css">
    <style>
    .scrollbar-hide:where(.astro-ouamjn2i)::-webkit-scrollbar {
        display: none
    }

    .scrollbar-hide:where(.astro-ouamjn2i) {
        -ms-overflow-style: none;
        scrollbar-width: none
    }

    html.lenis,
    html.lenis body {
        height: auto
    }

    .lenis:where(.astro-ouamjn2i).lenis-smooth {
        scroll-behavior: auto !important
    }

    .lenis:where(.astro-ouamjn2i).lenis-smooth :where(.astro-ouamjn2i)[data-lenis-prevent] {
        overscroll-behavior: contain
    }

    .lenis:where(.astro-ouamjn2i).lenis-stopped {
        overflow: hidden
    }

    .lenis:where(.astro-ouamjn2i).lenis-scrolling iframe:where(.astro-ouamjn2i) {
        pointer-events: none
    }
    </style>
    <script type="module" src="_astro/page.CFW0rSNk.js"></script>
</head>

<body class="bg-neutral-200 selection:bg-yellow-400 selection:text-neutral-700 :bg-neutral-800 astro-ouamjn2i">
    <div class="mx-auto max-w-screen-2xl px-4 sm:px-6 lg:px-8 astro-ouamjn2i">
        <header class="sticky inset-x-0 top-4 z-50 flex w-full flex-wrap text-sm md:flex-nowrap md:justify-start">
            <nav class="relative mx-2 w-full rounded-[36px] border border-yellow-100/40 bg-yellow-50/60 px-4 py-3 backdrop-blur-md md:flex md:items-center md:justify-between md:px-6 md:py-0 lg:px-8 xl:mx-auto"
                aria-label="Global">
                <div class="flex items-center justify-between"><a
                        class="flex-none rounded-lg text-xl font-bold outline-none ring-zinc-500 focus-visible:ring :ring-zinc-200 :focus:outline-none"
                        href="/" aria-label="Brand">
                        <!-- logo -->
                        <img src="app/assets/images/logos/<?= $d->get_settings("light_logo") ?>" class="h-auto" style="width: 200px;" alt="www">
                        
                        <!-- logo ends here -->
                    </a>
                    <div class="ml-auto mr-5 md:hidden"><button type="button"
                            class="hs-collapse-toggle flex h-8 w-8 items-center justify-center rounded-full text-sm font-bold text-neutral-600 transition duration-300 hover:bg-neutral-200 disabled:pointer-events-none"
                            data-hs-collapse="#navbar-collapse-with-animation"
                            aria-controls="navbar-collapse-with-animation" aria-label="Toggle navigation"><svg
                                class="h-[1.25rem] w-[1.25rem] flex-shrink-0 hs-collapse-open:hidden" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <line x1="3" x2="21" y1="6" y2="6"></line>
                                <line x1="3" x2="21" y1="12" y2="12"></line>
                                <line x1="3" x2="21" y1="18" y2="18"></line>
                            </svg> <svg class="hidden h-[1.25rem] w-[1.25rem] flex-shrink-0 hs-collapse-open:block"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"></path>
                                <path d="m6 6 12 12"></path>
                            </svg></button></div><span class="inline-block md:hidden"><button type="button"
                            aria-label=" Theme Toggle"
                            class="hs--mode group flex h-8 w-8 items-center justify-center rounded-full font-medium text-neutral-600 outline-none ring-zinc-500 transition duration-300 hover:bg-neutral-200 hover:text-orange-400 hs--mode-active:hidden "
                            data-hs-theme-click-value=""><svg class="h-4 w-4 flex-shrink-0" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                            </svg></button> <button type="button" aria-label="Light Theme Toggle"
                            class="hs--mode group hidden h-8 w-8 items-center justify-center rounded-full font-medium text-neutral-600 outline-none ring-zinc-500 transition duration-300 hover:text-orange-400 hs--mode-active:flex "
                            data-hs-theme-click-value="light"><svg class="h-4 w-4 flex-shrink-0" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="4"></circle>
                                <path d="M12 8a2 2 0 1 0 4 4"></path>
                                <path d="M12 2v2"></path>
                                <path d="M12 20v2"></path>
                                <path d="m4.93 4.93 1.41 1.41"></path>
                                <path d="m17.66 17.66 1.41 1.41"></path>
                                <path d="M2 12h2"></path>
                                <path d="M20 12h2"></path>
                                <path d="m6.34 17.66-1.41 1.41"></path>
                                <path d="m19.07 4.93-1.41 1.41"></path>
                            </svg></button></span>
                </div>
                <div id="navbar-collapse-with-animation"
                    class="hs-collapse hidden grow basis-full overflow-hidden transition-all duration-300 md:block">
                    <div
                        class="mt-5 flex flex-col gap-x-0 gap-y-4 md:mt-0 md:flex-row md:items-center md:justify-end md:gap-x-4 lg:gap-x-7 md:gap-y-0 md:ps-7">
                        <!-- <a id="home" href="/" data-astro-prefetch
                            class="rounded-lg text-base font-medium text-neutral-600 outline-none ring-zinc-500 hover:text-neutral-500 focus-visible:ring :text-neutral-400 :ring-zinc-200 :hover:text-neutral-500 :focus:outline-none md:py-3 md:text-sm 2xl:text-base">Home</a> -->
                        <script type="module">
                        document.addEventListener("DOMContentLoaded", function() {
                            let t = window.location.pathname;
                            t.split("index");
                            let a;
                            t === "/" ? a = "home" : a = t.replace("index", "");
                            let e = document.getElementById(a);
                            e && (e.classList.remove("text-neutral-600", ":text-neutral-400",
                                    "hover:text-neutral-500", ":hover:text-neutral-500"), e.classList
                                .add("text-orange-400", ":text-orange-300"), e.setAttribute(
                                    "aria-current", "page"))
                        });
                        </script>
                        <a id="contact" href="app/register" data-astro-prefetch
                            class="rounded-lg text-base font-medium text-neutral-600 outline-none ring-zinc-500 hover:text-neutral-500 focus-visible:ring :text-neutral-400 :ring-zinc-200 :hover:text-neutral-500 :focus:outline-none md:py-3 md:text-sm 2xl:text-base">Register
                        </a>

                        <a type="button"
                            class="flex items-center gap-x-2 text-base md:text-sm font-medium text-neutral-600 ring-zinc-500 transition duration-300 focus-visible:ring outline-none hover:text-orange-400 :hover:text-orange-300 :border-neutral-700 :text-neutral-400 :ring-zinc-200 :focus:outline-none md:my-6 md:border-s md:border-neutral-300 md:ps-6 2xl:text-base"
                            href="app/index"><svg class="h-4 w-4 flex-shrink-0" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg> Log in</a>
                        <script src="scripts/vendor/preline/dropdown/index.js"></script>
                        <script type="module">
                        const o = ["en", "fr"];
                        document.addEventListener("DOMContentLoaded", function() {
                            document.querySelectorAll(".hs-dropdown-menu a").forEach(s => {
                                const i = s,
                                    n = i.getAttribute("href")?.replace("index", "").replace(
                                        "index", "");
                                i.addEventListener("click", function(l) {
                                    l.preventDefault();
                                    const e = new URL(404. html),
                                        t = e.pathname.split("index").filter(a => a && !o
                                            .includes(a));
                                    n !== e.pathname.split("index")[1] && (e.pathname
                                        .includes("/post") ? e.pathname.includes("en") ? (t
                                            .unshift(n), t.splice(2, 0, n)) : (t.unshift(n),
                                            t.splice(2, 0, "en")) : n !== "en" && t.unshift(
                                            n), e.pathname = t.join("index"), window
                                        .location.href = e.toString())
                                })
                            })
                        });
                        </script>
                        <span class="hidden md:inline-block"><button type="button" aria-label=" Theme Toggle"
                                class="hs--mode group flex h-8 w-8 items-center justify-center rounded-full font-medium text-neutral-600 outline-none ring-zinc-500 transition duration-300 hover:bg-neutral-200 hover:text-orange-400 hs--mode-active:hidden :text-neutral-400 :ring-zinc-200 :hover:text-orange-300 :focus:outline-none"
                                data-hs-theme-click-value=""><svg class="h-4 w-4 flex-shrink-0" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                                </svg></button> <button type="button" aria-label="Light Theme Toggle"
                                class="hs--mode group hidden h-8 w-8 items-center justify-center rounded-full font-medium text-neutral-600 outline-none ring-zinc-500 transition duration-300 hover:text-orange-400 hs--mode-active:flex :text-neutral-400 :ring-zinc-200 :hover:bg-neutral-700 :hover:text-orange-300 :focus:outline-none"
                                data-hs-theme-click-value="light"><svg class="h-4 w-4 flex-shrink-0" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="4"></circle>
                                    <path d="M12 8a2 2 0 1 0 4 4"></path>
                                    <path d="M12 2v2"></path>
                                    <path d="M12 20v2"></path>
                                    <path d="m4.93 4.93 1.41 1.41"></path>
                                    <path d="m17.66 17.66 1.41 1.41"></path>
                                    <path d="M2 12h2"></path>
                                    <path d="M20 12h2"></path>
                                    <path d="m6.34 17.66-1.41 1.41"></path>
                                    <path d="m19.07 4.93-1.41 1.41"></path>
                                </svg></button></span>
                    </div>
                </div>
            </nav>
        </header>