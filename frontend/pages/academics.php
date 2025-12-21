<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>GSOB INDATWA | Academics</title>
  <link rel="icon" type="image/x-icon" href="/assets/images/logo.jpg" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="google-adsense-account" content="ca-pub-3738844276852721" />

  <!-- <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3738844276852721"
    crossorigin="anonymous"></script> -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- <script src="/assets/js/script.js" defer></script> -->

  <link rel="stylesheet" href="/assets/css/style.css" />
  <!-- <link rel="stylesheet" href="/assets/css/homepage.css" />
  <link rel="stylesheet" href="/assets/css/style_Accademics.css" />
  <link rel="stylesheet" href="/assets/css/screens.css" />
  <link rel="stylesheet" href="/assets/css/styles.css" /> -->
  <link rel="stylesheet" href="/assets/css/screens.css" />
  <link rel="stylesheet" href="/assets/css/style_Accademics.css" />

  <style>
    body {
      background-color: #f4f6f8;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding-top: 0;
      margin: 0;
      /* Reduced padding-top to give more room */
    }

    .contact-container {
      max-width: 800px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: #007BFF;
      margin-bottom: 20px;
    }

    /* Message alert styles */
    .alert-slide-up {
      animation: slideUp 0.5s forwards;
    }

    @keyframes slideUp {
      0% {
        opacity: 1;
        transform: translateY(0);
      }

      100% {
        opacity: 0;
        transform: translateY(-20px);
      }
    }

    .contact-info a {
      color: #007BFF;
      text-decoration: none;
    }

    .contact-info a:hover {
      text-decoration: underline;
    }

    .dropdown-item {
      color: steelblue;
    }

    /* Make dropdown menus visible and operable on large screens (>= 992px) */
    @media (min-width: 992px) {

      /* Keep dropdown open on hover */
      .dropdown:hover>.dropdown-menu {
        display: block !important;
        opacity: 1;
        visibility: visible;
        position: absolute;
        top: 100%;
        left: 0;
        margin-top: 0.5rem;
        z-index: 1050;
        /* Ensure on top */
      }

      /* Remove any conflicting display rule */
      .dropdown-menu {
        display: none;
      }

      /* Show on hover */
      .dropdown:hover>.dropdown-menu {
        display: block !important;
      }
    }

    /* Style your links with a pale blue for a professional look */
    .navbar-nav .nav-link,
    .dropdown-toggle {
      color: steelblue;
      /* Pale blue */
      transition: color 0.3s ease;
    }

    .navbar-nav .nav-link:hover,
    .dropdown-toggle:hover,
    .navbar-nav .nav-link:focus,
    .dropdown-toggle:focus {
      color: steelblue;
      /* Lighter blue on hover/focus */
    }
  </style>
</head>

