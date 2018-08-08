<?php get_header();
/*
 * Template Name: Upload Page
 * Description: 
 */

$target_dir = getcwd() . "/episodes/";
$file_name = basename($_FILES["fileToUpload"]["name"]);
$target_file = $target_dir . $file_name;
$uploadErrors = [];
$uploadFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

if(isset($_POST["submit"])) {

  if($uploadFileType != "mp3") {
    $uploadErrors[] = "Only MP3 files are allowed";
  }

  if ($_FILES["fileToUpload"]["size"] > 200000000) {
    $uploadErrors[] = "Your file is too large! Please limit the filesize to under 200MB";
  }

  if (file_exists($target_file) && !isset($_POST['overwrite'])) {
    $uploadErrors[] = "This file already exists. If this is your intent, make sure to check off the \"overwrite\" option.";
  }

  if (count($uploadErrors) === 0) {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      echo "<div class='alert alert-success'><p>The file ". $file_name . " has been uploaded.</p></div>";
    } else {
      echo "<div class='alert alert-danger'><p>Sorry, something has gone wrong. Please try again later, or contact an admin if the issue persists.</p></div>";
    }
  } else {
    echo "<div class='alert alert-danger'><p>Sorry, your file was not uploaded. Please resolve the following errors before trying again:</p><ul>";
    foreach($uploadErrors as $error) {
      echo "<li>$error</li>";
    }
    echo "</ul></div>";
  }
}

?>

<main class="container">
  <form action="<?= get_permalink() ?>" method="post" enctype="multipart/form-data" class="mt-4">
    <div class="form-group">
      <label for="fileToUpload">Select file to upload:</label>
      <input type="file" class="form-control-file" name="fileToUpload" id="fileToUpload">
    </div>
    <button class="btn btn-primary mb-2" name="submit">Upload Episode</button>
    <div class="form-check">
      <input class="form-check-input" id="overwrite" type="checkbox" name="overwrite" value="on">
      <label for="overwrite">Overwrite file</label>
    </div>
  </form>
</main>

<?php get_footer(); ?>