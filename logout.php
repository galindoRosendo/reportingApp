<?php
include("php/com/header.php");
 ?>

<?php
unset($_SESSION["user"]);
unset($_SESSION["email"]);
header("Location: index.php");
 ?>

 <?php
include("php/com/footer.php");
  ?>
