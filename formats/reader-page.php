<?php
$readerName = trim(get_the_title());

//Get all posts metadata
$args = array(
  'post_status' => 'publish',
  'posts_per_page' => -1,
  'category_name' => 'episodes',
  'orderby' => 'date',
  'order' => 'DESC'
);

$posts = get_posts($args);
wp_reset_postdata();

//Compile the results
$appearsIn = generateCreditList(["ecinfo_readers","ecinfo_guests","ecinfo_host"], $readerName, $posts);
$contributedTo = generateCreditList(["ecinfo_cover_artist","ecinfo_editor"], $readerName, $posts);
$docsProvided = generateCreditList(["ecinfo_provider"], $readerName, $posts);

?>

<section class="col-12 col-md-10 mx-auto">
  <h1 class="page-title"><?= $readerName; ?></h1>
  <div class="reader-profile single clearfix">
    <div class="reader-image float-md-right">
      <?= the_post_thumbnail('full'); ?>
    </div>
    <div class="reader-info">
      <?= get_the_content() === "" ? generatePlaceholderBio() : the_content() ?>
    </div>
    <div class="reader-credits">
      <?php
      if (count($appearsIn) > 0) {
        echo "<h3>$readerName has appeared in:</h3>" . buildCredits($appearsIn);
      }

      if (count($contributedTo) > 0) {
        echo "<h3>$readerName has contributed to:</h3>" . buildCredits($contributedTo);
      }
      if (count($docsProvided) > 0) {
        echo "<h3>Documents provided:</h3>" . buildCredits($docsProvided);
      }
      ?>
    </div>
  </div>
</section>