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
    <link rel="stylesheet" href="css/styleIndex.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="images/logo.png">
    <script src="js/script.js"></script>

</head>

<body>
    <div class="form-overlay" id="formOverlay"></div>
    <nav class="custom-navbar">
        <div class="navbar-container">
            <div class="navbar">
                <div class="navbar-left">
                    <a href="index.php">
                        <img src="images/artchive 2.png" alt="Logo" class="logo-img">
                    </a>
                    <a href="exploreGuest.php" class="navbar-brand" id="explore">Explore</a>
                </div>
                <div class="navbar-right">
                    <button class="navbar-brand" id="log" onclick="openLoginForm()">Log in</button>

                    <button class="navbar-brand" id="sign" onclick="openSignupForm()">Sign up</button>
                </div>
            </div>
        </div>
    </nav>

    <main class="hero">
        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner">
                <h1>Get your next</h1>

                <div class="carousel-item active">
                    <h2>camera angle</h2>
                </div>
                <div class="carousel-item">
                    <h2>photoshot inspiration</h2>
                </div>
                <div class="carousel-item">
                    <h2>track</h2>
                </div>
            </div>

            <div class="carousel-indicators custom-dots">
                <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>
        </div>

        <div class="parent-container">

            <div class="img_container">
                <div class="card card_medium slide-0"><img src="images/movie1.jpg"></div>
                <div class="card card_medium slide-0"><img src="images/movie2.jpg"></div>
                <div class="card card_medium slide-0"><img src="images/movie4.jpg"></div>
                <div class="card card_medium slide-0"><img src="images/movie3.jpg"></div>

                <div class="card card_medium slide-2"><img src="images/music1.jpg"></div>
                <div class="card card_medium slide-2"><img src="images/music2.jpg"></div>
                <div class="card card_medium slide-2"><img src="images/music3.jpg"></div>
                <div class="card card_medium slide-2"><img src="images/music4.jpg"></div>

                <div class="card card_medium slide-1"><img src="images/pic1.jpg"></div>
                <div class="card card_medium slide-1"><img src="images/pic2.jpg"></div>
                <div class="card card_medium slide-1"><img src="images/pic3.jpg"></div>
                <div class="card card_medium slide-1"><img src="images/pic4.jpg"></div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS and Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>


    <?php
        include 'auth_forms.php';
    ?>


</body>

</html>


<script>
window.addEventListener("DOMContentLoaded", () => {

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Handle URL parameters on page load
        handleUrlParameters();

        // Initialize character counter
        updateBiographyCounter();

        // Initialize file validation
        validateFileSize();

        // Close forms when clicking on overlay
        const overlay = document.getElementById("formOverlay");
        if (overlay) {
            overlay.addEventListener("click", function() {
                closeAllForms();
            });
        }

        // Prevent form closing when clicking inside the form
        document.querySelectorAll(".form-popup").forEach(function(form) {
            form.addEventListener("click", function(e) {
                e.stopPropagation();
            });
        });

        // Age validation for birthdate
        const birthdateInput = document.getElementById('register_birthdate');
        if (birthdateInput) {
            birthdateInput.addEventListener('change', function() {
                const birthDate = new Date(this.value);
                const today = new Date();
                const age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();

                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate
                .getDate())) {
                    age--;
                }

                if (age < 13) {
                    alert('You must be at least 13 years old to register.');
                    this.value = '';
                }

                if (birthDate > today) {
                    alert('Birthdate cannot be in the future.');
                    this.value = '';
                }
            });
        }

        const passwordInput = document.getElementById('register_password');
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                const hasLetter = /[A-Za-z]/.test(password);
                const hasNumber = /\d/.test(password);
                const isLongEnough = password.length >= 8;

                // You can add visual feedback here
                if (isLongEnough && hasLetter && hasNumber) {
                    this.style.borderColor = 'green';
                } else {
                    this.style.borderColor = 'red';
                }
            });
        }
    });
});




function updateImageGrid(slideIndex) {
    const cards = document.querySelectorAll(".card.card_medium");

    cards.forEach(card => {
        // Remove qualquer classe antiga de animação
        card.classList.remove("fade-in-up");
        card.style.display = "none";

        if (card.classList.contains("slide-" + slideIndex)) {
            card.style.display = "block";

            // Força reflow para reiniciar a animação

        }
    });
}

document.addEventListener("DOMContentLoaded", function() {
    const carousel = document.getElementById("carouselExampleIndicators");

    // Chamada inicial para mostrar os cards do primeiro slide
    updateImageGrid(0);

    // Adiciona o listener para quando o slide muda
    carousel.addEventListener("slid.bs.carousel", function(event) {
        const activeIndex = event.to; // novo índice do slide
        updateImageGrid(activeIndex);
    });
});


document.addEventListener("DOMContentLoaded", function() {
    const imgCards = document.querySelectorAll(".card");

    function updateImageGrid(slideIndex) {
        imgCards.forEach(card => {
            card.classList.remove("fade-in-up");
            card.style.display = "none";


            if (card.classList.contains("slide-" + slideIndex)) {
                card.style.display = "block";
                void card.offsetWidth;
                card.classList.add("fade-in-up");
            } else {
                card.style.display = "none";
            }
        });
    }

    // Inicializa com o slide 0
    updateImageGrid(0);

    const carousel = document.getElementById('carouselExampleAutoplaying');
    carousel.addEventListener('slid.bs.carousel', function(event) {
        // const activeItem = carousel.querySelector(".carousel-item.active");
        // const allItems = carousel.querySelectorAll(".carousel-item");
        // const activeIndex = Array.from(allItems).indexOf(activeItem);
        const activeIndex = event.to; // novo índice do slide

        updateImageGrid(activeIndex);
    });
});
</script>