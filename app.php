<?php

session_start();

if (!isset($_SESSION['id'])) {
    
    header('Location: index.php');
    exit();
}
?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Artchive</title>
  <link rel="stylesheet" href="css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="images/logo.png">
  <script src="js/script.js"></script>
</head>

<body>

  <?php
    include('header.php');
  ?>


  <?php
    include('menu_nav.php');
  ?>

</body>
<script>

  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('home').checked = true;
  });


  // document.querySelectorAll('.card').forEach(card => {
  //   card.addEventListener('click', function () {
  //     const postId = this.dataset.postId;
  //     openPost(postId)
  //     console.log('Post ID:', postId);
  //   });
  // });

  //make liked button red
  document.querySelector('.like-button').addEventListener('click', function () {
    this.classList.toggle('liked');
  });


  //close card if clicked out the margins
  document.addEventListener("click", function (e) {
    if (!e.target.closest(".post-menu")) {
      document.getElementById("postMenu").style.display = "none";
    }
  });

</script>
</html>