<?php
require_once("common.php");

$pageTitle = "Kategorier";

require_once("inc/header.php");

?>

<h1>Kategorier</h1>
<hr>


<?php

$categories = $db->get_categories();

if ($categories->num_rows > 0) {
?>
  <div class="list-group">
    <?php
    while ($row = $categories->fetch_assoc()) {
    ?>
      <a href="category.php?id=<?php echo $row["id"] ?>" class="list-group-item d-flex justify-content-between align-items-start">
        <div class="ms-2 me-auto">
          <div class="fw-bold"><?php echo htmlspecialchars($row["namn"]); ?></div>
          <?php echo htmlspecialchars($row["beskrivning"]) ?>
        </div>
        <span class="badge bg-primary rounded-pill"></span>
      </a>
    <?php
    }
    ?>
  </div>
<?php
} else {
  echo "<p>Inga kategorier funna.</p>";
}
?>

<?php require_once("inc/footer.php"); ?>