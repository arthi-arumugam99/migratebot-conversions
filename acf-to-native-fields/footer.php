<!-- FOOTER  -->
<footer>
   the footer
</footer>

<!-- .JS includes -->
<script type="text/javascript">
  var bloginfo = {
    url: '<?php bloginfo('url'); ?>',
    template_url: '<?php bloginfo('template_url'); ?>'
  };
</script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/public/js/libraries.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/public/js/helper.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/public/js/app.min.js"></script>

<?php
/* Always have wp_footer() just before the closing </body>
 * tag of your theme, or you will break many plugins, which
 * generally use this hook to reference JavaScript files.
 */

wp_footer();
?>
</body>
</html>
