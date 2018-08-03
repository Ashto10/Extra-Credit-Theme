<?php

include 'includes/reader-bio-gen.php';

add_filter('pre_override_comment_data', 'set_comment_data');
function set_comment_data($commentdata){
  $userdata = get_userdata((int)$_POST['comment_author_selection']); 

  $commentdata['user_ID'] = $userdata->ID;
  $commentdata['comment_author'] = $userdata->user_login;
  $commentdata['comment_author_email'] = $userdata->user_email;
  $commentdata['comment_author_url'] = $userdata->user_url;

  return $commentdata;
}


// Get biography page of reader by name
function getBio($name) {
  $args = array(
    'post_type' => 'page'
  );

  $pages = get_pages($args);
  foreach ($pages as $page) {
    $title = trim($page->post_title);
    $name = trim($name);
    if ($title == $name) {
      return get_permalink($page->ID);
    }
  }

  return null;
}

function getCastList() {
  $guestsAreInEpisode = false;

  if (get_readers("guests","",false)) {
    $guestsAreInEpisode = true;
  }

  $output .= get_readers("readers","with ", $guestsAreInEpisode);
  if ($guestsAreInEpisode) {
    $output .= get_readers("guests",", and featuring ",false);
  }

  return $output;
}

function get_reader($title, $pre) {
  $reader = get_post_meta(get_the_ID(),'ecinfo_' . $title,true);

  $linkToBio = getBio($reader);
  if($linkToBio) {
    return $pre . "<a href='$linkToBio'>$reader</a>";
  } else {
    return $pre . $reader;
  }
}

function get_readers($title, $pre, $excludeAnd) {
  $output .= $pre;

  $queries = get_post_meta(get_the_ID(), 'ecinfo_' . $title, true);
  $queries = str_getcsv($queries,",",'"');

  for($i = 0; $i < count($queries); $i++) {
    $query = $queries[$i];
    $linkToBio = getBio($query);
    if($linkToBio) {
      $output .= "<a href='$linkToBio'>$query</a>";
    } else {
      $output .= "$query";
    }

    if ($i < count($queries) - 1 && (count($queries) > 2 || $excludeAnd)) {
      $output .= ", ";
    }

    if ($i == count($queries) - 2 && !$excludeAnd) {
      $output .= " and ";
    }
  }

  return $output;
}

function getInfoList($category) {
  $queries = get_post_meta(get_the_ID(), 'ecinfo_' . $category, true);
  $queries =str_getcsv($queries,"\n",'"');
  $output = "";

  foreach ($queries as $query) {
    if(checkIfSite($query)) {
      $query = filter_var($query,FILTER_SANITIZE_URL);
      $output .= "<li><a href='http://www.$query' target='_blank' rel='noreferrer'>$query</a></li>";
    } else {
      $output .= "<li>$query</li>";
    }
  }

  return $output;
}

function checkIfSite($string) {
  $endings = array (
    ".com",
    ".net",
    ".org"
  );

  foreach ($endings as $endsIn) {

    $temp = strlen($string) - strlen($endsIn);
    $result = strpos($string, $endsIn, $temp);

    if($result !== false) {
      return true;
    }
  }
  return false;
}

// Add support Featured Images
add_theme_support( 'post-thumbnails' );

// Add support for WordPress Titles
add_theme_support( 'title-tag' );

// Add support for custom menus
function register_custom_menu() {
  register_nav_menu('navigation-menu',__( 'Navigation Menu' ));
}
add_action( 'init', 'register_custom_menu' );

// Add scripts and stylesheets
function startwordpress_scripts() {

  wp_enqueue_style( 'google-fonts',
                   'https://fonts.googleapis.com/css?family=Oswald|Roboto');

  wp_enqueue_style( 'bootstrap-css',
                   'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css');

  wp_enqueue_style( 'xc-styles',
                   get_template_directory_uri() . '/css/xc-styles.css');

  wp_enqueue_script('popper',
                    'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js',
                    array('jquery'), '1.12.9', true);

  wp_enqueue_script('bootstrap-js',
                    'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js',
                    array('jquery'), '4.0.0', true);

  wp_enqueue_script('xc-scripts',
                    get_template_directory_uri() . '/js/xc_script.js',
                    array('jquery'), '1.0.0', true);
}

