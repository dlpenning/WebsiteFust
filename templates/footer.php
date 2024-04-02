<?php wp_footer() ?>
        <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js"
            integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc"
            crossorigin="anonymous"></script>
        <script defer src="<?= get_template_directory_uri() ?>/main.js"></script>
    </div>
    <footer class="app-footer">
        <div class="app-footer-main">
            <div class="inner">
                <div>
                    <p><h3>Legal<span class="dot">.</span></h3></p>
                    <ul>
                        <li><a href="#">Cookie policy</a></li>
                        <li><a href="#">Privacy policy</a></li>
                        <li><a href="#">Codes of conduct</a></li>
                    </ul>
                </div>
                <div>
                    <p><h3>Socials<span class="dot">.</span></h3></p>
                    <ul>
                        <li><a href="#">Instagram</a></li>
                    </ul>
                </div>
                <div>
                    <p><h3>Become a member<span class="dot">.</span></h3></p>
                    <ul>
                        <li><a href="/become-a-member">Join us now</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="app-footer-sub">
            <div class="inner">
                <p class="footer-container">&copy; F.U.S.T., <?= date("Y"); ?>. All rights reserved.</p>
                <?php include 'socials.php'; ?>
            </div>
        </div>
    </footer>
</body>
</html>