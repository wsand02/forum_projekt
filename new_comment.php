<?php
require_once("common.php");

$pageTitle = "Kommentera";

require_once("inc/header.php");

if (!$logged_in) {
  header("Location: index.php");
  die();
}

$p_id = intval($_GET["id"]);
if (empty($p_id)) {
  header("Location: index.php");
  die();
}

$result = $db->get_post($p_id);

if($result->num_rows > 0) {
  $post = $result->fetch_assoc();
} else {
  header("Location: index.php");
  die();
}
$_SESSION["cf_id"] = $p_id;
?>
<div class="form-post">
<h1>Kommentera</h1><hr>
<?php if (isset($_SESSION["cf_error"])) { ?>
  <div class="alert alert-danger" role="alert">
    <?php echo $_SESSION["cf_error"] ?>
  </div>
<?php } ?>
<form action="new_comment_handler.php" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="post" value="<?php echo $p_id ?>">
  <div class=" mb-3">
    <label for="contents" class="form-label">Innehåll</label>
    <textarea class="form-control" id="contents" name="contents" rows="5" required><?php 
    if(isset($_SESSION["cf_contents"])) {
      echo htmlspecialchars($_SESSION["cf_contents"]);
    } ?></textarea>
  </div>
  <div class="mb-3">
    <label for="formFile" class="form-label">Bild</label>
    <input class="form-control" type="file" name="image" id="image">
  </div>
  <button type="submit" class="btn btn-lg btn-primary">Skapa</button>
</form>
</div>
<?php 
unset($_SESSION["cf_error"]); 
unset($_SESSION["cf_contents"]); 
?>

<?php require_once("inc/footer.php"); ?>