<?php

  session_start();

  if (!isset($_SESSION['id'])) {
      
      header('Location: index.php');
      exit();
  }


  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  require_once( "lib/lib.php" );
   
  if (isset($_GET["username"])) {
    $username = $_GET["username"];
  }


?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
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



  <div class="form-popup" id="editProfileForm">
      <form method="post" class="form-container">
        <span class="close-icon" onclick="closeEditProfileForm()">&times;</span>
        <h3>Edit Profile</h3>
        <div class="info">

          <div class="profile-pic">
              <img id="profilePreview" src="images/profilePic.PNG" alt="Preview">
          </div>
          
          <input type="file" id="profile-pic-input" name="profile_pic" accept="image/*" style="display:none;"
            onchange="previewProfilePic(this)">

          <label for="name">Name</label>
          <input type="text" id="name" name="name" placeholder="Your name" required>

          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Your username" required>

          <label for="bio">Bio</label>
          <textarea type="text" id="bio" name="bio" placeholder="Tell us about you..." rows="4"></textarea>

          <button type="submit" class="default-btn">Save Changes</button>
        </div>
      </form>
  </div>
</body>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('profile').checked = true;

    // 1. Abre modal de post
    const cards = document.querySelectorAll('.img_container .card img');
    cards.forEach(img => {
      img.addEventListener('click', function () {
        openPost(this.src);
      });
    });

    // 2. Like button toggle
    const likeButton = document.querySelector('.like-button');
    if (likeButton) {
      likeButton.addEventListener('click', function () {
        this.classList.toggle('liked');
      });
    }

    // 3. Fecha dropdown de post se clicar fora
    document.addEventListener("click", function (e) {
      if (!e.target.closest(".post-menu")) {
        document.getElementById("postMenu").style.display = "none";
      }
    });

    // 4. Botão "Edit Profile" (identifica botão específico)
    const editProfileBtn = document.querySelector(".profile-info .default-btn");
    if (editProfileBtn) {
      editProfileBtn.addEventListener("click", function (e) {
        e.preventDefault();
        openEditProfileForm();
      });
    }

    // 5. Fechar forms ao clicar no overlay
    const overlay = document.getElementById("formOverlay");
    overlay.addEventListener('click', function () {
      closeEditProfileForm();
      closeUploadForm();
    });

  });


  // Preview da imagem
  function previewProfilePic(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById('profilePreview').src = e.target.result;
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>
