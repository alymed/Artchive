<?php
  session_start();

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  require_once( "lib/lib.php" );
  
  $idUser = $_SESSION['id'] ?? null;

  if (isset($idUser)) {
    header('Location: app.php');
    exit();
  }

  $owner = false;
  
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
        <a href="app.php" class="nav-logo">
            <img src="images/logo.png" alt="Logo" class="logo-img">
        </a>
        <div style="position: relative;">
            <input type="text" class="search-box" id="search" placeholder="Search..." autocomplete="off">
            <div id="autocomplete-results"
                style="position: absolute; top: 100%; left: 0; right: 0; background: white; z-index: 1000;"></div>
        </div>

        <a href="perfil.php" class="user-icon">
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
        <div id="homeContent" class="content">

        </div>
        <div id="filmContent" class="content">
            <div class="img_container">
                <?php
                  $posts = array();

                  $allUsers = getAllUsersData();

                  for( $i= 0;$i<count($allUsers);$i++){
                      $allUsersPosts = getPosts($allUsers[$i]['id'], $owner);
                      for($j= 0;$j<count($allUsersPosts);$j++){
                          $idFile = $allUsersPosts[$j]['idImage'];
                          $fileData = getFileDetails($idFile);
                          if($fileData['mimeFilename'] == 'video'){
                              $posts[] = $allUsersPosts[$j];
                          }
                      }
                  }

                  if(count($posts) > 0){
                    $randomKeys = array_rand($posts, count($posts));

                      if(count($posts) == 1){
                          $randomKeys = [$randomKeys];
                      }

                      for( $k= 0;$k<count($posts);$k++){

                          $post = $posts[$randomKeys[$k]];
                          $idPost = $post['id'];
                          $postTitle = $post['title'];
                          $fileID = $post['idImage'];
                          $user = getUsernameById($post['idUser']);
                          $description = $post['description'];
                          $date = $post['createdAt'];

                          echo "<figure class=\"card card_large\" 
                                  data-post-id=\"$idPost\" 
                                  data-description=\"".htmlspecialchars($description)."\" 
                                  data-date=\"$date\">";
                          echo "<img src=\"showFileThumb.php?id=$fileID&size=Large\" alt=\"Post\"></img>";
                          echo "<figcaption>$postTitle</figcaption>";
                          echo "</figure>";
                      }

                  }
              ?>
            </div>
        </div>

        <div id="musicContent" class="content">
            <div class="img_container">
                <?php
            $posts = array();

            $allUsers = getAllUsersData();

            for( $i= 0;$i<count($allUsers);$i++){
                $allUsersPosts = getPosts($allUsers[$i]['id'], $owner);
                for($j= 0;$j<count($allUsersPosts);$j++){
                    $idFile = $allUsersPosts[$j]['idImage'];
                    $fileData = getFileDetails($idFile);
                    if($fileData['mimeFilename'] == 'audio'){
                        $posts[] = $allUsersPosts[$j];
                    }
                }
            }

            if(count($posts) > 0){


                $randomKeys = array_rand($posts, count($posts));

                if(count($posts) == 1){
                    $randomKeys = [$randomKeys];
                }

                for( $k= 0;$k<count($posts);$k++){

                    $post = $posts[$randomKeys[$k]];

                    $idPost = $post['id'];
                    $postTitle = $post['title'];
                    $fileID = $post['idImage'];

                    $image = "<img src=\"showFileThumb.php?id=$fileID&size=small\" alt=\"Post\"></img>";
                    $caption = "<figcaption> aaaaaaaa </figcaption>";
                    echo "<figure class=\"card card_small\" data-post-id=\"$idPost\">$image $caption </figure>";

                }

            }


        ?>
            </div>
        </div>

        <div id="photoContent" class="content">
            <div class="img_container">
                <?php
            $posts = array();

            $allUsers = getAllUsersData();

            for( $i= 0;$i<count($allUsers);$i++){
                $allUsersPosts = getPosts($allUsers[$i]['id'], $owner);
                for($j= 0;$j<count($allUsersPosts);$j++){
                    $idFile = $allUsersPosts[$j]['idImage'];
                    $fileData = getFileDetails($idFile);
                    if($fileData['mimeFilename'] == 'image'){
                        $posts[] = $allUsersPosts[$j];
                    }
                }
            }

            if(count($posts) > 0){


                $randomKeys = array_rand($posts, count($posts));

                if(count($posts) == 1){
                    $randomKeys = [$randomKeys];
                }

                for( $k= 0;$k<count($posts);$k++){

                    $post = $posts[$randomKeys[$k]];

                    $idPost = $post['id'];
                    $postTitle = $post['title'];
                    $fileID = $post['idImage'];

                    $image = "<img src=\"showFileThumb.php?id=$fileID&size=small\" alt=\"Post\"></img>";
                    $caption = "<figcaption> aaaaaaaa </figcaption>";
                    echo "<figure class=\"card card_small\" data-post-id=\"$idPost\">$image $caption </figure>";

                }

            }

        ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>


<div id="postModal" class="modal">
    <div class="modal-content">
        <span class="close-icon" onclick="closePost()">&times;</span>
        <div class="post">
            <div class="post-header">
                <img id="modalProfilePic" src="" alt="User profile" class="profile-pic">
                <span id="modalUsername" class="username">Username</span>
                <div class="post-menu">
                    <i class="bi bi-three-dots-vertical menu-icon" onclick="togglePostMenu()"></i>
                    <div class="dropdown-menu" id="postMenu">
                        <button onclick="alert('Analytics clicked')">Analytics</button>
                        <button onclick="alert('Share clicked')">Share</button>
                    </div>
                </div>
            </div>
            <div id="modalMediaContainer"></div>
            <div class="post-footer">
                <div class="post-actions">
                    <a id="likeButton" class="like-button"><i class="bi bi-heart"></i></a>
                    <span id="likeCount" class="action-count">0</span>

                    <button class="comment-button"><i class="bi bi-chat"></i></button>
                    <span id="commentCount" class="action-count">0</span>

                    <button class="save-button"><i class="bi bi-bookmark"></i></button>
                </div>
                <p class="caption"><span class="username" id="captionUsername"></span>
                    <span id="captionText"></span>
                </p>
            </div>
            <div class="comment-section">
                <h4>Comments</h4>
                <div class="comment-list" id="commentList"></div>
                <div class="comment-input">
                    <input type="text" id="newComment" placeholder="Add a comment..." />
                    <button onclick="addComment()">Post</button>
                </div>
            </div>
        </div>
    </div>
</div>
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
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.img_container .card img');

    cards.forEach(img => {
        img.addEventListener('click', function() {
            openPost(this.src);
        });
    });
});

//close card if clicked out the margins
document.addEventListener("click", function(e) {
    if (!e.target.closest(".post-menu")) {
        document.getElementById("postMenu").style.display = "none";
    }
});
</script>

</html>