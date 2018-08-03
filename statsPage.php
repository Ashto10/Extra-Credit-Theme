<?php get_header();
/*
 * Template Name: Stats Page
 * Description: 
 */
?>

<main class="container">
  <div class="table-responsive">
    <?php
    //Get all posts metadata
    $args = array(
      'post_status' => 'publish',
      'posts_per_page' => -1,
      'category_name' => 'episodes',
      'orderby' => 'date',
      'order' => 'DESC'
    );

    $posts = get_posts($args);

    $readerList = array();

    foreach($posts as $p) {
      $readers = get_post_meta($p->ID,"ecinfo_readers",true);
      $readers = str_getcsv($readers,",",'"');

      foreach($readers as $reader) {
        $reader = trim($reader);
        $reader = SpecialCases($reader,null);
        if (array_key_exists($reader, $readerList)) {
          $readerList[$reader] += 1;
        } else {
          $readerList[$reader] = 1;
        }
      }

    }

    ksort($readerList);

    wp_reset_postdata();
    ?>

    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <td><strong>Reader</strong></td>
          <td><strong>Times Recorded</strong></td>
        </tr>
      </thead>
      <tbody>
        <?php

        foreach ($readerList as $key => $value) {
          $linkToPage = getBio($key);

          if ($linkToPage) {
            echo "<tr><td><a href=".getBio($key).">$key</a></td><td>$value</td></tr>";  
          } else {
            echo "<tr><td>$key</td><td>$value</td></tr>"; 
          }
        }
        ?>
      </tbody>
    </table>

  </div>
</main>
<?php get_footer(); ?>