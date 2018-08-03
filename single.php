<?php get_header(); ?>

<div class="episode-bg-cover"
     style="background-image: url('<?php
            $thumb_id = get_post_thumbnail_id();
            $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail-size', true);
            echo $thumb_url_array[0];
            ?>')">
</div>
<main class="container">
  <?php 
  if ( have_posts() ) : while ( have_posts() ) : the_post();

  get_template_part( 'formats/episode-single', get_post_format() );
  endwhile; endif; 
  ?>
</main>

<div class="comments-container container-fluid">
  <?php 
  if ( comments_open() || get_comments_number() ) :
  comments_template();
  endif;
  ?>
</div>

<?php get_footer(); ?>