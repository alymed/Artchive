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
    document.getElementById("loginForm").style.display = "none";
    document.getElementById("signupForm").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";
  }

  function openSignupForm2() {
    document.getElementById("signupForm").style.display = "none";
    document.getElementById("signupForm2").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";
  }
  
  function openSignupForm3() {
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

function openUploadForm() {
  document.getElementById("uploadForm").style.display = "block";
  document.getElementById("formOverlay").style.display = "block";
}

function closeUploadForm() {
  document.getElementById("uploadForm").style.display = "none";
  document.getElementById("formOverlay").style.display = "none";
  document.getElementById("home").checked = true;
}

function scrollToContact(){
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

function openPost(imageSrc) {
  const modal = document.getElementById('postModal');
  const modalImage = document.getElementById('modalImage');

  modalImage.src = imageSrc;
  modal.style.display = 'flex';
}

function closePost() {
  const modal = document.getElementById('postModal');
  modal.style.display = 'none';
}

function togglePostMenu() {
  const menu = document.getElementById("postMenu");
  menu.style.display = menu.style.display === "block" ? "none" : "block";
}

function addComment() {
  const input = document.getElementById("newComment");
  const commentList = document.getElementById("commentList");

  if (input.value.trim() !== "") {
    const newComment = document.createElement("div");
    newComment.classList.add("comment");
    newComment.innerHTML = `<strong>@you</strong> ${input.value}`;
    commentList.appendChild(newComment);
    input.value = "";
  }
}