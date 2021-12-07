<?php
require_once("common.php");

if ($logged_in) {
  header("Location: index.php");
  die();
}

$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];
$passwordconfirm = $_POST["passwordconfirm"];

$failed = false;
$error = "";
// Kommer validera det som användaren har inmatat.

// De följande är bara ifall användaren lyckas kringå de inbyggda funktionerna för
// required som existerar inom html.
if (empty($username)) {
  $error = "Du måste ha ett användarnamn.";
  $failed = true;
}

if (empty($email)) {
  $error = "Du måste ha en mejladress.";
  $failed = true;
}

if (empty($password)) {
  $error = "Du måste ha ett lösenord.";
  $failed = true;
}

if (empty($passwordconfirm)) {
  $error = "Du måste bekräfta ditt lösenord.";
  $failed = true;
}

if ($password != $passwordconfirm) {
  $error = "Dina lösenord matchade inte.";
  $failed = true;
}

$user_with_that_username = $db->get_user_by_username($username);
if ($user_with_that_username->num_rows > 0) {
  $error = "Det där användarnamnet används redan.";
  $failed = true;
}

$user_with_that_email = $db->get_user_by_email($email);
if ($user_with_that_email->num_rows > 0) {
  $error = "Den där mejladressen används redan.";
  $failed = true;
}

if ($failed) {
  $_SESSION["rf_username"] = $username;
  $_SESSION["rf_email"] = $email;
  $_SESSION["rf_error"] = $error;
  header("Location: register.php");
  die();
}

$passwordhash = password_hash($password, PASSWORD_DEFAULT);

$new_id = $db->create_user($username, $email, $passwordhash);

$_SESSION["uid"] = $new_id;
header("Location: profile_settings.php");