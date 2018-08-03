<?php get_header();?>

<main class="container-fluid">
  <div class="row">
    <?php if ( have_posts() ) {
  while ( have_posts() ) : the_post();
  get_template_part( 'formats/episode-index', get_post_format() );
  endwhile; ?>

    <nav class="col-xs-12">

    </nav>
    <?php } ?>
  </div>
</main>
<ul class="pager d-flex">
  <li class="p-2 mx-auto"><?php next_posts_link( 'Prev' ); ?></li>
  <li class="p-2 mx-auto"><?php previous_posts_link( 'Next' ); ?></li>
</ul>

<?php get_footer(); ?>