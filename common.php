<?php

require_once("inc/db.php");

$db = new Database;
$db->connect(
  "localhost",
  "root",
  "",
  "forum"
);

session_start();

$logged_in = false;

if (isset($_SESSION["uid"])) {
  $logged_in = true;
}
