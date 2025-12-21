<?php
// Determine current path for active links
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);
$path = rtrim($path, '/'); // remove trailing slash
if ($path === '' || $path === '/index.php' || $path === '/index') $path = '/index';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="google-adsense-account" content="ca-pub-3738844276852721" />
  <title>GSOB INDATWA | Home</title>
  <link rel="icon" type="image/x-icon" href="/assets/images/logo.jpg" />

  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3738844276852721"
    crossorigin="anonymous"></script>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

  <link rel="stylesheet" type="text/css" href="/assets/css/homepage.css" />
  <link rel="stylesheet" type="text/css" href="/assets/css/styles.css" />
  <link rel="stylesheet" type="text/css" href="/assets/css/screens.css" />
  <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
  <link rel="stylesheet" type="text/css" href="/assets/css/style_index.css" />
  <link rel="stylesheet" type="text/css" href="/assets/css/style_Home.css" />

  <style>
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

  <!-- Navbar Reference -->
  <?php include 'navbar.php' ?>

  <!--Home wrapper Section  -->
  <section class="homewrapper">
    <div class="rt-container">
      <div class="col-rt-12">
        <div id="slider">
          <div class="slides">
            <div class="slider">
              <div class="legend"></div>
              <div class="content">
                <div class="content-txt">
                  <h2 style="font-size:15px;margin-top:2%">
                    Today on 10 May 2025, At GSO Butare Indatwa n'Inkesha, we held an event of school of 95 years.
                    <div style="font-size:17px;color:blue">
                      <a href="/news">View more</a>
                    </div>
                  </h2>
                </div>
              </div>
              <div class="image">
                <div class="image">
                  <img src="/assets/images/schlday/day2.JPG" />
                </div>
              </div>
            </div>
            <div class="slider">
              <div class="legend"></div>
              <div class="content">
                <div class="content-txt">
                  <h2>Welcome to our vibrant learning community!</h2>
                  <p></p>
                </div>
              </div>
              <div class="image">
                <img src="/assets/images/school5.JPG" />
              </div>
              <div class="image">
                <img src="/assets/images/isong1.jpg" />
              </div>
            </div>
            <div class="slider">
              <div class="legend"></div>
              <div class="content">
                <div class="content-txt">
                  <h2>A world of knowledge and opportunity.</h2>
                  <p></p>
                </div>
              </div>
              <div class="image">
                <img src="/assets/images/student2.jfif" />
              </div>
            </div>
            <div class="slider">
              <div class="legend"></div>
              <div class="content">
                <div class="content-txt">
                  <h2>Opening doors to a brighter future.</h2>
                  <p></p>
                </div>
              </div>
              <div class="image">
                <img src="/assets/images/school3.JPG" />
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

  <!-- FTS Challenge Section -->
  <section class="popup-section" id="custom-popup-trigger">
    <div class="popup-content">
      <div style="margin:1% 0">
        <h2 style="color:white">FTS challenges</h2>
        <p></p>
        <div class="schlmgzn">
          <p>
            <a href="/assets/Documents/FTS/challenge">Click here to go to FTS challenges</a>
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Discover Section -->
  <section>
    <p class="text-center">
    <h2>
      Discover GSOB
    </h2>
    <p id="welc">
      Unleash your potential at GSOB! Explore our engaging academics, from challenging courses to creative projects.
      Find your crew in our exciting clubs and activities. Stay informed about upcoming events and discover new ways
      to
      get involved. We're excited to welcome you to GSOB!
    </p>
    </p>
  </section>

  <!-- Intro Section -->
  <section class="intro py-5">
    <div class="container">
      <div class="row g-4">

        <!-- Box 1 -->
        <div class="col-md-4">
          <div class="card text-center shadow-sm h-100 border-0">
            <div class="card-body">
              <figure class="mb-3">
                <img src="/assets/images/bmind.png" alt="Uncover your path" class="img-fluid mb-2"
                  style="height: 80px; width: 80px;" />
                <figcaption class="fw-semibold">Uncover your path.</figcaption>
              </figure>
              <p class="text-muted">
                Dive into our challenging programs and ignite your curiosity at GSOB. Empower your future through our
                enriching academic experience.
              </p>
              <a href="/academics" class="btn btn-primary mt-3" style="background-color: blue;">View more</a>
            </div>
          </div>
        </div>

        <!-- Box 2 -->
        <div class="col-md-4">
          <div class="card text-center shadow-sm h-100 border-0">
            <div class="card-body">
              <figure class="mb-3">
                <img src="/assets/images/relaxvolley.png" alt="Unleash your talents" class="img-fluid mb-2"
                  style="height: 80px; width: 80px;" />
                <figcaption class="fw-semibold">Unleash your talents.</figcaption>
              </figure>
              <br />
              <p class="text-muted">
                Find your crew and explore your interests! <br />
                Join GSOB's community's vibrant extracurricular scene.
              </p>
              <a href="/extracurricular" class="btn btn-primary mt-3" style="background-color: blue;">View more</a>
            </div>
          </div>
        </div>

        <!-- Box 3 -->
        <div class="col-md-4">
          <div class="card text-center shadow-sm h-100 border-0">
            <div class="card-body">
              <figure class="mb-3">
                <img src="/assets/images/newsicon.png" alt="Get the latest" class="img-fluid mb-2"
                  style="height: 80px; width: 80px;" />
                <figcaption class="fw-semibold">Get the latest.</figcaption>
              </figure>
              <br />
              <p class="text-muted">
                School buzz! Get the latest news and announcements about exciting
                events happening at GSOB.
              </p>
              <a href="/news" class="btn btn-primary mt-3" style="background-color: blue;">View more</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- Learnmo reveal Section-->
  <section class="learnmo reveal">
    <p style="text-align:center;">
    <h2>
      Holistic learning environment.
    </h2>
    <p>
      Craving a great education? GSOB goes beyond lectures. Explore engaging academics that spark curiosity. Develop
      new
      skills and knowledge in our diverse clubs. Connect with passionate peers who share your drive. At GSOB, your
      academic journey is just the beginning!
    </p>
    </p>
  </section>

  <!-- msg reveal Section-->
  <section class="msg reveal">
    <div class="imgwrapp">
      <img src="/assets/images/father.jpg" height="450" width="500" />
    </div>
    <aside>
      <h3 class="hd" style="color: black;">
        Principal's Message
      </h3>

      <p style="color: black;">
        I'm thrilled to begin another enriching year with our dedicated students, supportive parents, and talented
        staff. At our school, we prioritize not only academic excellence but also the holistic development of each
        student through a variety of extracurricular activities and opportunities for personal growth. Our passionate
        educators are committed to nurturing critical thinking and creativity, while our staff ensures a safe and
        supportive environment for all. Together, we celebrate diversity, foster integrity, and encourage every
        student
        to embrace challenges and discover their unique talents. I look forward to a year of achievement,
        collaboration,
        and memorable experiences as we continue to uphold our commitment to excellence.
      </p>
      <h4>
        Fr. HAKIZIMANA Charles<br /><span class="cap">Head of School</span>
      </h4>
    </aside>
  </section>

  <!-- Testm reveal Section-->
  <section class="testm reveal text-center">
    <div class="qtwrapp">
      <p style="text-align:center;">
      <figure>
        <img src="/assets/images/dos1.JPG" height="100" width="100" />
        <figcaption>
          HABIMANA John doe <br />
        </figcaption>
      </figure>
      <p>
        " Dear Students, Parents, and Faculty, It is with great pleasure and anticipation that I welcome you to
        another
        exciting academic year at GSOB Indatwa n'inkesha. Together, let us continue our journey of learning, growth,
        and
        excellence in both academics and personal development. "<br /> <span class="btmcap">-Head of studies</span>
      </p>
      </p>
    </div>
  </section>

  <!-- Counter Section -->
  <section class="countsection">
    <p style="text-align:center;">
    <div class="counters">
      <div class="counter" id="counter1">
        <div id="counter1Value">
          <br /> 0
        </div>
        <div class="counter-name">
          All students
        </div>
      </div>
      <div class="counter" id="counter2">
        <div id="counter2Value">0</div>
        <div class="counter-name">Total staff</div>
      </div>
      <div class="counter" id="counter3">
        <div id="counter3Value">0</div>
        <div class="counter-name">Mean class size</div>
      </div>
      <div class="counter" id="counter4">
        <div id="counter4Value">0</div>
        <div class="counter-name">Legacy in time</div>
      </div>
    </div>
    </p>
  </section>

  <!-- Snapshot Section -->
  <section class="snapsection">
    <h2>
      A Snapshot of our community
    </h2>
    <p class="mainhead">
      At GSOB, we thrive on sportsmanship, teamwork, and a zest for learning beyond the classrooms.
    </p>
    <div class="snapwrapper">
      <article class="snap">
        <img src="/assets/images/basket.jpg" height="250" width="100%" />
        <p class="headline">
          Games
        </p>
        <p class="caption">
          Games at our school are more than recreation they're a journey of teamwork, resilience, and lasting
          friendships. Whether on courts or fields, we champion sportsmanship and skill development in every student
        </p>
      </article>
      <article class="snap">
        <img src="/assets/images/ctc.JPG" height="250" width="100%" />
        <p class="headline">
          Inspiring Innovation
        </p>
        <p class="caption">
          Projects at our school go beyond tasks they inspire creativity, collaboration, and achievement.Each project
          embodies our commitment to fostering ingenuity and skill development in every student.
        </p>
      </article>
      <article class="snap">
        <img src="/assets/images/balet.jpg" height="250" width="100%" />
        <p class="headline">
          Embracing Culture
        </p>
        <p class="caption">
          At GSOB , we celebrate passions that move us. Our students shine as they embrace traditional dance and more
          others, embodying the spirit of our vibrant community.
        </p>
      </article>
    </div>
  </section>

  <!-- Footer Section -->
  <footer class="alumni1 bg-black text-white pt-5 pb-3 mt-5"
    style="background-image: linear-gradient(to  left, rgb(27, 6, 165),rgba(16, 3, 101, 1));">
    <div class="container">
      <div class="row">
        <!-- Quick links -->
        <div class="col-md-3 col-sm-6 mb-4 text-white">
          <h5>Quick Links</h5>
          <ul class="list-unstyled text-white">
            <li class="text-white"><a class="text-white" href="/index">Home</a></li>
            <li class="text-white"><a class="text-white" href="#">Our School</a></li>
            <li class="text-white"><a class="text-white" href="/academics">Academics</a></li>
            <li class="text-white"><a class="text-white" href="/extracurricular">Extracurricular</a></li>
            <li class="text-white"><a class="text-white" href="/news">Updates</a></li>
          </ul>
        </div>

        <!-- Home values -->
        <div class="col-md-3 col-sm-6 mb-4">
          <h5>Home Values</h5>
          <ul class="list-unstyled">
            <li>Excellence</li>
            <li>Cleanliness</li>
            <li>Responsibility</li>
            <li>Empowerment</li>
            <li>Innovation</li>
          </ul>
        </div>

        <!-- What we prefer -->
        <div class="col-md-3 col-sm-6 mb-4">
          <h5>What We Prefer</h5>
          <ul class="list-unstyled">
            <li>God</li>
            <li>Studies</li>
            <li>Discipline</li>
            <li>Sports</li>
            <li>Peace</li>
          </ul>
        </div>

        <!-- Newsletter -->
        <div class="col-md-3 col-sm-6 mb-4 w-40">
          <div class="footer-newsletter p-3 rounded shadow-sm bg-white">
            <h5 class="text-primary mb-2">Subscribe</h5>
            <p class="small text-muted mb-2">Get updates and news delivered to your inbox</p>
            <form id="newsletterForm" class="needs-validation" novalidate>
              <input type="text" id="newsletterFullName" name="fullNames" class="form-control form-control-sm mb-2"
                placeholder="Full Name" required>
              <input type="email" id="newsletterEmail" name="email" class="form-control form-control-sm mb-2"
                placeholder="Email Address" autocomplete="email" required>
              <div class="form-check mb-2">
                <input type="checkbox" class="form-check-input" id="newsletterCheckbox" required>
                <label class="form-check-label small text-black" for="newsletterCheckbox">
                  I agree to receive newsletter emails
                </label>
              </div>
              <button type="submit" id="newsletterSubmitButton" class="btn btn-primary btn-sm w-100">Subscribe</button>
            </form>
            <small class="d-block mt-2 text-muted">Updates, tips, and exclusive offers.</small>
          </div>
        </div>
        <div class="text-center mt-4 small text-muted">
          &copy; <span id="year"></span> GSOB Indatwa | By Indatwa
        </div>
      </div>
    </div>
  </footer>
  <!-- Bootstrap JS Bundle (only once!) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
  </script>


  <!--Gloab Toast JS  -->
  <script src="/assets/js/navigator.js" defer></script>
  <script src="/assets/js/index.js" defer></script>
  <script src="/assets/js/script.js" defer></script>
</body>

</html>