add_action( 'wp_enqueue_scripts', 'startwordpress_scripts' );

$prefix = 'ecinfo_';

$meta_boxes = array();

$meta_boxes[] = array(
  'id' => 'meta-box_1',
  'title' => 'Extra Credit episode info',
  'pages' => array('post'), // multiple post types, accept custom post types
  'context' => 'normal',
  'priority' => 'high',
  'fields' => array(
    array(
      'name' => 'Episode Number',
      'desc' => 'The episode release number',
      'id' => $prefix . 'epnum',
      'type' => 'text',
      'std' => '',
      'placeholder' => '#'
    ),
    array(
      'name' => 'Hosted By',
      'desc' => 'Name of the episode\'s host',
      'id' => $prefix . 'host',
      'type' => 'text',
      'std' => '',
      'placeholder' => 'Hosty McHostHost'
    ),
    array(
      'name' => 'Edited By',
      'desc' => 'Name of the episode editor',
      'id' => $prefix . 'editor',
      'type' => 'text',
      'std' => '',
      'placeholder' => 'Edit McEditor'
    ),
    array(
      'name' => 'Cover Creator',
      'desc' => 'Name of artist that made the cover',
      'id' => $prefix . 'cover_artist',
      'type' => 'text',
      'std' => '',
      'placeholder' => 'Artsy McFartsy'
    ),
    array(
      'name' => 'Main Readers',
      'desc' => 'Main cast readers in episode<br/><em>separate with commas</em>',
      'id' => $prefix . 'readers',
      'type' => 'textarea',
      'std' => '',
      'placeholder' => 'name 1,name 2'
    ),
    array(
      'name' => 'Guest Readers',
      'desc' => 'Guest readers in episode<br/><em>separate with commas</em>',
      'id' => $prefix . 'guests',
      'type' => 'textarea',
      'std' => '',
      'placeholder' => 'name 1,name 2'
    ),
    array(
      'name' => 'Subjects',
      'desc' => 'The sites and/or people covered<br/><em>One subject per line</em>',
      'id' => $prefix . 'subjects',
      'type' => 'textarea',
      'std' => '',
      'placeholder' => 'John Doe, Something.com'
    ),
    array(
      'name' => 'Music used',
      'desc' => 'Music used in episode<br /><em>One song per line, preferablly formatted "song name by artist"</em>',
      'id' => $prefix . 'music',
      'type' => 'textarea',
      'std' => '',
      'placeholder' => 'Lady - Garbage Ass'
    ),
    array(
      'name' => 'Doc Link',
      'desc' => 'Link to the google drive doc<br/><em>One doc per line</em>',
      'id' => $prefix . 'doc',
      'type' => 'text',
      'std' => '',
      'placeholder' => 'drive.google.com/etc'
    ),
    array(
      'name' => 'Content Provider',
      'desc' => 'Who submitted the doc.<br/><em>separate with commas</em>',
      'id' => $prefix . 'provider',
      'type' => 'textarea',
      'std' => '',
      'placeholder' => 'name 1,name 2'
    )
  )
);

foreach ($meta_boxes as $meta_box) {
  $my_box = new My_meta_box($meta_box);
}

class My_meta_box {

  protected $_meta_box;

  // create meta box based on given data
  function __construct($meta_box) {
    $this->_meta_box = $meta_box;
    add_action('admin_menu', array(&$this, 'add'));

    add_action('save_post', array(&$this, 'save'));
  }

  /// Add meta box for multiple post types
  function add() {
    foreach ($this->_meta_box['pages'] as $page) {
      add_meta_box($this->_meta_box['id'], $this->_meta_box['title'], array(&$this, 'show'), $page, $this->_meta_box['context'], $this->_meta_box['priority']);
    }
  }

