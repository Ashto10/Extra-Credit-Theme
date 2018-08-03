<section class="col-12 col-md-10 mx-auto">
  <h1 class="page-title"><?php $pageTitle = get_the_title(); echo $pageTitle; ?></h1>
  <div class="reader-profile single clearfix">
    <div class="reader-image float-md-right">
      <?php 
      the_post_thumbnail('full'); 
      ?>
    </div>
    <div class="reader-info">
      <?php 

      if (get_the_content() === "") {
        generatePlaceholderBio();
      } else {
        the_content();
      }

      ?>
    </div>
    <div class="reader-credits">
      <?php

      //Get the reader's name ready to compare
      $bio = trim(get_the_title());

      //Get all posts metadata
      $args = array(
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'category_name' => 'episodes',
        'orderby' => 'date',
        'order' => 'DESC'
      );

      $posts = get_posts($args);

      //Compile the results
      $appearsIn = "";
      $contributedTo = "";
      $documentsProvided = "";

      foreach($posts as $p) {
        $readers = get_post_meta($p->ID,"ecinfo_readers",true);
        $readers = str_getcsv($readers,",",'"');
        $guests = get_post_meta($p->ID,"ecinfo_guests",true);
        $guests = str_getcsv($guests,",",'"');
        $hosts = get_post_meta($p->ID,"ecinfo_host",true);
        $hosts = str_getcsv($hosts,",",'"');

        $readers = array_merge($readers,$guests,$hosts);
        foreach($readers as $reader) {
          $reader = SpecialCases($reader,"reader");
          if (trim($reader) == $bio) {
            $appearsIn .= "<a class='credit' href=".get_post_permalink($p).">".get_the_title($p)."</a>";
            break;
          }
        }

        $artists = get_post_meta($p->ID,"ecinfo_cover_artist",true);
        $artists = str_getcsv($artists,",",'"');
        $editors = get_post_meta($p->ID,"ecinfo_editor",true);
        $editors = str_getcsv($editors,",",'"');

        $contributors = array_merge($artists,$editors);
        foreach($contributors as $contributor) {
          $contributor = SpecialCases($contributor,"contributor");
          if (trim($contributor) == $bio) {
            $contributedTo .= "<a class='credit' href=".get_post_permalink($p).">".get_the_title($p)."</a>";
            break;
          }
        }

        $submitters = get_post_meta($p->ID,"ecinfo_provider",true);
        $submitters = str_getcsv($submitters,",",'"');

        foreach($submitters as $submitter) {
          $submitter = SpecialCases($submitter,"submitter");
          if (trim($submitter) == $bio) {
            $documentsProvided .= "<a class='credit' href=".get_post_permalink($p).">".get_the_title($p)."</a>";
            break;
          }
        }
      }
      wp_reset_postdata();


      if ($appearsIn != "") {
        echo "<h3>$bio has appeared in:</h3>$appearsIn";
      }
      if ($contributedTo != "") {
        echo "<h3>$bio has contributed to:</h3>$contributedTo";
      }
      if ($documentsProvided != "") {
        echo "<h3>Documents provided:</h3>$documentsProvided";
      }
      ?>


    </div>
  </div>
</section>