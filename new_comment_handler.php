<?php

require_once("common.php");

if (!$logged_in) {
  header("Location: login.php");
  die();
}

// https://stackoverflow.com/questions/9315461/how-can-i-catch-this-error-post-content-length
if (isset($_SERVER["CONTENT_LENGTH"])) {
  if ($_SERVER["CONTENT_LENGTH"] > ((int)ini_get('post_max_size') * 1024 * 1024)) {
    $_SESSION["cf_error"] = "Filen du försökte ladda upp var ALLDELES FÖR STOR!";
    if (isset($_SESSION["cf_id"])) {
      header("Location: new_comment.php?id=" . $_SESSION["cf_id"]);
      unset($_SESSION["cf_id"]); 
    } else {
      header("Location: new_comment.php");
    }
    die();
  }
}

$post = $_POST["post"];
$contents = $_POST["contents"];
$image = $_FILES["image"];

$failed = false;
$error = "";

$will_upload = false;
$image_filext = "";
$image_id = 0;

if (empty($post)) {
  $error = "Ingen post vald. Du borde inte vara här.";
  $failed = true;
}

if (empty($contents)) {
  $error = "Du måste ha innehåll i din kommentar.";
  $failed = true;
}

if ($image["name"] != "") {
  $image_mime_type = mime_content_type($image["tmp_name"]);
  if ($image_mime_type === "image/png") {
    $image_filext = ".png";
    $will_upload = true;
  } elseif ($image_mime_type === "image/jpeg") {
    $image_filext = ".jpeg";
    $will_upload = true;
  } elseif ($image_mime_type === "image/gif") {
    $image_filext = ".gif";
    $will_upload = true;
  } else {
    $error = "Din bild måste vara av typen: jpeg/jpg, png eller gif.";
    $failed = true;
  }
}

if (strlen($contents) > 1000) {
  $error = "Ditt innehåll får inte vara längre än 1000 karaktärer.";
  $failed = true;
}

if ($failed) {
  $_SESSION["cf_error"] = $error;
  $_SESSION["cf_contents"] = $contents;
  header("Location: new_comment.php?id=$post");
  die();
}

if ($will_upload) {
  $new_filename = uniqid() . $image_filext;
  $upload_dir = "media/uploads/";
  $target_file = $upload_dir . basename($new_filename);
  if (move_uploaded_file($image["tmp_name"], $target_file)) {
    $image_id = $db->create_image($target_file);
  };
}

$new_id = $db->create_comment($contents, $_SESSION["uid"], $post, $image_id);
header("Location: post.php?id=$post");
unset($_SESSION["cf_id"]); 
die();