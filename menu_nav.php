<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once( "lib/lib.php" );

    $idUser = $_SESSION['id'];

    if(isset($username)){
        $idUserProfile = searchUsers($username, $idUser)[0]["id"];
        $owner = false;

    }else{
        $idUserProfile = $_SESSION['id'];
        $owner = true;
    }

    $followers = getUserFollowers( $idUser);
    $following = getUserFollowing($idUser);
    
    #Profile
    $profile_posts = getPosts($idUserProfile, $owner);
    $profile_userData = getUserData($idUserProfile);
    $profile_followers = getUserFollowers($idUserProfile);
    $profile_following = getUserFollowing($idUserProfile);

    $notifications = getActivities($idUser);

    if(!$owner){
        $isfollowing = false;
        for($i= 0;$i<count($profile_followers);$i++){
            
            if($profile_followers[$i]['idFollower'] == $idUser){
                $isfollowing = true;
            }
        }
    }

?>


<input hidden type="radio" name="tab" id="profile">
<input type="radio" name="tab" id="home">
<input type="radio" name="tab" id="create">
<input type="radio" name="tab" id="notification">
<input type="radio" name="tab" id="film">
<input type="radio" name="tab" id="photos">
<input type="radio" name="tab" id="music">
<input type="radio" name="tab" id="settings">


<div class="tabs">
    <label for="home" class="tab">
        <i class="bi bi-house-door default-icon"></i>
        <i class="bi bi-house-door-fill active-icon"></i>
    </label>
    <label for="create" class="tab" onclick="openUploadForm()">
        <i class="bi bi-plus-square default-icon"></i>
        <i class="bi bi-plus-square-fill active-icon"></i>
    </label>
    <label for="notification" class="tab">
        <i class="bi bi-bell default-icon"></i>
        <i class="bi bi-bell-fill active-icon"></i>
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
    <label for="settings" class="tab">
        <i class="bi bi-gear default-icon"></i>
        <i class="bi bi-gear-fill active-icon"></i>
    </label>
</div>

