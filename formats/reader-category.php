<section class="col-10 col-sm-6 col-md-4 col-xl-3 mx-auto post-container-wrapper">
  <a href="<?php the_permalink() ?>">
    <div class="reader post-container">
    <div class="post-title-container">
      <span class="post-title"><?php the_title(); ?></span>
    </div>
    <div class="thumbnail-container squareImg">
      <?php the_post_thumbnail('full'); ?>
    </div>
    </div>
  </a>
</section>