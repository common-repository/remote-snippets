<?php
get_header();
?>
<main id="site-content" role="main">
    <?php
    if ( have_posts() ) {
        while ( have_posts() ) {
            ?><h2>RemoteSnippet Preview</h2><?php
            the_post();
            the_content();
        }
    }
    ?>
</main>
<?php get_footer();
