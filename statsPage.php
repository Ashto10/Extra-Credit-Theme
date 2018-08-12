<?php
/*
 * Template Name: Stats Page
 * Description: 
 */

get_header();

$args = array(
  'post_status' => 'publish',
  'posts_per_page' => -1,
  'category_name' => 'episodes',
  'orderby' => 'date',
  'order' => 'DESC'
);
$posts = get_posts($args);
wp_reset_postdata();

$rp = getReaderPages();
$readerList = [];

foreach($posts as $p) {
  $readers = str_getcsv(get_post_meta($p->ID,"ecinfo_readers",true),",",'"');

  foreach($readers as $reader) {
    $cleanedName = specialCases(strToLower(trim($reader)));
    if (array_key_exists($cleanedName, $readerList)) {
      $readerList[$cleanedName]['count'] += 1;
    } else {
      $readerList[$cleanedName] = array(
        'name' => $reader,
        'link' => isset($rp[$cleanedName]) ? $rp[$cleanedName] : null,
        'count' => 1
      );
    }
  }
}

ksort($readerList);

?>

<main class="container">
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <td><strong>Reader</strong></td>
          <td><strong>Times Recorded</strong></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($readerList as $reader): ?>
        <tr>
          <td>
            <?= $reader['link'] ? "<a href={$reader['link']}>{$reader['name']}</a>" : "{$reader['name']}"; ?>
          </td>
          <td><?= $reader['count']; ?></td>
        </tr>
        <?php endforeach?>
      </tbody>
    </table>

  </div>
</main>

<?php get_footer(); ?>