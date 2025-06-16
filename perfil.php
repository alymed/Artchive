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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    if (isset($_POST['tagName']) && !empty(trim($_POST['tagName']))) {
        $tagName = trim($_POST['tagName']);
        $success = addCategory($tagName);
    }

    // Opcional: Redirecionar para evitar resubmissão do formulário ao recarregar
    header("Location: perfil.php" . urlencode($username));
    exit();
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
        <div id="newProfilePicContainer" class="profile-pic">
            
        </div>
        
        <label for="userFile">Change profile picture</label>
        <input type="file" id="uploadProfilePic" name="userFile" size="64" accept="image/*" /*onchange="previewProfilePic(this)"*/>
        
        <button id="uploadProfilePicButton" class="default-btn">Apply</button>

        <form method="post" class="form-container" action="editProfile.php" enctype="multipart/form-data">
            <span class="close-icon" onclick="closeEditProfileForm()">&times;</span>
            <h3>Edit Profile</h3>
            <div class="info">


                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Your name" value="<?php echo htmlspecialchars($profile_userData['name']); ?>">

                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Your username" value="<?php echo htmlspecialchars($profile_userData['username']); ?>">

                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" placeholder="Tell us about you..." rows="4"><?php echo htmlspecialchars($profile_userData['biography']); ?></textarea>

                <button type="submit" class="default-btn">Save Changes</button>
            </div>
        </form>
    </div>

    <div class="form-popup" id="supporterForm">
        <form method="post" class="form-container">
            <span class="close-icon" onclick="closeSupporterForm()">&times;</span>
            <h3>Become Supporter</h3>
            <div class="info">
                <p>
                As a <strong>Supporter</strong>, you can:<br>
                &bull; Create secondary categories<br>
                &bull; Upload multimedia content<br>
                &bull; Add descriptions and tags<br>
                &bull; Set content visibility<br>
                &bull; Manage your own submissions
            </p>
            <input type="hidden" name="become_supporter" value="1">
            <button type="submit" class="default-btn">Become Supporter</button>
            </div>
        </form>
    </div>

    <div class="form-popup" id="addCategoryForm">
        <form method="post" class="form-container">
            <span class="close-icon" onclick="closeAddCategoryForm()">&times;</span>
            <h3>Add Category</h3>
            <div class="info">
               <label for="tagName">Category Name</label>
                <input type="text" id="tagName" name="tagName" placeholder="Enter category name..." required>

                <input type="hidden" name="add_category" value="1">
                <button type="submit" class="default-btn" style="margin:8px;">Add Category</button>
            </div>
        </form>
    </div>
</body>


<script type="module">


document.getElementById('uploadProfilePicButton').addEventListener('click', async () => {
    const input = document.getElementById('uploadProfilePic');
    const file = input.files[0];

    if (!file) {
        alert("Please select an image.");
        return;
    }

    // Optional: check it's an image
    if (!file.type.startsWith("image/")) {
        alert("Only image files are allowed.");
        return;
    }

    const formData = new FormData();
    formData.append("userFile", file);

    const response = await fetch("getNewProfilePicJS.php", {
        method: "POST",
        body: formData
    });

    const result = await response.json(); 
    
    if(Object.keys(result).length){

        console.log("id: " + result.id);
        console.log("filename: " + result.filename);
        const newProfilePicContainer = document.getElementById('newProfilePicContainer');
        newProfilePicContainer.innerHTML = `<img id="profilePreview" src="showFile.php?id=${result.id}" alt="Preview"></img>`;



    }
});

</script>

<script>

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('profile').checked = true;

    // 2. Like button toggle
    const likeButton = document.querySelector('.like-button');
    if (likeButton) {
        likeButton.addEventListener('click', function() {
            this.classList.toggle('liked');
        });
    }

    // 3. Fecha dropdown de post se clicar fora
    document.addEventListener("click", function(e) {
        if (!e.target.closest(".post-menu")) {
            document.getElementById("postMenu").style.display = "none";
        }
    });

    // 4. Botão "Edit Profile" (identifica botão específico)
    const editProfileBtn = document.querySelector(".profile-info .default-btn");
    if (editProfileBtn) {
        editProfileBtn.addEventListener("click", function(e) {
            e.preventDefault();
            openEditProfileForm();
        });
    }

    // 5. Fechar forms ao clicar no overlay
    const overlay = document.getElementById("formOverlay");
    overlay.addEventListener('click', function() {
        closeEditProfileForm();
        closeUploadForm();
    });

});


// Preview da imagem
function previewProfilePic(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

</script>