  // Callback function to show fields in meta box
  function show() {
    global $post;

    // Use nonce for verification
    echo '<input type="hidden" name="mytheme_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

    echo '<table class="form-table">';

    foreach ($this->_meta_box['fields'] as $field) {
      // get current post meta data
      $meta = get_post_meta($post->ID, $field['id'], true);

      echo '<tr>',
      '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
      '<td>';
      switch ($field['type']) {
        case 'text':
          echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" placeholder="', $meta ? $meta : $field['placeholder'], '" size="30" style="width:97%" />',
          '<br />', $field['desc'];
          break;
        case 'textarea':
          echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="', '4', '" placeholder="', $meta ? $meta : $field['placeholder'], '" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>',
          '<br />', $field['desc'];
          break;
        case 'select':
          echo '<select name="', $field['id'], '" id="', $field['id'], '">';
          foreach ($field['options'] as $option) {
            echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
          }
          echo '</select>';
          break;
        case 'radio':
          foreach ($field['options'] as $option) {
            echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
          }
          break;
        case 'checkbox':
          echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
          break;
      }
      echo     '<td>',
      '</tr>';
    }

    echo '</table>';
  }

  // Save data from meta box
  function save($post_id) {
    // verify nonce
    if (!wp_verify_nonce($_POST['mytheme_meta_box_nonce'], basename(__FILE__))) {
      return $post_id;
    }

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return $post_id;
    }

    // check permissions
    if ('page' == $_POST['post_type']) {
      if (!current_user_can('edit_page', $post_id)) {
        return $post_id;
      }
    } elseif (!current_user_can('edit_post', $post_id)) {
      return $post_id;
    }

    foreach ($this->_meta_box['fields'] as $field) {
      $old = get_post_meta($post_id, $field['id'], true);
      $new = $_POST[$field['id']];

      if ($new && $new != $old) {
        update_post_meta($post_id, $field['id'], $new);
      } elseif ('' == $new && $old) {
        delete_post_meta($post_id, $field['id'], $old);
      }
    }
  }

}

function create_bootstrap_menu( $theme_location ) {
  if ( ($theme_location) && ($locations = get_nav_menu_locations()) && isset($locations[$theme_location]) ) {

    $menu_list  = '<nav class="navbar navbar-expand-lg navbar-dark">' ."\n";
    $menu_list .= '<a class="navbar-brand" href="' . home_url() . '">' . get_bloginfo( 'name' ) . '</a>';
    $menu_list .= '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bstrap-menu-'.$theme_location.'" aria-controls="bstrap-menu-'.$theme_location.'" aria-expanded="false" aria-label="Toggle navigation">' ."\n";
    $menu_list .= '<span class="navbar-toggler-icon"></span>' ."\n";
    $menu_list .= '</button>' ."\n";

    $menu = get_term( $locations[$theme_location], 'nav_menu' );
    $menu_items = wp_get_nav_menu_items($menu->term_id);

    $menu_list .= '<div class="collapse navbar-collapse" id="bstrap-menu-'.$theme_location.'">' ."\n";
    $menu_list .= '<ul class="navbar-nav ml-auto">' ."\n";

    foreach( $menu_items as $menu_item ) {
      if( $menu_item->menu_item_parent == 0 ) {

        $parent = $menu_item->ID;

        $menu_array = array();
        foreach( $menu_items as $submenu ) {
          if( $submenu->menu_item_parent == $parent ) {
            $bool = true;
            $menu_array[] = '<a class="dropdown-item" href="' . $submenu->url . '">' . $submenu->title . '</a>' ."\n";
          }
        }
        if( $bool == true && count( $menu_array ) > 0 ) {

          $menu_list .= '<li class="nav-item dropdown">' ."\n";
          $menu_list .= '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $menu_item->title . ' <span class="caret"></span></a>' ."\n";

          $menu_list .= '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">' ."\n";
          $menu_list .= implode( "\n", $menu_array );
          $menu_list .= '</div>' ."\n";

        } else {

          $menu_list .= '<li class="nav-item">' ."\n";
          $menu_list .= '<a class="nav-link" href="' . $menu_item->url . '">' . $menu_item->title . '</a>' ."\n";
        }

      }

      // end <li>
      $menu_list .= '</li>' ."\n";
    }

    $menu_list .= '</ul>' ."\n";
    $menu_list .= '</div>' ."\n";
    $menu_list .= '</nav>' ."\n";

  } else {
    $menu_list = '<!-- no menu defined in location "'.$theme_location.'" -->';
  }

  echo $menu_list;
}

function SpecialCases($a, $b) {
  if ($a == "Fruithag" ) {
    return "Positive Stress";
  } else if ($a == "Ashto" && $b == "reader") {
    return "Smoke Alarm";
  } else {
    return $a;
  }
}