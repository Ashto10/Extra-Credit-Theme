<?php get_header();?>

<main class="container">

  <?php
  $wpb_all_query = new WP_Query(array('post_type'=>'page', 'post_status'=>'publish', 'posts_per_page'=>-1,'category_name' => 'regulars'));
  ?>

  <section>
    <h2 class="page-title">Snakes in the Ball Pit</h2>
    <div class="row">
      <?php if ( $wpb_all_query->have_posts() ) : ?>
      <?php while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post();
      get_template_part( 'formats/reader-category', get_post_format() );
      endwhile; ?>
      <?php endif; ?>

      <?php wp_reset_postdata(); ?>
      <?php 
      $wpb_all_query = new WP_Query(array('post_type'=>'page', 'post_status'=>'publish', 'posts_per_page'=>-1,'category_name' => 'supporting'));
      ?>

      <?php if ( $wpb_all_query->have_posts() ) : ?>
      <?php while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post();
      get_template_part( 'formats/reader-category', get_post_format() );
      endwhile; ?>

      <?php endif; ?>

      <?php wp_reset_postdata(); ?>
      <?php 
      $wpb_all_query = new WP_Query(array('post_type'=>'page', 'post_status'=>'publish', 'posts_per_page'=>-1,'category_name' => 'occasional'));
      ?>

      <?php if ( $wpb_all_query->have_posts() ) : ?>
      <?php while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post();
      get_template_part( 'formats/reader-category', get_post_format() );
      endwhile; ?>

      <?php endif; ?>
    </div>
  </section>
  <?php wp_reset_postdata(); ?>

  <?php
  $wpb_all_query = new WP_Query(array('post_type'=>'page', 'post_status'=>'publish', 'posts_per_page'=>-1,'category_name' => 'guests'));
  ?>

  <?php if ( $wpb_all_query->have_posts() ) : ?>

  <section>
    <h2 class="page-title">Special Readers</h2>
    <div class="row">
      <?php while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post();
      get_template_part( 'formats/reader-category', get_post_format() );
      endwhile; ?>
    </div>
  </section>
  <?php wp_reset_postdata(); ?>

  <?php endif; ?>
</main>

<?php get_footer(); ?>