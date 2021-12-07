<?php 
require_once("common.php");

if ($logged_in) {
  header("Location: index.php");
  die();
}

$username_or_email = $_POST["username"];
$password = $_POST["password"];

$success = false;

$failed = false;
$error = "";

if (empty($username_or_email)) {
  $error = "Du måste ange ett användarnamn eller en mejladress.";
  $failed = true;
}

if (empty($password)) {
  $error = "Du måste ange ett lösenord.";
  $failed = true;
}

$result = $db->get_user_by_email_or_username($username_or_email);
if ($result->num_rows > 0) {
  $user = $result->fetch_assoc();
  if (password_verify($password, $user["lösenordhash"])) {
    $success = true;
  } else {
    $error = "Fel användarnamn eller lösenord.";
    $failed = true;
  }
} else {
  $error = "Fel användarnamn eller lösenord.";
  $failed = true;
}

if ($failed) {
  $_SESSION["lf_error"] = $error;
  header("Location: login.php");
  die();
}

if ($success) {
  $_SESSION["uid"] = $user["id"];
  header("Location: profile_settings.php");
} else {
  // Borde aldrig komma hit men vem vet
  header("Location: index.php");
  die();
}