<?php

require_once("common.php");

if (!$logged_in) {
  header("Location: login.php");
  die();
}

$result = $db->get_user($_SESSION["uid"]);
$user = $result->fetch_assoc();

echo "Logged in " . $user["användarnamn"];
