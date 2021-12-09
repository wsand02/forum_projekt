<?php

require_once("common.php");

if (!$logged_in) {
  header("Location: login.php");
  die();
}

// https://stackoverflow.com/questions/9315461/how-can-i-catch-this-error-post-content-length
if (isset($_SERVER["CONTENT_LENGTH"])) {
  if ($_SERVER["CONTENT_LENGTH"] > ((int)ini_get('post_max_size') * 1024 * 1024)) {
    $_SESSION["pf_error"] = "Filen du försökte ladda upp var ALLDELES FÖR STOR!";
    header("Location: new_post.php");
    die();
  }
}

$title = $_POST["title"];
$category = $_POST["category"];
$contents = $_POST["contents"];
$image = $_FILES["image"];

$failed = false;
$error = "";

$will_upload = false;
$image_filext = "";
$image_id = 0;

if (empty($title)) {
  $error = "Du måste ha en titel.";
  $failed = true;
}

if (empty($category)) {
  $error = "Du måste välja en kategori.";
  $failed = true;
}

if (empty($contents)) {
  $error = "Du måste ha innehåll i ditt inlägg.";
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


if (strlen($title) > 100) {
  $error = "Din titel får inte vara längre än 100 karaktärer.";
  $failed = true;
}

if (strlen($contents) > 1000) {
  $error = "Ditt innehåll får inte vara längre än 1000 karaktärer.";
  $failed = true;
}

if ($failed) {
  $_SESSION["pf_error"] = $error;
  $_SESSION["pf_title"] = $title;
  $_SESSION["pf_contents"] = $contents;
  header("Location: new_post.php?cid=$category");
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

$new_id = $db->create_post($title, $contents, $_SESSION["uid"], $category, $image_id);
header("Location: post.php?id=$new_id");
die();