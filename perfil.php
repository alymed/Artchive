
<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once( "lib/lib.php" );

    $idUser = $_SESSION['id'];

    $filesID = getFilesID($idUser);
    $userData = getUserData($idUser);

?>

<!-- HOME TAB -->
    <div id="profileContent" class="content">
      <div class="profile-header">
        <div class="profile-pic">
          <img src="images/profilePic.PNG" alt="Profile Picture" />
        </div>
        <div class="profile-info">
          <h1 id="userName"> <?php echo $userData['username']; ?> </h1>
          <!--<p class="username" id="userUsername">massama.jpeg</p>-->
          <p class="bio" id="userBio"><?php echo $userData['biography']; ?></p>

          <div class="social-stats">
            <div><strong>120</strong><br />Followers</div>
            <div><strong>80</strong><br />Following</div>
            <div><strong>34</strong><br />Posts</div>
            <button class="default-btn" onclick="openEditProfileForm()">Edit Profile</button>
          </div>
        </div>
      </div>


      <!-- Gallery -->
      <div class="img_container">
        <h2 class="section-title">Gallery</h2>
        <?php 
        for($idx=0; $idx<count($filesID); $idx++){
          $fileID = $filesID[$idx];
          $target = "<img src=\"showFileThumb.php?id=$fileID\" alt=\"Post\">";
          echo "<div class=\"card card_small\">$target</div>";
        }
        ?>
      </div>
    </div>


<script>


  document.addEventListener('DOMContentLoaded', function () {
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
