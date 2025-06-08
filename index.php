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
    

    //TODO: If you want to change the way errors are shown change here
    window.addEventListener("DOMContentLoaded", () => {

        const params = new URLSearchParams(window.location.search);

        if (params.has("signupStep")) {
            const step = params.get("signupStep");

            if (step === "1") {
                openSignupForm();
            } else if (step === "2") {
                openSignupForm2();

                const name = params.get("name");
                const email = params.get("email");
                const birthdate = params.get("birthdate");

                if (email) {
                    document.getElementById("register_name2").value = name;
                    document.getElementById("register_email2").value = email;
                    document.getElementById("register_birthdate2").value = birthdate;
                }
            }
        }

        if (params.has("signupError")) {
            alert("Signup Error: " + params.get("signupError"));
            openSignupForm();
        }

        if (params.has("loginError")) {
            alert("Login Error: " + params.get("loginError"));
            openLoginForm();
            
        }

        // Clean up the URL
        window.history.replaceState({}, document.title, window.location.pathname);
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

    document.addEventListener("DOMContentLoaded", function () {
        const carousel = document.getElementById("carouselExampleIndicators");

        // Chamada inicial para mostrar os cards do primeiro slide
        updateImageGrid(0);

        // Adiciona o listener para quando o slide muda
        carousel.addEventListener("slid.bs.carousel", function (event) {
            const activeIndex = event.to; // novo índice do slide
            updateImageGrid(activeIndex);
        });
    });


    document.addEventListener("DOMContentLoaded", function () {
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
        carousel.addEventListener('slid.bs.carousel', function (event) {
            // const activeItem = carousel.querySelector(".carousel-item.active");
            // const allItems = carousel.querySelectorAll(".carousel-item");
            // const activeIndex = Array.from(allItems).indexOf(activeItem);
            const activeIndex = event.to; // novo índice do slide

            updateImageGrid(activeIndex);
        });
    });
</script>