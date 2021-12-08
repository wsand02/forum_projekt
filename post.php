<?php
require_once("common.php");

$p_id = intval($_GET["id"]);
if (empty($p_id)) {
  header("Location: index.php");
  die();
}

$result = $db->get_post($p_id);

if ($result->num_rows > 0) {
  $post = $result->fetch_assoc();
} else {
  http_response_code(404);
  include("404.php");
  die();
}

$post_image_id = $post["bild"];
$post_image = "";

if (isset($post_image_id)) {
  $image_result = $db->get_image($post_image_id);
  $image_raw = $image_result->fetch_assoc();
  $post_image = $image_raw["bildadress"];
}

$creator_result = $db->get_user($post["skapare"]);
$creator = $creator_result->fetch_assoc();
$pfp_result = $db->get_image(intval($creator["profilbild"]));
if ($pfp_result->num_rows > 0) {
  $pfp_raw = $pfp_result->fetch_assoc();
  $pfp = $pfp_raw["bildadress"];
} else {
  $pfp = "media/pfp/notfound.jpg";
}

$pageTitle = $post["titel"];

require_once("inc/header.php");
?>
<div class="post">
  <div class="d-flex w-100 justify-content-between mb-3">
    <div>
      <h1><?php echo (htmlspecialchars($post["titel"])) ?></h1>
      <p class="text-secondary">Skapades av <?php echo (htmlspecialchars($creator["användarnamn"])); ?> | <?php echo ($post["skapades"]) ?> | <?php echo ($post["ändrades"]) ?></p>
    </div>
    <img class="rounded pfp" src="<?php echo $pfp ?>" alt="">

  </div>

  <?php if ($post_image != "") { ?>
    <img src="<?php echo (htmlspecialchars($post_image)) ?>" class="img-fluid rounded mb-3">
  <?php } ?>
  <p><?php echo (htmlspecialchars($post["innehåll"])) ?></p>
</div>
<hr>
<?php
$comments_result = $db->get_comments_by_post($p_id);
?>
<div class="d-flex w-100 justify-content-between mb-3">
  <h2>Kommentarer</h2>
  <a href="new_comment.php?id=<?php echo $p_id ?>" class="btn btn-primary">Kommentera</a>
</div>
<?php
// blir inte så bra men jag har inte tid
if ($comments_result->num_rows > 0) {
  while ($row = $comments_result->fetch_assoc()) { ?>
    <?php
    $user_result = $db->get_user(intval($row["skapare"]));
    $user = $user_result->fetch_assoc();
    $pfp_result2 = $db->get_image(intval($user["profilbild"]));
    if ($pfp_result2->num_rows > 0) {
      $pfp_raw = $pfp_result2->fetch_assoc();
      $pfp2 = $pfp_raw["bildadress"];
    } else {
      $pfp2 = "media/pfp/notfound.jpg";
    }
    ?>
    <div class="card comment mb-3">
      <img class="card-img-top" height="220px" src="media/pfp/default.jpg" alt="">
      <div class="card-body">
        <p class="card-text"><?php echo (htmlspecialchars($row["innehåll"])) ?></p>
        <p class="card-text text-secondary">Skapades av <?php echo (htmlspecialchars($user["användarnamn"])) ?> | <?php echo (htmlspecialchars($row["skapades"])) ?></p>
        <img class="rounded float-end pfp" src="<?php echo $pfp2 ?>" alt="">
      </div>
    </div>
  <?php } ?>

<?php } else { ?>
  <p>Det finns inga kommentarer på det här inlägget.</p>
<?php } ?>
<?php require_once("inc/footer.php"); ?>