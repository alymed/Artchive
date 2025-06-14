function openLoginForm() {
  document.getElementById("signupForm").style.display = "none";
  document.getElementById("loginForm").style.display = "block";
  document.getElementById("formOverlay").style.display = "block";
}

function closeLoginForm() {
  document.getElementById("loginForm").style.display = "none";
  document.getElementById("formOverlay").style.display = "none";
}

function openSignupForm() {
    closeAllForms();
    document.getElementById("signupForm").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";
}





// Message handling
function showMessage(message, type = 'info') {
    const messageContainer = document.getElementById("messageContainer");
    const messageText = document.getElementById("messageText");
    const messageContent = document.getElementById("messageContent");
    
    messageText.textContent = message;
    messageContent.className = `message-content ${type}`;
    messageContainer.style.display = "block";
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        closeMessage();
    }, 5000);
}

function closeMessage() {
    document.getElementById("messageContainer").style.display = "none";
}

// URL parameter handling for multi-step signup
function handleUrlParameters() {
    const urlParams = new URLSearchParams(window.location.search);
    const signupStep = urlParams.get('signupStep');
    const signupError = urlParams.get('signupError');
    const username = urlParams.get('username');
    const password = urlParams.get('password');
    
    // Handle signup steps
    if (signupStep) {
        const email = urlParams.get('email') || '';
        const name = urlParams.get('name') || '';
        const birthdate = urlParams.get('birthdate') || '';
        
        switch (signupStep) {
            case '2':
                openSignupForm2(email, name, birthdate);
                break;
            case '3':
                openSignupForm3(username, password);
                break;
        }
    }
    
    // Handle errors
    if (signupError) {
        let errorMessage = '';
        switch (signupError) {
            case 'EmailInUse':
                errorMessage = 'This email is already registered. Please use a different email or try logging in.';
                break;
            case 'UsernameInUse':
                errorMessage = 'This username is already taken. Please choose a different username.';
                break;
            case 'MissingFields':
                errorMessage = 'Please fill in all required fields.';
                break;
            case 'InvalidName':
                errorMessage = 'Please enter a valid name using only letters, spaces, hyphens, apostrophes, and periods.';
                break;
            case 'InvalidBirthdate':
                errorMessage = 'Please enter a valid birthdate.';
                break;
            case 'FutureBirthdate':
                errorMessage = 'Birthdate cannot be in the future.';
                break;
            case 'AgeTooYoung':
                errorMessage = 'You must be at least 13 years old to register.';
                break;
            case 'WeakPassword':
                errorMessage = 'Password must be at least 8 characters long and contain at least one letter and one number.';
                break;
            case 'RegisterError':
                errorMessage = 'Registration failed. Please try again.';
                break;
            case 'InvalidSession':
                errorMessage = 'Invalid session. Please start the registration process again.';
                break;
            case 'BiographyTooLong':
                errorMessage = 'Biography must be 90 characters or less.';
                break;
            case 'FileTooLarge':
                errorMessage = 'Profile picture must be less than 5MB.';
                break;
            case 'InvalidFileType':
                errorMessage = 'Please upload a valid image file (JPG, PNG, or GIF).';
                break;
            case 'FilePathTooLong':
                errorMessage = 'File name is too long. Please rename your file.';
                break;
            case 'FileUploadError':
                errorMessage = 'Failed to upload profile picture. Please try again.';
                break;
            case 'ProfileUpdateError':
                errorMessage = 'Failed to update profile. Please try again.';
                break;
            default:
                errorMessage = 'An error occurred. Please try again.';
        }
        showMessage(errorMessage, 'error');
    }
}


function openUploadForm() {
  const form = document.getElementById("uploadForm");
    const overlay = document.getElementById("formOverlay");

  if (form && overlay) {
        form.style.display = "block";
        overlay.style.display = "block";
        console.log("Form opened successfully");
    } else {
        console.error("Form or overlay element not found");
    }
}

function closeUploadForm() {
  document.getElementById("uploadForm").style.display = "none";
  document.getElementById("formOverlay").style.display = "none";
  document.getElementById("home").checked = true;
}

function scrollToContact() {
  document.getElementById("contact").scrollIntoView({ behavior: "smooth" });
}
function openEditProfileForm() {
  document.getElementById("editProfileForm").style.display = "block";
  document.getElementById("formOverlay").style.display = "block";
}

function closeEditProfileForm() {
  document.getElementById("editProfileForm").style.display = "none";
  document.getElementById("formOverlay").style.display = "none";
}