<body>
  <!-- ======= Academics Section ======= -->
  <?php include 'navbar.php' ?>

  <!-- Academics Slider -->
  <section class="academicswrapper">
    <div class="rt-container">
      <div class="col-rt-12">
        <div id="slider">
          <div class="slides">
            <div class="slider">
              <div class="legend"></div>
              <div class="content">
                <div class="content-txt">
                  <h2>Welcome to academic excellence.</h2>
                </div>
              </div>
              <div class="image">
                <img src="/assets/images/facilities5.JPG" alt="Facility" />
              </div>
            </div>

            <div class="slider">
              <div class="legend"></div>
              <div class="content">
                <div class="content-txt">
                  <h2>Knowledge meets opportunity.</h2>
                </div>
              </div>
              <div class="image">
                <img src="/assets/images/labo2.jpg" alt="Lab" />
              </div>
            </div>

            <div class="slider">
              <div class="legend"></div>
              <div class="content">
                <div class="content-txt">
                  <h2>Curiosity thrives, minds ignite.</h2>
                </div>
              </div>
              <div class="image">
                <img src="/assets/images/ctc.JPG" alt="Classroom" />
              </div>
            </div>

            <div class="slider">
              <div class="legend"></div>
              <div class="content">
                <div class="content-txt">
                  <h2>Step into a success-bound world of lessons.</h2>
                </div>
              </div>
              <div class="image">
                <img src="/assets/images/facilities3.jpg" alt="School facility" />
              </div>
            </div>
          </div>

          <div class="switch">
            <ul>
              <li>
                <div class="on"></div>
              </li>
              <li></li>
              <li></li>
              <li></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Top Section -->
  <section class="top">
    <p class="text-center">
    <h2>A hub of intellectual challenge</h2>
    <p>GSO Butare nurtures a lasting passion for learning among its students... empowering them with the aptitudes
      needed to confront an ever-evolving global landscape.</p>
    </p>
  </section>

  <!-- Options Section -->
  <section class="options">
    <div>
      <h2>Our Options</h2>
      <h4>Ordinary Level</h4>
      <h4>Advanced Level</h4>
    </div>

    <div>
      <h2>School requirements document</h2>
      <button class="babyeyi-btn">Babyeyi</button>
    </div>
  </section>

  <!-- Babyeyi PDF -->
  <div class="container" style="display:none;">
    <div class="iframe-wrapper">
      <iframe src="/assets/Documents/Babyeyi.pdf" frameborder="0"></iframe>
      <span>
        <p class="quit">X</p>
      </span>
    </div>
  </div>

  <!-- Combinations Section -->
  <section class="comb">
    <p class="text-center">
    <h2>Combinations</h2>
    <p>GSO Butare provides outstanding schooling for both O'level and four scientific combinations.</p>
    </p>

    <div class="cards-wrapper">
      <div class="card">
        <div class="face front">
          <h1>MPC</h1>
          <figcaption>Mathematics Physics and Computer Science</figcaption>
        </div>
        <div class="face back">
          <img src="/assets/images/code.jpg" alt="MPC" />
        </div>
      </div>

      <div class="card">
        <div class="face front">
          <h1>PCM</h1>
          <figcaption>Physics Chemistry and Mathematics</figcaption>
        </div>
        <div class="face back">
          <img src="/assets/images/maths.jpg" alt="PCM" />
        </div>
      </div>

      <div class="card">
        <div class="face front">
          <h1>PCB</h1>
          <figcaption>Physics Chemistry and Biology</figcaption>
        </div>
        <div class="face back">
          <img src="/assets/images/phy.jpg" alt="PCB" />
        </div>
      </div>

      <div class="card">
        <div class="face front">
          <h1>MCB</h1>
          <figcaption>Mathematics Chemistry and Biology</figcaption>
        </div>
        <div class="face back">
          <img src="/assets/images/chem.jpg" alt="MCB" />
        </div>
      </div>

      <div class="card">
        <div class="face front">
          <h1>ANP</h1>
          <figcaption>Associate Nursing Programme</figcaption>
        </div>
        <div class="face back">
          <img src="/assets/images/nurse.jpg" alt="ANP" />
        </div>
      </div>
    </div>
  </section>

  <!-- Facilities Section -->
  <section class="facilities reveal">
    <p class="text-center">
    <h2>Our Amenities</h2>
    <p class="top">Our School stands among the top for its superior facilities, enhancing both learning and
      enjoyment.
    </p>
    </p>

    <div class="facilwrap">
      <article class="facil">
        <h3>Computer Labs</h3>
        <p>Promoting tech literacy, offering tools for research, creativity, and innovation.</p>
      </article>

      <article class="facil">
        <h3>Science Labs</h3>
        <p>Hands-on learning and discovery empower students to explore scientific concepts.</p>
      </article>

      <article class="facil">
        <h3>Library</h3>
        <p>Fuels academic excellence and fosters a culture of learning.</p>
      </article>

      <article class="facil">
        <h3>Smart Classes</h3>
        <p>Interactive technology empowers educators for dynamic teaching.</p>
      </article>

      <article class="facil">
        <h3>Clinical Environment</h3>
        <p>Provides healthcare students with essential tools and experience for patient care.</p>
      </article>
    </div>
  </section>

  <!-- Footer -->
  <footer class="alumni1">
    <div class="row">
      <div class="column" id="column10">
        <h3>Quick links:</h3>
        <p>
          <a href="/index">Home</a>
        </p>
        <p>
          <a href="#">Our school</a>
        </p>
        <p>
          <a href="/academics">Academics</a>
        </p>
        <p>
          <a href="/extracurricular">Extracurricular</a>
        </p>
        <p>
          <a href="/news">Updates</a>
        </p>
      </div>

      <div class="column" id="column11">
        <h3>Home values:</h3>
        <p>Excellence</p>
        <p>Cleanliness</p>
        <p>Responsibility</p>
        <p>Empowerment</p>
        <p>Innovation</p>
      </div>

      <div class="column" id="column12">
        <h3>What we prefer:</h3>
        <p>God</p>
        <p>Studies</p>
        <p>Discipline</p>
        <p>Sports</p>
        <p>Peace</p>
      </div>

      <div class="column" id="column-sub">
        <h3>Subscribe for our newsletter:</h3>
        <input type="text" placeholder="Enter your email here..." />
        <input type="submit" value="Submit" />
        <h4 style="color:#6495ED; margin-bottom:10%;">You’ll receive all updates via email!</h4>

        <div class="tooltip-container">
          <button>Reach developers</button>
          <div class="tooltip">
            <div class="row">
              <a href="https://www.instagram.com/c.a.s___t.r.o" target="_blank">@Castro</a>
              <a href="https://www.instagram.com/kfa___brice1" target="_blank">@KFabrice</a>
            </div>
            <div class="row">
              <a href="https://www.instagram.com/other.guy34" target="_blank">@Tris</a>
              <a href="https://www.instagram.com/_janson__/" target="_blank">@Hush</a>
            </div>
          </div>
        </div>
      </div>

      <div class="copyright">
        <p class="text-center">
          &copy; 2025 All rights reserved — <a href="#">By Indatwa</a>
        </p>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const container = document.querySelector('.container')
    const babyeyiBtn = document.querySelector('.babyeyi-btn')
    const quit = document.querySelector('.quit')

    babyeyiBtn.onclick = () => (container.style.display = 'block')
    quit.onclick = () => (container.style.display = 'none')
  </script>

</body>

</html>