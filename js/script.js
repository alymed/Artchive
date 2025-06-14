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

function openSignupForm2(email, name , birthdate ) {
    // Populate hidden fields with data from step 1
    if (email){
      document.getElementById("signup2_email").value = email;
    }
    if (name) document.getElementById("signup2_name").value = name;
    if (birthdate) document.getElementById("signup2_birthdate").value = birthdate;
    
    closeAllForms();
    document.getElementById("signupForm2").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";

    console.log("Going to Step 2:", email, name, birthdate);
}

function openSignupForm3(username = '', password = '') {
    // Populate hidden field with user ID
    if (username) document.getElementById("signup3_username").value = username;
    if (password) document.getElementById("signup3_password").value = password;

    closeAllForms();
    document.getElementById("signupForm2").style.display = "none";
    document.getElementById("signupForm3").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";
}

function closeSignupForm() {
    document.getElementById("signupForm").style.display = "none";
    document.getElementById("signupForm2").style.display = "none";
    document.getElementById("signupForm3").style.display = "none";
    document.getElementById("formOverlay").style.display = "none";
}

function closeAllForms() {
    document.getElementById("loginForm").style.display = "none";
    document.getElementById("signupForm").style.display = "none";
    document.getElementById("signupForm2").style.display = "none";
    document.getElementById("signupForm3").style.display = "none";
    document.getElementById("formOverlay").style.display = "none";
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

// Biography character counter
function updateBiographyCounter() {
    const textarea = document.getElementById('biography');
    const counter = document.getElementById('bio-count');
    
    if (textarea && counter) {
        textarea.addEventListener('input', function() {
            const count = this.value.length;
            counter.textContent = count;
            
            if (count > 90) {
                counter.style.color = 'red';
            } else if (count > 75) {
                counter.style.color = 'orange';
            } else {
                counter.style.color = 'inherit';
            }
        });
    }
}

function openUploadForm() {
  document.getElementById("uploadForm").style.display = "block";
  document.getElementById("formOverlay").style.display = "block";
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

function openPost(postId) {
  const modal = document.getElementById('postModal');
  modal.style.display = "block";
  setTimeout(() => modal.classList.add('show'), 10);

  console.log('Post ID JS:', postId);

  const card = document.querySelector(`.card[data-post-id="${postId}"]`);
  if (card) {
    const img = card.querySelector('img');
    const username = card.getAttribute('data-username');
    const description = card.getAttribute('data-description');
    const date = card.getAttribute('data-date');

    document.getElementById('modalImage').src = img.src;
    document.getElementById('captionUsername').textContent = username;
    document.getElementById('modalUsername').textContent = username;
    document.getElementById('captionText').textContent = description;

    // Opcional: Exibir a data em algum lugar
    // document.getElementById('modalDate').textContent = new Date(date).toLocaleDateString();
  }

  document.getElementById('currentPostId').value = postId;
  loadComments(postId);
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
