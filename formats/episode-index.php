<section class="col-10 col-sm-6 col-md-4 col-xl-3 mx-auto post-container-wrapper">
  <div class="post-container post-container-index">
    <a href="<?php the_permalink(); ?>">
      <div class="post-title-container">
        <span class="post-title"><?php the_title(); ?></span>
      </div>
      <div class="thumbnail-container squareImg">
        <?php the_post_thumbnail('medium'); ?>
      </div>
    </a>
    <div class="post-info">
      <span class="post-date"><?php the_date(); ?></span>
      <a class="comments-link" href="<?php the_permalink(); ?>#disqus_thread">
        <?php
        printf( _nx( 'One Comment', '%1$s Comments', get_comments_number(), 'comments title', 'textdomain' ), number_format_i18n(get_comments_number() ) );
        ?>
      </a>
      <div class="post-excerpt">
        <?php the_excerpt(); ?>
      </div>
    </div>

    <div class="episode-num">
      <?php
      $epNum = get_post_meta(get_the_ID(), 'ecinfo_epnum', true);
      if($epNum !== '') {
        echo "#".str_pad($epNum, 3, '0', STR_PAD_LEFT);
      }
      ?>
    </div>
  </div>
</section>