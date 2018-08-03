<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <?php wp_head(); ?>
  </head>
  <body>
    <header>
      <div class="bg-wood"></div>
      <div class="chalkboard-header">	
        <?php
        if(is_home()) {
          echo "<img src='".get_template_directory_uri()."/img/ec_logo_white.png' class='img-responsive mx-auto d-block'>";

        }
        
        create_bootstrap_menu('navigation-menu');
        ?>
        
      </div>
    </header>