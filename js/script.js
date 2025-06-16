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
  document.getElementById("upload-file").value=""
  document.getElementById("upload-title").value=""
  document.getElementById("upload-category").value=""
  document.getElementById("upload-description").value=""


  document.getElementById("uploadForm").style.display = "block";
  document.getElementById("formOverlay").style.display = "block";
}

function openSupporterForm() {
  document.getElementById("supporterForm").style.display = "block";
  document.getElementById("formOverlay").style.display = "block";
}
function closeSupporterForm() {
  document.getElementById("supporterForm").style.display = "none";
  document.getElementById("formOverlay").style.display = "none";
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
  


// Funções para o modal de post
function togglePostMenu() {
    const menu = document.getElementById('postMenu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}


// Função para fechar o modal
function closePost() {
    const modal = document.getElementById('postModal');
    modal.classList.remove('show');
    document.body.style.overflow = 'auto'; // Restaura scroll da página
}

// Função para abrir o modal
function openPost(idPost, idUser) {
    const modal = document.getElementById('postModal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden'; // Previne scroll da página

    const mediaContainer = document.getElementById("modalMediaContainer");
    if (likeButton) {
        mediaContainer.setAttribute('data-post-id', idPost);
    }
    
    const targetDiv = document.getElementById("modalMediaContainer");
    // Aqui você carregaria os dados do post
    updatePostModalFromData(idPost, idUser, targetDiv);
}

// Função para carregar dados do post
async function loadPostData(idPost, idLiker) {
    try {


        const [postRes, likeRes] = await Promise.all([
            fetch(`getPostDataJS.php?query=${encodeURIComponent(idPost)}`),
            fetch(`checkIfPostLikedJS.php?query1=${encodeURIComponent(idLiker)}&query2=${encodeURIComponent(idPost)}`)
        ]);


        const postData = await postRes.json();
        const isLiked = await likeRes.json();

     

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
        const imageDetails = await imageRes.json();



        // Atualizar o modal com os dados carregados
        return {postData, userData, imageDetails, isLiked};

    } catch (error) {
        console.error('Erro ao carregar post:', error);
    }
}

// Função auxiliar para atualizar o modal (baseada na updatePostModal original)
async function updatePostModalFromData(idPost, idUser,  targetDiv) {

    const { postData, userData, imageDetails, isLiked} = await loadPostData(idPost, idUser);

    if (Object.keys(imageDetails).length != 0) {

        const mimeFilename = imageDetails.mimeFilename;
  

        if (targetDiv) {

            targetDiv.innerHTML = getMultimediaFileHTML(mimeFilename, imageDetails);
            

            // Atualizar dados do usuário
            document.getElementById("modalUsername").textContent = userData.username;
            document.getElementById("likeCount").textContent = postData.numLikes;
            document.getElementById("commentCount").textContent = postData.numComments;

            // Atualizar foto de perfil
            const profilePicElement = document.getElementById("modalProfilePic");
            if (userData.profilePicture) {
                profilePicElement.src = `showFile.php?id=${userData.profilePicture}`;
            } else {
                profilePicElement.src = "images/default-profile.png";
            }
            profilePicElement.alt = `${userData.username}'s profile picture`;

            // Atualizar caption com username e descrição
            const captionUsername = document.getElementById("captionUsername");
            const captionText = document.getElementById("captionText");
            
            if (captionUsername) {
                captionUsername.textContent = userData.username || 'Unknown User';
            }
            
            if (captionText) {
                captionText.textContent = postData.description || '';
            }

            // Atualizar título do post
            const postTitleElement = document.getElementById("modalPostTitle");
            if (postTitleElement) {
                postTitleElement.textContent = postData.title || '';
            }

            const likeButton = document.getElementById("likeButton");
            if(likeButton){

                if(isLiked){
                    likeButton.classList.add('liked');
                }else{
                    likeButton.classList.remove('liked');
                }
            }
        }
    }
}


function getMultimediaFileHTML(mimeFilename, imageDetails){

    let innerHTML = "";

    switch (mimeFilename) {
        case 'image':
            innerHTML = `<img src="showFile.php?id=${imageDetails.id}" alt="Post">`;
            break;

        case 'video':
            innerHTML = `
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
            innerHTML = `
                    <div class="audio-post">
                        <audio controls>
                            <source src="showFile.php?id=${imageDetails.id}" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                    `;
            break;
    }

    return innerHTML;

}





// Função para toggle like
async function toggleLike(idUser) {

    const idPost = document.getElementById("modalMediaContainer").dataset.postId;

    const currentIsLikedRes = await fetch(`checkIfPostLikedJS.php?query1=${encodeURIComponent(idUser)}&query2=${encodeURIComponent(idPost)}`)
    const currentIsLiked = await currentIsLikedRes.json();
    
    let toggleLikeRes;
    if(currentIsLiked){
        toggleLikeRes = await fetch(`dislikePostJS.php?query1=${encodeURIComponent(idUser)}&query2=${encodeURIComponent(idPost)}`);
    }else{
        toggleLikeRes = await fetch(`likePostJS.php?query1=${encodeURIComponent(idUser)}&query2=${encodeURIComponent(idPost)}`);
    }

    const toggleLike = await toggleLikeRes.json();

    if (toggleLike) {

        

        const likeButton = document.getElementById("likeButton");
        if (likeButton) {

            if (currentIsLiked) {
                likeButton.classList.remove('liked');
            } else {
                likeButton.classList.add('liked');
            }
        }

        const { postData, userData, imageDetails, isLiked} = await loadPostData(idPost, idUser);

        document.getElementById("likeCount").textContent = postData.numLikes;


        

    } else {
        console.log("Something was wrong with the like/dislike process");
    }





   
    
    
}



function handleShare() {
    if (navigator.share) {
        navigator.share({
            title: document.getElementById('modalPostTitle').textContent,
            url: window.location.href
        });
    } else {
        // Fallback para browsers que não suportam Web Share API
        navigator.clipboard.writeText(window.location.href);
        alert('Link copiado para a área de transferência!');
    }
}


// Função auxiliar para obter ID do post atual
function getCurrentPostId() {
    // Implementar lógica para obter o ID do post atual
    return document.getElementById('postModal').dataset.currentPostId;
}

// Função auxiliar para formatar tempo
function formatTime(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diff = now - date;
    
    if (diff < 60000) return 'Just now';
    if (diff < 3600000) return `${Math.floor(diff / 60000)}m`;
    if (diff < 86400000) return `${Math.floor(diff / 3600000)}h`;
    return `${Math.floor(diff / 86400000)}d`;
}

function togglePostPrivacy() {
    isPostPublic = !isPostPublic;
    const button = document.querySelector('#postMenu button:nth-child(2)');
    if (isPostPublic) {
        button.innerHTML = '<i class="bi bi-lock"></i> Make Private';
        // Aqui você pode chamar uma função para tornar o post público no backend
    } else {
        button.innerHTML = '<i class="bi bi-unlock"></i> Make Public';
        // Aqui você pode chamar uma função para tornar o post privado no backend
    }
}