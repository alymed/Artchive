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
    document.getElementById("upload-file").value = "";
    document.getElementById("upload-title").value = "";
    document.getElementById("upload-category").value = "";
    document.getElementById("upload-description").value = "";

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

// Função para fechar o modal
function closePost() {
    const modal = document.getElementById('postModal');
    modal.classList.remove('show');
    document.body.style.overflow = 'auto'; // Restaura scroll da página
}

// Função para abrir o modal
function openPost(postId, userId) {
    const modal = document.getElementById('postModal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden'; // Previne scroll da página

    // Carregar os dados do post
    loadPostData(postId, userId);
}

// Função para carregar dados do post
async function loadPostData(postId, userId) {
    try {
        const postResponse = await fetch(`getPostDataJS.php?query=${encodeURIComponent(postId)}`);
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

        // Store current post ID in modal for later use
        const modal = document.getElementById("postModal");
        if (modal) {
            modal.dataset.currentPostId = postId;
        }

        // Atualizar o modal com os dados carregados
        updatePostModalFromData(postData, userData, imageData, userId);

    } catch (error) {
        console.error('Erro ao carregar post:', error);
    }
}

// Função auxiliar para atualizar o modal
function updatePostModalFromData(postData, userData, imageDetails, userId) {
    if (imageDetails && Object.keys(imageDetails).length > 0) {
        const mimeFilename = imageDetails.mimeFilename;
        const targetDiv = document.getElementById("modalMediaContainer");

        if (targetDiv) {
            switch (mimeFilename) {
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
        }
    }

    // Atualizar botão de like com verificação
    const likeButton = document.getElementById("likeButton");
    if (likeButton && postData.id) {
        likeButton.href = "likePost.php?idPost=" + postData.id;
    }
    
    // Atualizar dados do usuário com verificações
    const modalUsername = document.getElementById("modalUsername");
    if (modalUsername) {
        modalUsername.textContent = userData.username || 'Unknown User';
    }

    const likeCount = document.getElementById("likeCount");
    if (likeCount) {
        likeCount.textContent = postData.numLikes || 0;
    }
    
    // Atualizar foto de perfil
    const profilePicElement = document.getElementById("modalProfilePic");
    if (profilePicElement) {
        if (userData.profile_pic) {
            profilePicElement.src = userData.profile_pic;
        } else {
            profilePicElement.src = "images/profilePicHandler.jpg";
        }
        profilePicElement.alt = `${userData.username || 'User'}'s profile picture`;
    }

    // Atualizar caption com verificações
    const captionUsername = document.getElementById("captionUsername");
    if (captionUsername) {
        captionUsername.textContent = userData.username || 'Unknown User';
    }
    
    const captionText = document.getElementById("captionText");
    if (captionText) {
        captionText.textContent = postData.description || '';
    }

    // Atualizar título do post
    const postTitleElement = document.getElementById("modalPostTitle");
    if (postTitleElement) {
        postTitleElement.textContent = postData.title || '';
    }

    // Atualizar botão de privacidade se o usuário for o dono
    updatePrivacyButton(postData, userId);
}

// Função para atualizar o botão de privacidade
function updatePrivacyButton(postData, userId) {
    const postMenu = document.getElementById("postMenu");
    if (!postMenu || !postData) return;

    // Procurar pelo botão de privacidade existente (buscar por qualquer botão que contenha "Make")
    let privacyButton = postMenu.querySelector('button:nth-child(2)'); // Segundo botão no menu
    
    // Se não encontrar, criar o botão
    if (!privacyButton) {
        privacyButton = document.createElement('button');
        postMenu.appendChild(privacyButton);
    }
    
    // Verificar se o usuário é o dono do post
    if (postData.idUser == userId) {
        privacyButton.style.display = 'block';
        
        // Configurar o botão baseado na privacidade atual
        if (postData.privacy === 'public') {
            privacyButton.innerHTML = '<i class="bi bi-lock"></i> Make Private';
        } else {
            privacyButton.innerHTML = '<i class="bi bi-unlock"></i> Make Public';
        }
    }
    // Se o usuário não for o dono do post, esconder o botão
    else {
        privacyButton.style.display = 'none';
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
    const modal = document.getElementById('postModal');
    return modal ? modal.dataset.currentPostId : null;
}

// Função para toggle do menu do post
function togglePostMenu() {
    const menu = document.getElementById('postMenu');
    if (menu) {
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }
}