<div class="tab-content">

    <div id="homeContent" class="content">
        <input type="radio" name="top_tab" id="all" checked>
        <input type="radio" name="top_tab" id="tag2">
        <input type="radio" name="top_tab" id="tag3">
        <input type="radio" name="top_tab" id="tag4">
        <input type="radio" name="top_tab" id="tag5">

        <div class="top_tabs">
            <label for="all" class="top_tab">All</label>
            <label for="tag2" class="top_tab">Tag 2</label>
            <label for="tag3" class="top_tab">Tag 3</label>
            <label for="tag4" class="top_tab">Tag 4</label>
            <label for="tag5" class="top_tab">Tag 5</label>

        </div>

        <div class="img_container">
        <?php
            $posts = array();
            for( $i= 0;$i<count($following);$i++){
                $followingUserPosts = getPosts($following[$i]['idFollowed'], $owner);
                for($j= 0;$j<count($followingUserPosts);$j++){
                    $posts[] = $followingUserPosts[$j];
                }
            }

            if(count($posts) > 0){
                $randomKeys = array_rand($posts, count($posts));
            }

            for( $k= 0;$k<count($posts);$k++){

                $post = $posts[$randomKeys[$k]];

                $sizes = ['small', 'medium', 'large'];

                $randomKey = array_rand($sizes);
                $randomSize = $sizes[$randomKey];

                $idPost = $post['id'];
                $postTitle = $post['title'];
                $fileID = $post['idImage'];

                $image = "<img src=\"showFileThumb.php?id=$fileID&size=$randomSize\" alt=\"Post\"></img>";
                $caption = "<figcaption> aaaaaaaa </figcaption>";
                echo "<figure class=\"card card_$randomSize\" data-post-id=\"$idPost\">$image $caption </figure>";

            }
        ?>
        </div>

  
    </div>
    <div id="createContent" class="content">

    </div>
    <div id="notification" class="content">
        <h2>Notificações</h2>
        <ul class="notification-list">
            <?php
                for($i= 0;$i<count($notifications);$i++){

                    $actor = getUserData($notifications[$i]['idActor']);
                    $action = $notifications[$i]['action'];
                    $isRead = $notifications[$i]['isRead'];

                    $createdAt = new DateTime($notifications[$i]['createdAt']);
                    $now = new DateTime(); 
                    $diffInSeconds = $now->getTimestamp() - $createdAt->getTimestamp();
                    
                    if ($diffInSeconds < 60) {
                        $time =  "A few seconds ago";
                    } elseif ($diffInSeconds < 3600) {
                        $time = floor($diffInSeconds / 60) . "m";
                    } elseif ($diffInSeconds < 86400) {
                        $time  = floor($diffInSeconds / 3600) . "h";
                    } else {
                        $time = floor($diffInSeconds / 86400) . "d";
                    }


                    switch($action){
                        case 'follow':
                            $text = $actor['username'] . " followed you!";
                            break;
                        
                        case  'comment':
                            $target = getPostData($notifications[$i]["idTarget"]);
                            $text = $target["username"] . " commented on your post!";
                            break;

                        case  'liked':
                            $target = getPostData($notifications[$i]["idTarget"]);
                            $text = $target["username"] . " liked your post!";
                            break;
                    }
            ?>
            <li class="notification-item">
                <i class="bi bi-info-circle"></i>
                <?php echo $text?>
                <span class="time"><?php echo $time ?></span>
            </li>
            <?php
                }
            ?>

        </ul>
    </div>
    <div id="filmContent" class="content">
       
      
    </div>
    <div id="musicContent" class="content">

    </div>
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
            }

            for( $k= 0;$k<count($posts);$k++){

                $post = $posts[$randomKeys[$k]];

                $sizes = ['small', 'medium', 'large'];

                $randomKey = array_rand($sizes);
                $randomSize = $sizes[$randomKey];

                $postTitle = $post['title'];
                $fileID = $post['idImage'];

                $image = "<img src=\"showFileThumb.php?id=$fileID&size=$randomSize\" alt=\"Post\"></img>";
                $caption = "<figcaption> aaaaaaaa </figcaption>";
                echo "<figure class=\"card card_$randomSize\">$image $caption </figure>";

            }

        ?>
    </div>

    <div id="photoContent" class="content">
        <h2>Content 3</h2>
        <p>This is the content for Tab 3.</p>
    </div>

    <div id="settingsContent" class="content">
        <h2>Content 3</h2>
        <p><a href="logout.php">Logout</a></p>
    </div>

    <div id="profileContent" class="content">
        <div class="profile-header">
            <div class="profile-pic">
                <img src="images/profilePic.PNG" alt="Profile Picture" />
            </div>
            <div class="profile-info">
                <h1 id="userName"> <?php echo $profile_userData['username']; ?> </h1>
                <p class="bio" id="userBio"><?php echo $profile_userData['biography']; ?></p>

                <div class="social-stats">
                    <div><strong><?php echo count($profile_followers) ?></strong><br />Followers</div>
                    <div><strong><?php echo count($profile_following) ?> </strong><br />Following</div>
                    <div><strong><?php echo count($profile_posts) ?></strong><br />Posts</div>
                    <button class="default-btn" onclick="openEditProfileForm()">Edit Profile</button>
                    <?php if (!$owner) { ?>
                    <button class="default-btn" onclick="scrollToContact()">Contact Me</button>
                    <?php } ?>
                    <?php
            if (!$owner && !$isfollowing){
            ?>
                    <a href="follow.php?idFollower=<?php echo urlencode($idUser)?>&idFollowed=<?php echo urlencode($idUserProfile)?>"
                        class="button-link">Follow</a>
                    <?php
            } else if (!$owner && $isfollowing){    
            ?>
                    <a href="unfollow.php?idFollower=<?php echo urlencode($idUser)?>&idFollowed=<?php echo urlencode($idUserProfile)?>"
                        class="button-link">Unfollow</a>
                    <?php
            }  
            ?>
                </div>
            </div>
        </div>

        <div class="img_container">
            <h2 class="section-title"> Gallery </h2>
            <?php 

        for($idx=0; $idx<count($profile_posts); $idx++){
          $idFile = $profile_posts[$idx]['idImage'];
          $idPost = $profile_posts[$idx]['id'];
          $target = "<img src=\"showFileThumb.php?id=" . $idFile . "&size=small\" alt=\"Post\"></img>";
          echo "<div class=\"card card_small\" data-post-id=\"$idPost\">$target</div>";
        }
        ?>
        </div>
        <?php if (!$owner) { ?>
        <div id="contact" class="contact-container">
            <h2>Contact Me</h2>
            <form action="sendEmail.php?id=<?php echo urlencode($idUserProfile); ?>" method="POST">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Your name..." required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Your email..." required>

                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" placeholder="Subject..." required>

                <label for="message">Message</label>
                <textarea id="message" name="message" rows="6" placeholder="Write your message..." required></textarea>
                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">

                <button class="default-btn" type="submit">Send</button>
            </form>
        </div>
        <?php } ?>
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
                <img src="https://i.pravatar.cc/40" alt="User profile" class="profile-pic">
                <span class="username">user_one</span>
                <div class="post-menu">
                    <i class="bi bi-three-dots-vertical menu-icon" onclick="togglePostMenu()"></i>
                    <div class="dropdown-menu" id="postMenu">
                        <button onclick="alert('Analytics clicked')">Analytics</button>
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
                <div class="comment-input">
                    <input type="text" id="newComment" placeholder="Add a comment..." />
                    <button onclick="addComment()">Post</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-popup" id="uploadForm">
    <form method="POST" class="form-container" action="fileUpload.php" enctype="multipart/form-data">
        <span class="close-icon" onclick="closeUploadForm()">&times;</span>
        <img src="images/logo.png" alt="Logo" class="logo-img">

        <h3>Upload New Content</h3>
        <div class="info">

            <input type="file" id="upload-file" name="userFile" size="64" required>

            <input type="text" id="upload-title" name="title" placeholder="Enter a title" required>
            <textarea type="text" id="upload-description" name="description" placeholder="Write a short description..."
                rows="6"></textarea>

            <button type="button" id="privacyToggle" class="privacy-btn" name="privacy" aria-pressed="false" title="Definir como público ou privado">
                <i class="fa-solid fa-lock-open"></i>
            </button>
            <input type="hidden" name="privacy" id="privacyInput" value="public">

            <button type="submit" class="default-btn">Upload</button>
        </div>
    </form>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<script>
    const privacyToggle = document.getElementById('privacyToggle');
    const privacyInput = document.getElementById('privacyInput');

    privacyToggle.addEventListener('click', () => {
        const icon = privacyToggle.querySelector('i');
        if (privacyInput.value === 'public') {
            privacyInput.value = 'private';
            privacyToggle.setAttribute('aria-pressed', 'true');
            icon.classList.remove('fa-lock-open');
            icon.classList.add('fa-lock');
            privacyToggle.title = "Definido como privado";
        } else {
            privacyInput.value = 'public';
            privacyToggle.setAttribute('aria-pressed', 'false');
            icon.classList.remove('fa-lock');
            icon.classList.add('fa-lock-open');
            privacyToggle.title = "Definido como público";
        }
    });
</script>