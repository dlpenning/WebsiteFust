<?php wp_footer() ?>
        <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js"
            integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc"
            crossorigin="anonymous"></script>
        <script defer src="<?= get_template_directory_uri() ?>/main.js"></script>
    </div>
    <footer class="app-footer">
        <div class="inner">
            <p>&copy; FUST, <?= date("Y"); ?>. All rights reserved.</p>
            <?php include 'socials.php'; ?>
        </div>
    </footer>
</body>
</html>