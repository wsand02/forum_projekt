<?php
require_once("common.php");

$c_id = intval($_GET["id"]);
if (empty($c_id)) {
  header("Location: index.php");
  die();
}

$result = $db->get_category($c_id);

if ($result->num_rows > 0) {
  $category = $result->fetch_assoc();
} else {
  http_response_code(404);
  include("404.php");
  die();
}

$category_name = $category["namn"];
$category_description = $category["beskrivning"];

$pageTitle = $category_name;

require_once("inc/header.php");

?>
<h1><?php echo htmlspecialchars($category_name) ?></h1>
<p><?php echo htmlspecialchars($category_description) ?></p>
<hr>

<?php
$posts_results = $db->get_posts_by_category($c_id);
if ($posts_results->num_rows > 0) {
?>
  <div class="list-group">
    <?php
    while ($row = $posts_results->fetch_assoc()) {
    ?>
      <?php
      $user_result = $db->get_user(intval($row["skapare"]));
      $user = $user_result->fetch_assoc();
      $pfp_result = $db->get_image(intval($user["profilbild"]));
      if ($pfp_result->num_rows > 0) {
        $pfp_raw = $pfp_result->fetch_assoc();
        $pfp = $pfp_raw["bildadress"];
      } else {
        $pfp = "media/pfp/notfound.jpg";
      }

      ?>
      <a href="post.php?id=<?php echo ($row['id']) ?>" class="list-group-item list-group-item-action">
        <div class="d-flex w-100 justify-content-between">
          <h5 class="mb-1"><?php echo htmlspecialchars($row["titel"]) ?></h5>
          <small></small>
        </div>
        <img class="rounded float-end pfp" src="<?php echo $pfp ?>" alt="">
        <p class="mb-1"><?php echo (substr(htmlspecialchars($row['innehåll']), 0, 50)) ?>...</p>
        <small>Skapades av <?php echo htmlspecialchars($user["användarnamn"]) ?> | <?php echo $row["skapades"] ?></small>
      </a>
    <?php } ?>
  </div>
<?php } else {
  echo "<p>Inga inlägg funna.</p>";
} ?>



<?php require_once("inc/footer.php"); ?>