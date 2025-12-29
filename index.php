<?php
session_start();
require_once 'utils/strings.php';

if (isset($_SESSION['userID']) && !empty($_SESSION['userID'])) {
  header('Location: gradebook/index.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
  <title>Logowanie - <?php echo SCHOOL_NAME; ?></title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
  <link rel="stylesheet" href="assets/css/login_page.css">
  <link rel="icon" href="<?php echo BASE_URL; ?>/assets/favicon.svg">
</head>

<body>

  <div class="login-panel">

    <div class="login-section">
      <div class="login-header">
        <h1><?php echo SCHOOL_NAME;  ?></h1>
        <p>Panel logowania do dziennika</p>
      </div>

      <form class="login-form" action="login/login.php" method="POST" onsubmit="return validateGrade()">
        <input type="text" name="username" placeholder="Login lub e-mail" required>
        <input type="password" name="password" placeholder="Hasło" required>

        <input type="hidden" name="favorite_grade" id="fav_grade_input">

        <label class="grade-label">Twoja ulubiona ocena:</label>
        <div class="grade-selector">
          <button type="button" class="grade-btn" onclick="selectGrade(1, this)">1</button>
          <button type="button" class="grade-btn" onclick="selectGrade(2, this)">2</button>
          <button type="button" class="grade-btn" onclick="selectGrade(3, this)">3</button>
          <button type="button" class="grade-btn" onclick="selectGrade(4, this)">4</button>
          <button type="button" class="grade-btn" onclick="selectGrade(5, this)">5</button>
          <button type="button" class="grade-btn" onclick="selectGrade(6, this)">6</button>
        </div>

        <button type="submit">Zaloguj się</button>
      </form>
      <div class="forgot-password">
        <a href="#">Nie pamiętasz hasła?</a>
      </div>
      <div class="login-footer">
        &copy; <?php echo date('Y'); ?> <?php echo SCHOOL_NAME; ?> <br>Aleksander Gwiazdowski
      </div>
    </div>

    <div class="slider-section">
      <div class="slideshow-container">

        <div class="mySlides fade">
          <div class="numbertext">1 / 4</div>
          <img src="assets/img/szk1.png">
          <div class="text">Front budynku naszej szkoły</div>
        </div>

        <div class="mySlides fade">
          <div class="numbertext">2 / 4</div>
          <img src="assets/img/szk2.png">
          <div class="text">Nasza szkoła z lotu ptaka</div>
        </div>

        <div class="mySlides fade">
          <div class="numbertext">3 / 4</div>
          <img src="assets/img/szk3.png">
          <div class="text">Nasza szkoła na łonie natury</div>
        </div>

        <div class="mySlides fade">
          <div class="numbertext">4 / 4</div>
          <img src="assets/img/szk4.png">
          <div class="text">Piękny dziedziniec naszej szkoły</div>
        </div>

        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>

        <div class="dots-container">
          <span class="dot" onclick="currentSlide(1)"></span>
          <span class="dot" onclick="currentSlide(2)"></span>
          <span class="dot" onclick="currentSlide(3)"></span>
          <span class="dot" onclick="currentSlide(4)"></span>
        </div>
      </div>
    </div>

  </div>

  <script>
    let slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
      showSlides(slideIndex += n);
    }

    function currentSlide(n) {
      showSlides(slideIndex = n);
    }

    function showSlides(n) {
      let i;
      let slides = document.getElementsByClassName("mySlides");
      let dots = document.getElementsByClassName("dot");
      if (n > slides.length) {
        slideIndex = 1
      }
      if (n < 1) {
        slideIndex = slides.length
      }
      for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
      }
      for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
      }
      slides[slideIndex - 1].style.display = "block";
      dots[slideIndex - 1].className += " active";
    }

    function selectGrade(value, btnElement) {
      document.getElementById('fav_grade_input').value = value;

      const buttons = document.querySelectorAll('.grade-btn');
      buttons.forEach(btn => btn.classList.remove('selected'));

      btnElement.classList.add('selected');
    }

  </script>
</body>

</html>