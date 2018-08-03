<?php get_header(); ?>

<main class="container">
  <?php 
  if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

  <section class="col-12 single">
    <h1 class="page-title"><?php the_title(); ?></h1>
    <div>
      <?php the_content(); ?>
    </div>
  </section>

  <?php endwhile; endif; 
  ?>
</main>
<?php get_footer(); ?>