<script>
    function showall(id) {
        // Get the element by its ID
        var pElement = document.getElementById(id);
        // Replace the innerHTML with the data-fulltext attribute's value
        pElement.innerHTML = pElement.getAttribute('data-fulltext');
    }
</script>
<?php
$facebook_link = $d->get_settings('facebook_link');
$telegram = $d->get_settings('telegram');
$instagram_link = $d->get_settings('instagram_link');
$x_link = $d->get_settings('x_link');
$tiktok_link = $d->get_settings('tiktok_link');
?>
<footer class="w-full bg-neutral-300 dark:bg-neutral-900">
    <div class="mx-auto w-full max-w-[85rem] px-4 py-10 sm:px-6 lg:px-5 lg:pt-5 2xl:max-w-screen-2xl">
        <div class="mt-9 grid gap-y-2 sm:mt-12 sm:flex sm:items-center sm:justify-between sm:gap-y-0">
            <div class="flex items-center justify-between">
                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                    © <span id="current-year"></span> 
                    <a class="rounded-lg font-medium underline underline-offset-2 outline-none ring-zinc-500 transition duration-300 hover:text-neutral-700 hover:decoration-dashed focus:outline-none focus-visible:ring dark:ring-zinc-200 dark:hover:text-neutral-300" href="/" target="" rel="noopener noreferrer"><?= $d->get_settings(); ?></a> |
                    <a class="rounded-lg font-medium underline underline-offset-2 outline-none ring-zinc-500 transition duration-300 hover:text-neutral-700 hover:decoration-dashed focus:outline-none focus-visible:ring dark:ring-zinc-200 dark:hover:text-neutral-300" href="?page=policy" target="_blank" rel="noopener noreferrer">Privacy Policy</a> |
                    <a class="rounded-lg font-medium underline underline-offset-2 outline-none ring-zinc-500 transition duration-300 hover:text-neutral-700 hover:decoration-dashed focus:outline-none focus-visible:ring dark:ring-zinc-200 dark:hover:text-neutral-300" href="?page=terms" target="_blank" rel="noopener noreferrer">Terms and conditions</a>
                </p>
            </div>
            <div>
                <?php if ($telegram != "") { ?>
                    <a class="inline-flex h-10 w-10 items-center justify-center gap-x-2 rounded-lg border border-transparent text-sm font-bold text-neutral-700 outline-none ring-zinc-500 hover:bg-neutral-500/10 focus:outline-none focus-visible:ring focus-visible:ring-zinc-500 dark:ring-zinc-200 dark:hover:bg-neutral-50/10" href="<?= $telegram ?>" target="_blank" rel="noopener noreferrer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-telegram" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M15 10l-4 4l6 6l4 -16l-18 7l4 2l2 6l3 -4" />
                        </svg>
                    </a>
                <?php } ?>
                <?php if ($facebook_link != "") { ?>
                    <a class="inline-flex h-10 w-10 items-center justify-center gap-x-2 rounded-lg border border-transparent text-sm font-bold text-neutral-700 outline-none ring-zinc-500 hover:bg-neutral-500/10 focus:outline-none focus-visible:ring focus-visible:ring-zinc-500 dark:ring-zinc-200 dark:hover:bg-neutral-50/10" href="<?= $facebook_link ?>" target="_blank" rel="noopener noreferrer"><svg class="h-4 w-4 flex-shrink-0 fill-current text-neutral-700 dark:text-neutral-400" viewBox="0 0 24 24" fill="currentColor">
                            <title>Facebook</title>
                            <path d="M9.101 23.691v-7.98H6.627v-3.667h2.474v-1.58c0-4.085 1.848-5.978 5.858-5.978.401 0 .955.042 1.468.103a8.68 8.68 0 0 1 1.141.195v3.325a8.623 8.623 0 0 0-.653-.036 26.805 26.805 0 0 0-.733-.009c-.707 0-1.259.096-1.675.309a1.686 1.686 0 0 0-.679.622c-.258.42-.374.995-.374 1.752v1.297h3.919l-.386 2.103-.287 1.564h-3.246v8.245C19.396 23.238 24 18.179 24 12.044c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.628 3.874 10.35 9.101 11.647Z" class></path>
                        </svg> </a>
                <?php } ?>
                <?php if ($x_link != "") { ?>
                    <a class="inline-flex h-10 w-10 items-center justify-center gap-x-2 rounded-lg border border-transparent text-sm font-bold text-neutral-700 outline-none ring-zinc-500 hover:bg-neutral-500/10 focus:outline-none focus-visible:ring focus-visible:ring-zinc-500 dark:ring-zinc-200 dark:hover:bg-neutral-50/10" href="<?= $x_link ?>" target="_blank" rel="noopener noreferrer"><svg class="h-4 w-4 flex-shrink-0 fill-current text-neutral-700 dark:text-neutral-400" viewBox="0 0 24 24" fill="currentColor">
                            <title>Twitter</title>
                            <path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z" class></path>
                        </svg> </a>
                <?php } ?>
                <?php if ($instagram_link != "") { ?>
                    <a class="inline-flex h-10 w-10 items-center justify-center gap-x-2 rounded-lg border border-transparent text-sm font-bold text-neutral-700 outline-none ring-zinc-500 hover:bg-neutral-500/10 focus:outline-none focus-visible:ring focus-visible:ring-zinc-500 dark:ring-zinc-200 dark:hover:bg-neutral-50/10" href="<?= $instagram_link ?>" target="_blank" rel="noopener noreferrer">

                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" class="h-4 w-10 flex-shrink-0 fill-current text-neutral-700 dark:text-neutral-400" viewBox="0 0 64 64">
                            <title>Instagram</title>
                            <path d="M 31.820312 12 C 13.439312 12 12 13.439312 12 31.820312 L 12 32.179688 C 12 50.560688 13.439312 52 31.820312 52 L 32.179688 52 C 50.560688 52 52 50.560688 52 32.179688 L 52 32 C 52 13.452 50.548 12 32 12 L 31.820312 12 z M 28 16 L 36 16 C 47.129 16 48 16.871 48 28 L 48 36 C 48 47.129 47.129 48 36 48 L 28 48 C 16.871 48 16 47.129 16 36 L 16 28 C 16 16.871 16.871 16 28 16 z M 41.994141 20 C 40.889141 20.003 39.997 20.900859 40 22.005859 C 40.003 23.110859 40.900859 24.003 42.005859 24 C 43.110859 23.997 44.003 23.099141 44 21.994141 C 43.997 20.889141 43.099141 19.997 41.994141 20 z M 31.976562 22 C 26.454563 22.013 21.987 26.501437 22 32.023438 C 22.013 37.545437 26.501437 42.013 32.023438 42 C 37.545437 41.987 42.013 37.498562 42 31.976562 C 41.987 26.454563 37.498562 21.987 31.976562 22 z M 31.986328 26 C 35.299328 25.992 37.992 28.673328 38 31.986328 C 38.007 35.299328 35.326672 37.992 32.013672 38 C 28.700672 38.008 26.008 35.327672 26 32.013672 C 25.992 28.700672 28.673328 26.008 31.986328 26 z"></path>
                        </svg>
                    </a>
                <?php } ?>
                <?php if ($tiktok_link != "") { ?>
                    <a class="inline-flex h-10 w-10 items-center justify-center gap-x-2 rounded-lg border border-transparent text-sm font-bold text-neutral-700 outline-none ring-zinc-500 hover:bg-neutral-500/10 focus:outline-none focus-visible:ring focus-visible:ring-zinc-500 dark:ring-zinc-200 dark:hover:bg-neutral-50/10" href="<?= $tiktok_link ?>" target="_blank" rel="noopener noreferrer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0 fill-current text-neutral-700 dark:text-neutral-400" x="0px" y="0px" width="100" height="100" viewBox="0 0 50 50">
                            <title>TikTok</title>
                            <path d="M41,4H9C6.243,4,4,6.243,4,9v32c0,2.757,2.243,5,5,5h32c2.757,0,5-2.243,5-5V9C46,6.243,43.757,4,41,4z M37.006,22.323 c-0.227,0.021-0.457,0.035-0.69,0.035c-2.623,0-4.928-1.349-6.269-3.388c0,5.349,0,11.435,0,11.537c0,4.709-3.818,8.527-8.527,8.527 s-8.527-3.818-8.527-8.527s3.818-8.527,8.527-8.527c0.178,0,0.352,0.016,0.527,0.027v4.202c-0.175-0.021-0.347-0.053-0.527-0.053 c-2.404,0-4.352,1.948-4.352,4.352s1.948,4.352,4.352,4.352s4.527-1.894,4.527-4.298c0-0.095,0.042-19.594,0.042-19.594h4.016 c0.378,3.591,3.277,6.425,6.901,6.685V22.323z"></path>
                        </svg>
                    </a>
                <?php } ?>
            </div>
        </div>
        <script type="module">
            const e = new Date().getFullYear(),
                t = document.getElementById("current-year");
            t.innerText = e.toString();
        </script>
    </div>
    <?php 
    if($d->get_settings("live_chat_widget")){
        echo htmlspecialchars_decode($d->get_settings("live_chat_widget"));
    }
    ?>
</footer>