function previewProfilePic(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById('profilePreview').src = e.target.result;
    }
    reader.readAsDataURL(input.files[0]);
  }
}


  

async function openPost(idPost) {
  try {
    const postResponse = await fetch(`getPostDataJS.php?query=${encodeURIComponent(idPost)}`);
    const postData = await postResponse.json();

    if (Object.keys(postData).length === 0) {
      console.log("No post data found.");
      return;
    }

    const { idUser, idImage, title, description, numLikes, numComments } = postData;

    const [userRes, imageRes] = await Promise.all([
      fetch(`getUserDataJS.php?query=${encodeURIComponent(idUser)}`),
      fetch(`getImageDetailsJS.php?query=${encodeURIComponent(idImage)}`)
    ]);

    const userData = await userRes.json();
    const imageData = await imageRes.json();

    updatePostModal(postData, userData, imageData);

  } catch (err) {
    console.error("Error loading post data:", err);
  }
}

  


function updatePostModal(postData, userData, imageDetails){

  if(Object.keys(imageDetails).length != 0){

    mimeFilename = imageDetails.mimeFilename;
    filename = imageDetails.filename;

    const targetDiv = document.getElementById("modalMediaContainer");

    if(targetDiv){

      switch(mimeFilename){

        case 'image':

          targetDiv.innerHTML = `<img src="showFile.php?id=${imageDetails.id}" alt="Post">`;
          break;
        
        case 'video':

          targetDiv.innerHTML = `
          <div class="DBGVideoContainer MyVideoContainer">
              <div class="DBGVideoPlayer MyVideoPlayer">
                  <video id="TheVideo" width="640" height="480" poster="showFileImage.php?id=${imageDetails.id}" controls >
                      <source src="showFile.php?id=${imageDetails.id}" />
                  </video>
              </div>
          </div>
          `;
          break;

        case 'audio':

          targetDiv.innerHTML = `
          <div class="audio-post">
            <audio controls>
              <source src="showFile.php?id=${imageDetails.id}" type="audio/mpeg">
              Your browser does not support the audio element.
            </audio>
          </div>
          `;
          break;

      }

      
      document.getElementById("likeButton").href = "likePost.php?idPost=" + postData.id;
      document.getElementById("modalUsername").textContent = userData.username
      document.getElementById("likeCount").textContent = postData.numLikes;
      document.getElementById("commentCount").textContent = postData.numComments;

      const modal = document.getElementById('postModal');
      modal.style.display = "block";


    }


    




  }



}


function closePost() {
  const modal = document.getElementById('postModal');
  modal.classList.remove('show');
  setTimeout(() => {
    modal.style.display = "none";
  }, 300); // tempo igual ao transition do CSS
}


function togglePostMenu() {
  const menu = document.getElementById("postMenu");
  menu.style.display = menu.style.display === "block" ? "none" : "block";
}

// function addComment() {
//   const input = document.getElementById("newComment");
//   const commentList = document.getElementById("commentList");

//   if (input.value.trim() !== "") {
//     const newComment = document.createElement("div");
//     newComment.classList.add("comment");
//     newComment.innerHTML = `<strong>@you</strong> ${input.value}`;
//     commentList.appendChild(newComment);
//     input.value = "";
//   }
// }


function addComment() {
  const commentInput = document.getElementById('newComment');
  const commentText = commentInput.value.trim();
  const postId = document.getElementById('currentPostId').value;

  if (!commentText) {
    alert("Please enter a comment");
    return;
  }

  const formData = new FormData();
  formData.append('post_id', postId);
  formData.append('comment', commentText);

  fetch('addComment.php', {
    method: 'POST',
    body: formData,
    credentials: 'same-origin' // para enviar cookies de sessão
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Limpa o input
        commentInput.value = '';

        // Atualiza a lista de comentários no modal
        loadComments(postId);
      } else {
        alert('Error: ' + data.message);
      }
    })
    .catch(err => {
      console.error('Fetch error:', err);
      alert('Error sending comment');
    });
}

function loadComments(postId) {
  fetch(`getComments.php?post_id=${postId}`, {
    credentials: 'same-origin'
  })
    .then(res => res.json())
    .then(comments => {
      const commentList = document.getElementById('commentList');
      commentList.innerHTML = ''; // limpa a lista atual

      comments.forEach(comment => {
        const div = document.createElement('div');
        div.className = 'comment';
        div.innerHTML = `<strong>@${comment.username}</strong> ${comment.content}`;
        commentList.appendChild(div);
      });
    })
    .catch(err => {
      console.error('Error loading comments:', err);
    });
}
