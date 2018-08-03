<?php get_header();
/*
 * Template Name: Readers Page
 * Template Post Type: page
 */
?>
<main class="container">
  <div class="row">
    <?php 
    if ( have_posts() ) : while ( have_posts() ) : the_post();
    get_template_part( 'formats/reader-page', get_post_format() );

    endwhile; endif; 
    ?>
  </div>
</main>
<?php get_footer(); ?>