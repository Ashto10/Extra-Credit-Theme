<?php
$content = apply_filters('the_content',get_the_content());

$content = explode("<p>[post-show-notes]</p>",$content);
$showNotes = $content[0];
$additionalNotes = $content[1];

$rp = getReaderPages();

?>

<div class="col-12 col-md-10 mx-auto single">
  <div class="single-header">
    <h2 class="page-title"><?php the_title(); ?></h2>
    <div class="thumbnail-container"><?php the_post_thumbnail(); ?></div>
    <p>
      <span>Episode <?= get_post_meta(get_the_ID(), 'ecinfo_epnum', true);?></span> &mdash; <span>Released <?php the_date('M j Y');?></span>
    </p>
  </div>

  <div class="main-info">
    <?= $showNotes;?>

    <p>
      <?php
      $host = formatCredits((getListFromCategory('host', $rp)));
      if ($host) { echo "Hosted by $host<br/>"; }

      $guests = getListFromCategory('guests', $rp);
      
      $readers = formatCredits((getListFromCategory('readers', $rp)), count($guests) === 0);
      $guests = formatCredits($guests);
      
      if ($readers) { echo "With $readers"; }
      if ($guests) { echo ", and featuring $guests"; }
      ?>
    </p>

    <p>
      <?php
      $editor = formatCredits((getListFromCategory('editor', $rp)));
      if ($editor) { echo "Edited by $editor<br />"; }

      $provider = formatCredits((getListFromCategory('provider', $rp)));
      if ($provider) { echo "Content provided by $provider<br />"; }

      $cover_artist = formatCredits((getListFromCategory('cover_artist', $rp)));
      if ($cover_artist) { echo "Cover art by $cover_artist<br />"; }
      ?>
    </p>

    <div>
      <?php
      $subjects = getListFromCategory('subjects');
      if ($subjects): ?>
      <p class="list-title">Subject featured</p>
      <ul>
        <?php
        foreach($subjects as $subject) {
          echo "<li>$subject</li>";
        }
        ?>
      </ul>
      <?php endif; ?>
    </div>

    <div>
      <?php
      $songs = getListFromCategory('music');
      if ($songs): ?>
      <p class="list-title">Music used</p>
      <ul>
        <?php
        foreach($songs as $song) {
          if (strpos($song, '[') !== false) {
            $song = explode('[', $song);
            $title = $song[0];
            $artist = str_replace(']','',$song[1]);
            echo "<li>$title by $artist</li>";
          } else {
            echo "<li>$song</li>";
          }
        }
        ?>
      </ul>
      <?php endif; ?>
    </div>

    <div>
      <?php
      $doc = get_post_meta( get_the_ID(), 'ecinfo_doc', true );
      if ($doc) {
        echo "<a href='$doc'>Read the doc</a>";
      }
      ?>
    </div>

    <div class="powerpress-player"><?= do_shortcode('[powerpress]');?></div>

    <div class="tags-container">
      <?php the_tags("","");?>
    </div>
  </div>
  <?php if($additionalNotes): ?>
  <div class="additional-info">
    <?= $additionalNotes; ?>
  </div>
  <?php endif; ?>
</div>