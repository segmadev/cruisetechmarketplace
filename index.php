<?php require_once "content/header.php"; ?>
<script src="scripts/vendor/custom.js"></script>
<script src="scripts/vendor/preline/collapse/index.js"></script>
<script src="scripts/vendor/preline/overlay/index.js"></script>
<main class="astro-ouamjn2i"><astro-banner btnid="dismiss-button">
        <?php // require_once "content/sticky_banner.php"; ?>
    </astro-banner>
    <script
        type="module">class n extends HTMLElement { connectedCallback() { const e = this.getAttribute("btnId"), t = this.querySelector(`#${e}`); t?.addEventListener("click", () => this.remove()) } } customElements.define("astro-banner", n);</script>
  
    <?php
    if(isset($_GET['page']) && ($_GET['page'] == "terms" || $_GET['page'] == "policy")) {
        $page = htmlspecialchars($_GET['page']);
        if(file_exists(("content/$page.php"))) {
            require_once "content/$page.php";
        }
    }else{
    require_once "content/hero.php";
    require_once "content/company.php";
    require_once "content/about.php";
    require_once "content/how_it_works.php";
    require_once "content/action.php";
    }
   
    ?>
</main>
</div>
<?php require_once "content/footer.php"; ?>

<script>(function () { if (!document.body) return; var js = "window['__CF$cv$params']={r:'87b9716eecd776ba',t:'MTcxNDMzMjM3Ny40NjUwMDA='};_cpo=document.createElement('script');_cpo.nonce='',_cpo.src='cdn-cgi/challenge-platform/h/b/scripts/jsd/471dc2adc340/main.js',document.getElementsByTagName('head')[0].appendChild(_cpo);"; var _0xh = document.createElement('iframe'); _0xh.height = 1; _0xh.width = 1; _0xh.style.position = 'absolute'; _0xh.style.top = 0; _0xh.style.left = 0; _0xh.style.border = 'none'; _0xh.style.visibility = 'hidden'; document.body.appendChild(_0xh); function handler() { var _0xi = _0xh.contentDocument || _0xh.contentWindow.document; if (_0xi) { var _0xj = _0xi.createElement('script'); _0xj.innerHTML = js; _0xi.getElementsByTagName('head')[0].appendChild(_0xj); } } if (document.readyState !== 'loading') { handler(); } else if (window.addEventListener) { document.addEventListener('DOMContentLoaded', handler); } else { var prev = document.onreadystatechange || function () { }; document.onreadystatechange = function (e) { prev(e); if (document.readyState !== 'loading') { document.onreadystatechange = prev; handler(); } }; } })();</script>
</body>

</html>