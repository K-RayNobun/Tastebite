</div> <!-- Close container -->
    <?php include 'includes/search-modal.php'; ?>
    <?php include 'includes/feedback-modal.php'; ?>
    <script src="assets/js/main.js"></script>
    <?php if (isset($extra_js)): ?>
        <?php foreach ($extra_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
