<?php

session_start();

if (isset($_SESSION['id'])) {
    
    header('Location: app.php');
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
  <div class="form-overlay" id="formOverlay"></div>

  <div class="search-container">
    <a href="exploreConvidado.php" class="nav-logo">
      <img src="images/logo.png" alt="Logo" class="logo-img">
    </a>
    <input type="text" class="search-box" placeholder="Search...">
    <a class="user-icon" onclick="openLoginForm()">
      <i class="bi bi-person-circle"></i>
    </a>
  </div>

  <input type="radio" name="tab" id="home" checked>
  <input type="radio" name="tab" id="film">
  <input type="radio" name="tab" id="photos">
  <input type="radio" name="tab" id="music">


  <div class="tabs">
    <label for="home" class="tab">
      <i class="bi bi-house-door default-icon"></i>
      <i class="bi bi-house-door-fill active-icon"></i>
    </label>
    <label for="film" class="tab">
      <i class="bi bi-camera-reels default-icon"></i>
      <i class="bi bi-camera-reels-fill active-icon"></i>
    </label>
    <label for="photos" class="tab">
      <i class="bi bi-camera default-icon"></i>
      <i class="bi bi-camera-fill active-icon"></i>
    </label>
    <label for="music" class="tab">
      <i class="bi bi-file-music default-icon"></i>
      <i class="bi bi-file-music-fill active-icon"></i>
    </label>
  </div>

  <div class="tab-content">
    <div id="content1" class="content">
      <input type="radio" name="top_tab" id="all" checked>
      <input type="radio" name="top_tab" id="tag2">
      <input type="radio" name="top_tab" id="tag3">
      <div class="top_tabs">
        <label for="all" class="top_tab">All</label>
        <label for="tag2" class="top_tab">Tab 2</label>
        <label for="tag3" class="top_tab">Tab 3</label>
      </div>

      <div class="img_container">
        <figure class="card card_medium">
          <img src="images/img3.jpg" alt="Description of img3">
          <figcaption>Legend for img3</figcaption>
        </figure>

        <figure class="card card_small">
          <img src="images/img4.jpg" alt="Description of img4">
          <figcaption>Legend for img4 (small)</figcaption>
        </figure>

        <figure class="card card_large">
          <img src="images/img5.jpg" alt="Description of img5">
          <figcaption>Legend for img5</figcaption>
        </figure>

        <figure class="card card_large">
          <img src="images/img4.jpg" alt="Description of img4 again">
          <figcaption>Legend for second img4 (large)</figcaption>
        </figure>
      </div>

      <div class="tag2-content">
        <div class="img_container">
          <figure class="card card_medium">
            <img src="images/img1.jpg" alt="Description of img3">
            <figcaption>Legend for img3</figcaption>
          </figure>

          <figure class="card card_large">
            <img src="images/img4.jpg" alt="Description of img4 again">
            <figcaption>Legend for second img4 (large)</figcaption>
          </figure>
        </div>
      </div>

      <div class="tag3-content">
        <div class="img_container">
          <figure class="card card_medium">
            <img src="images/img2.jpg" alt="Description of img3">
            <figcaption>Legend for img3</figcaption>
          </figure>

          <figure class="card card_large">
            <img src="images/img4.jpg" alt="Description of img4 again">
            <figcaption>Legend for second img4 (large)</figcaption>
          </figure>
        </div>
      </div>


    </div>
    <div id="filmContent" class="content">
      <div class="img_container">
        <figure class="card card_medium">
          <img src="images/img3.jpg" alt="Description of img3">
          <figcaption>Legend for img3</figcaption>
        </figure>

        <figure class="card card_small">
          <img src="images/img4.jpg" alt="Description of img4">
          <figcaption>Legend for img4 (small)</figcaption>
        </figure>

        <figure class="card card_large">
          <img src="images/img5.jpg" alt="Description of img5">
          <figcaption>Legend for img5</figcaption>
        </figure>

        <figure class="card card_large">
          <img src="images/img4.jpg" alt="Description of img4 again">
          <figcaption>Legend for second img4 (large)</figcaption>
        </figure>
        <figure class="card card_medium">
          <img src="images/img3.jpg" alt="Description of img3">
          <figcaption>Legend for img3</figcaption>
        </figure>

        <figure class="card card_small">
          <img src="images/img4.jpg" alt="Description of img4">
          <figcaption>Legend for img4 (small)</figcaption>
        </figure>

        <figure class="card card_large">
          <img src="images/img5.jpg" alt="Description of img5">
          <figcaption>Legend for img5</figcaption>
        </figure>

        <figure class="card card_large">
          <img src="images/img4.jpg" alt="Description of img4 again">
          <figcaption>Legend for second img4 (large)</figcaption>
        </figure>
        <figure class="card card_medium">
          <img src="images/img3.jpg" alt="Description of img3">
          <figcaption>Legend for img3</figcaption>
        </figure>

        <figure class="card card_small">
          <img src="images/img4.jpg" alt="Description of img4">
          <figcaption>Legend for img4 (small)</figcaption>
        </figure>

        <figure class="card card_large">
          <img src="images/img5.jpg" alt="Description of img5">
          <figcaption>Legend for img5</figcaption>
        </figure>

        <figure class="card card_large">
          <img src="images/img4.jpg" alt="Description of img4 again">
          <figcaption>Legend for second img4 (large)</figcaption>
        </figure>
      </div>
    </div>
    <div id="musicContent" class="content">
      <h2>Content 3</h2>
      <p>This is the content for Tab 3.</p>
    </div>
    <div id="photoContent" class="content">
      <h2>Content 3</h2>
      <p>This is the content for Tab 3.</p>
    </div>
  </div>

  <div id="postModal" class="modal">
    <div class="modal-content">
      <span class="close-icon" onclick="closePost()">&times;</span>
      <div class="post">
        <div class="post-header">
          <img src="https://i.pravatar.cc/40" alt="User profile" class="profile-pic">
          <span class="username">user_one</span>
          <div class="post-menu">
            <i class="bi bi-three-dots-vertical menu-icon" onclick="togglePostMenu()"></i>
            <div class="dropdown-menu" id="postMenu">
              <button onclick="alert('Share clicked')">Share</button>
            </div>
          </div>
        </div>
        <img id="modalImage" class="post-image" alt="Post">
        <div class="post-footer">
          <div class="post-actions">
            <button class="like-button"><i class="bi bi-heart"></i></button>
            <span class="action-count">120</span>

            <button class="comment-button"><i class="bi bi-chat"></i></button>
            <span class="action-count">34</span>

            <button class="save-button"><i class="bi bi-bookmark"></i></button>
            <span class="action-count">18</span>
          </div>

          <p class="caption"><span class="username">user_one</span> Loving the view!</p>
        </div>
        <div class="comment-section">
          <h4>Comments</h4>
          <div class="comment-list" id="commentList">
            <div class="comment"><strong>@alice</strong> Wow!</div>
            <div class="comment"><strong>@bob</strong> Amazing!</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS and Popper -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>


  <?php 
    include 'auth_forms.php'
  ?>


</body>
<script>

function previewProfilePic(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('profilePicPreview').src = e.target.result;
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
  document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.img_container .card img');

    cards.forEach(img => {
      img.addEventListener('click', function () {
        openPost(this.src);
      });
    });
  });

  //close card if clicked out the margins
  document.addEventListener("click", function (e) {
    if (!e.target.closest(".post-menu")) {
      document.getElementById("postMenu").style.display = "none";
    }
  });



</script>

</html>