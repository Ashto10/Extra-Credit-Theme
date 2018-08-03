<?php
/*
    FORMAT THE WORDPRESS CONTENT INTO THE PROPER ORDER
    */

/*
    Properly load the page content
    */
$content = get_the_content();
$content = apply_filters('the_content',$content);

/*
    First, seperate the podcast player content from the rest of the episode documentation
    */
$content = explode('<div class="powerpress_player"',$content);
$showNotes = $content[0];
$audioPlayer = '<div class="powerpress_player"'.$content[1];

/*
    Look for an <hr> tag in the content. If one exists, that means that post-episode content exists. First, clean up the hr tag itself to make it easier to parse.
    */
str_replace(array("<hr>","<hr/>","<hr / >"),"<hr />",$showNotes);
/*
    Now seperate the post-episode content from the show notes
    */
$temp = explode("<hr />",$showNotes);
/*
    Doublecheck to make sure that there actually is post-episode content, and not that someone hit return or space or something after an <hr> tag
    */            
$blankTest = trim(strip_tags($temp[1]), "\t\n\r\0\x0B\xC2\xA0");
$blankTest = str_replace("&nbsp;","",$blankTest);
if ($blankTest != '') {
  /*
        Reassign $shownotes so it doesn't duplicate content
        */
  $showNotes = $temp[0];
  $additionalNotes = "<h3>Additional Fun</h3>".$temp[1];
}
?>

<div class="col-12 col-md-10 mx-auto single">
  <div class="single-header">
    <h2 class="page-title"><?php the_title();?></h2>
    <div class="thumbnail-container">
      <?php the_post_thumbnail(); ?>
    </div>
    <p>
      <span>Episode <?= get_post_meta(get_the_ID(), 'ecinfo_epnum', true);?></span> &mdash; <span>Released <?php the_date('M j Y');?></span>
    </p>
  </div>

  <div class="main-info">
    <?= $showNotes;?>

    <p>
      <?php
      $host = get_reader("host","");
      if ($host != "") {
        echo "Hosted by $host ";
      }
      ?>
      <?= getCastList(); ?>
    </p>

    <p>
      Edited by <?= get_reader("editor",""); ?>
      <br />
      <?php
      $provider = get_readers("provider","",false);
      if ($provider != "") {
        echo "Content provided by $provider<br />";
      }

      ?>
      Cover art by <?= get_reader("cover_artist",""); ?>
    </p>

    <span>Subject featured</span>
    <ul><?= getInfoList("subjects"); ?></ul>
    <span>Music used</span>
    <ul><?= getInfoList("music"); ?></ul>

    <?php
    $doc = get_post_meta( get_the_ID(), 'ecinfo_doc', true );
    if ($doc != "") {
      echo "<a href='$doc'>Read the doc</a>";
    }
    ?>

    <div class="powerpress-player"><?= $audioPlayer;?></div>

    <div class="tags-container">
      <?php the_tags("","");?>
    </div>
  </div>
  <?php if($additionalNotes): ?>
  <div class="additional-info">
    <?= $additionalNotes; ?>
  </div>
  <?php endif ?>
</div>