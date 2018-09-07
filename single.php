<?php

global $post;
setup_postdata( $post );

$GLOBALS['twitterMetaTags'] = array(
  'card' => 'player',
  'title' => get_the_title(),
  'site' => '@snakesinthebpit',
  'description' => get_the_excerpt(),
  'image' => get_the_post_thumbnail_url(),
  'image:alt' => 'episode art for ' . get_the_title()
);

$GLOBALS['ogMetaTags'] = array(
  'title' => get_the_title(),
  'type' => 'music.song',
  'image' => get_the_post_thumbnail_url(),
  'image:width' => '600px',
  'image:height' => '600px',
  'url' => get_permalink(),
  'description' => get_the_excerpt()
);

$audio = get_post_meta(get_the_ID(), 'enclosure' . $category, true);
if ($audio) {
  // Grab audio link from PowerPress's enclosure
  preg_match('/^.+.mp3/', $audio, $match);
  $GLOBALS['twitterMetaTags']['player'] = $match[0];
  $GLOBALS['twitterMetaTags']['player:width'] = '450';
  $GLOBALS['twitterMetaTags']['player:height'] = '100';
  
  $GLOBALS['ogMetaTags']['audio'] = $match[0];
  $GLOBALS['ogMetaTags']['audio:secure_url'] = $match[0];
  $GLOBALS['ogMetaTags']['audio:type'] = 'audio/mpeg';
}

get_header();
?>

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