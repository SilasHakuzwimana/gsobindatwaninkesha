<!DOCTYPE html>
<html>

<head>
  <title>GSOB INDATWA | School Updates</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="google-adsense-account" content="ca-pub-3738844276852721" />
  <link rel="icon" type="image/x-icon" href="/assets/images/logo.jpg" />
  <script src="/assets/js/script.js" defer></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/screens.css">
  <link rel="stylesheet" href="/assets/css/extra_activities.css">
  <link rel="stylesheet" href="/assets/css/style_ Extracurricular.css">

  <style>
    * {
      margin: 3px;
    }

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

    .wrappernews2,
    .wrappernews1 {
      display: flex;
      flex-direction: column;
      flex-wrap: wrap;
      width: 97%;
      height: fit-content;
      margin-top: 5%;
      padding: 0 1rem;
    }

    .wrappernews2 .row {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 1.5rem;
      width: 100%;
      height: fit-content;
      padding: 1rem;
    }

    .wrappernews2 .row .column {
      flex: 1 1 300px;
      max-width: 300px;
      height: 400px;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      align-items: flex-start;
      margin-bottom: 1.5rem;
      cursor: pointer;
      background-size: cover;
      background-position: center;
      border-radius: 15px;
      overflow: hidden;
      position: relative;
      transition: transform 0.3s ease;
    }

    .wrappernews2 .row .column:hover {
      transform: scale(1.05);
    }

    .wrappernews2 .row .column h3 {
      color: white;
      font-size: 1.5rem;
      font-weight: bold;
      margin: 0 0 0.5rem 1rem;
      text-transform: uppercase;
    }

    .wrappernews2 .row .column p {
      color: white;
      font-size: 0.9rem;
      margin: 0 1rem 1rem 1rem;
      line-height: 1.4;
    }

    .wrappernews2 .row .column::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
      z-index: 1;
    }

    .wrappernews2 .row .column * {
      position: relative;
      z-index: 2;
    }

    .wrappernews2 .row .column img {
      display: none;
    }

    .wrappernews2 .row .column figcaption {
      display: none;
    }

    .reveal {
      position: relative;
      transform: translateY(100px);
      opacity: 0;
      transition: 1s all ease;
    }

    .reveal.active {
      transform: translateY(0);
      opacity: 1;
    }

    @media screen and (max-width: 768px) {
      .wrappernews2 .row {
        justify-content: center;
      }

      .wrappernews2 .row .column {
        flex: 1 1 100%;
        max-width: 100%;
      }

      .wrappernews2 .row .column h3 {
        font-size: 1.3rem;
      }

      .wrappernews2 .row .column p {
        font-size: 0.8rem;
      }
    }

    @media screen and (max-width: 480px) {
      .wrappernews2 {
        padding: 0 0.5rem;
      }

      .wrappernews2 .row .column h3 {
        font-size: 1.1rem;
      }

      .wrappernews2 .row .column p {
        font-size: 0.7rem;
      }
    }

    .full-image-modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.9);
      justify-content: center;
      align-items: center;
    }

    .full-image-content {
      max-width: 90%;
      max-height: 90vh;
      display: block;
      margin: auto;
      object-fit: contain;
    }

    .full-image-close {
      position: absolute;
      top: 20px;
      right: 35px;
      color: #fff;
      font-size: 40px;
      font-weight: bold;
      cursor: pointer;
    }

    .full-image-close:hover,
    .full-image-close:focus {
      color: #bbb;
      text-decoration: none;
    }

    .nav-button {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background-color: rgba(0, 0, 0, 0.5);
      color: white;
      font-size: 30px;
      border: none;
      padding: 10px;
      cursor: pointer;
      z-index: 1001;
    }

    .prev-button {
      left: 10px;
    }

    .next-button {
      right: 10px;
    }

    .nav-button:hover {
      background-color: rgba(0, 0, 0, 0.8);
    }

    .wrappernews1 figcaption .popup-content .row .column img {
      cursor: pointer;
    }

    @media screen and (max-width: 768px) {
      .full-image-content {
        max-width: 95%;
        max-height: 85vh;
      }

      .full-image-close {
        top: 15px;
        right: 20px;
        font-size: 30px;
      }

      .nav-button {
        font-size: 25px;
        padding: 8px;
      }

      .prev-button {
        left: 5px;
      }

      .next-button {
        right: 5px;
      }
    }

    @media screen and (max-width: 480px) {
      .full-image-content {
        max-width: 98%;
        max-height: 80vh;
      }

      .full-image-close {
        top: 10px;
        right: 15px;
        font-size: 25px;
      }

      .nav-button {
        font-size: 20px;
        padding: 6px;
      }

      .prev-button {
        left: 3px;
      }

      .next-button {
        right: 3px;
      }
    }

    @media screen and (max-height: 600px) {
      .full-image-modal {
        align-items: flex-start;
        padding-top: 10px;
      }

      .full-image-content {
        max-height: 75vh;
      }
    }
  </style>
  <script>
    ;
    (function() {
      emailjs.init('YOUR_USER_ID') // Replace with your EmailJS user ID
    })()
  </script>
</head>

<body>

  <!-- Bootstrap Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container-fluid px-3 px-lg-4">
      <!-- Brand -->
      <a class="navbar-brand d-flex align-items-center" href="/index">
        <img src="/assets/images/logo.png" alt="GSO Logo" class="img-fluid me-2" style="max-height:70px; width:auto;">
        <div class="lh-sm">
          <strong class="text-primary" style="font-size: 20px;">GSO BUTARE</strong><br>
          <small class="text-muted" style="font-size: 10px;">S'INSTRUIRE POUR MIEUX SERVIR</small>
        </div>
      </a>

      <!-- Toggler for mobile -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar links -->
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-lg-center">
          <li class="nav-item"><a class="nav-link" href="/index">Home</a></li>
          <li class="nav-item"><a class="nav-link active" href="/academics">Academics</a></li>

          <!-- Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="schoolDropdown" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              Our School
            </a>
            <ul class="dropdown-menu" aria-labelledby="schoolDropdown">
              <li><a class="dropdown-item" href="/about">About us</a></li>
              <li><a class="dropdown-item" href="/alumni">Alumni</a></li>
              <li><a class="dropdown-item" href="/gallery">Gallery</a></li>
            </ul>
          </li>

          <li class="nav-item"><a class="nav-link" href="/extracurricular">Extracurricular</a></li>
          <li class="nav-item"><a class="nav-link" href="/news">School Updates</a></li>
          <li class="nav-item"><a class="nav-link" href="/contact">Contacts</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- News section  -->
  <section class="newspagewrapper">
    <div style="margin:2% 0;font-size:40px">
      <h2>Latest news and updates</h2>
    </div>
  </section>

  <section class="wrappernews2">
    <div class="row" id="updatesContainer">
      <!-- Dynamic updates aappear here -->
    </div>
  </section>


  <section class="wrappernews1" style="margin-left: 20px;">
    <div class="row">
      <div class="column">
        <figure>
          <img src="/assets/images/schlday/day22.JPG" height="320" width="400" />
          <figcaption style="font-size: medium;">
            <p>Today On May 3rd, 2025, GSO Butare Indatwa n'Inkesha held a memorial event to commemorate the 31st
              anniversary of the 1994 Genocide against the Tutsi, honoring the victims and pledging to foster unity and
              resilience for a brighter future.</p>
          </figcaption>
        </figure>
      </div>
      <div class="column">
        <figure>
          <img src="/assets/images/schlday/day24.JPG" height="320" width="600" />
          <figcaption style="font-size: medium;">
            <p>
              <button type="button" onclick="openPopup()"
                style="border-radius: 10px; border:none; background-color:#007BFF; color:#fff; padding:5px;">Read
                more</button>
            </p>
          </figcaption>
        </figure>

        <!-- Full-size image modal -->
        <div id="fullImageModal" class="full-image-modal">
          <span class="full-image-close">×</span>
          <img class="full-image-content" id="fullImage" />
          <button class="nav-button prev-button" onclick="changeImage(-1)">❮</button>
          <button class="nav-button next-button" onclick="changeImage(1)">❯</button>
        </div>
      </div>
    </div>
  </section>
  <section class="wrappernews2">
    <div class="row">
      <div class="column" style="background-image: url('/assets/images/kwibuka/edited1.jpg');">
        <h3>Commemoration event</h3>
        <p>On 03 May 2025, At GSO Butare Indatwa n'Inkesha, we held an event to commemorate the 31st anniversary of the
          1994 Genocide against the Tutsi.</p>
      </div>
      <div class="column" style="background-image: url('/assets/images/kwibuka/edited5.JPG');">
        <h3>Commemoration event</h3>
        <p>On May 3rd, 2025, We joined by Hon. Uwizeyimana Evode, as we honored the victims and reaffirmed our
          commitment to unity and resilience in memorial event to honor the victims of the 1994 Genocide against the
          Tutsi.</p>
      </div>
      <div class="column" style="background-image: url('/assets/images/kwibuka/edited13.jpg');">
        <h3>Commemoration event</h3>
        <p>On May 3rd, 2025, GSO Butare Indatwa n'Inkesha, joined by members of the Croix Rouge who were present at our
          school during the time of Genocide against Tusti 1994 , as we reflect and recommit to unity and resilience.
        </p>
      </div>
    </div>
    <div class="row">
      <div class="column" style="background-image: url('/assets/images/kwibuka/edited9.JPG');">
        <h3>Commemoration event</h3>
        <p>We organized a commemorative ceremony to place flowers at the 1994 Tutsi Genocide Memorial, honoring the
          victims and reflecting on the tragedy.</p>
      </div>
      <div class="column" style="background-image: url('/assets/images/mos22.jpg');">
        <h3>Visit of minister of sports</h3>
        <p>Kuru uyu wa Gatandatu tariki ya 29 Werurwe 2025, Nyakubahwa Madame Minisitiri wa Sport NELLY MUKAZAYIRE
          yasuye ishuri rya GROUPE SCOLAIRE OFFICIEL DE BUTARE(Indatwa n'inkesha).</p>
      </div>
      <div class="column" style="background-image: url('/assets/images/event2.jpg');">
        <h3>Genocide Memorial 1994</h3>
        <p>During the period of commemorate the 30st anniversary of the 1994 Genocide against Tutsi, the friends of our
          school joined us.</p>
      </div>
      <div class="column" style="background-image: url('/assets/images/schday1.jpg');">
        <h3>School Day 2024</h3>
        <p>On 19th May 2024 we celebrated our school day with our lovely students and other friends of the school.</p>
      </div>
      <div class="column" style="background-image: url('/assets/images/image2.jpg');">
        <h3>Kayumba Tournament 2024/2025</h3>
        <p>In KAYUMBA tournament season 2023/2024, many games participated like volleyball, beach volleyball, swimming,
          and cycling.</p>
      </div>
      <div class="column" style="background-image: url('/assets/images/event3.JPG');">
        <h3>Last Assembly</h3>
        <p>The last assembly took place before the school day. It was an important event for students and staff.</p>
      </div>
      <div class="column" style="background-image: url('/assets/images/sports1.JPG');">
        <h3>Isonga Volleyballers Victory</h3>
        <p>Our Isonga volleyballers won the tournament which met all teams of Isonga volleyballers of the whole country.
        </p>
      </div>
      <div class="column" style="background-image: url('/assets/images/sports2.jpg');">
        <h3>Indatwa Volleyball Rutsindura Win</h3>
        <p>Indatwa volleyball team won Rutsindura tournament season 2023/2024, prepared by Petit Seminaire Virgo
          Fidelis.</p>
      </div>
      <div class="column" style="background-image: url('/assets/images/sports4.jpg');">
        <h3>Indatwa Junior Volleyball Win</h3>
        <p>Indatwa junior volleyball team won Rutsindura tournament season 2023/2024, prepared by Petit Seminaire Virgo
          Fidelis.</p>
      </div>
      <div class="column" style="background-image: url('/assets/images/isong1.jpg');">
        <h3>Cycling Team Prepares for Kayumba</h3>
        <p>Cycling team of Isonga prepares for the KAYUMBA tournament, the first time cycling joins the tournament.</p>
      </div>
      <div class="column" style="background-image: url('/assets/images/image6.jpg');">
        <h3>Kayumba tournament 2024/2024</h3>
        <p>KAYUMBA tournament 2024/2025 meet more teams in volleyball in serie B.</p>
      </div>
      <div class="column" style="background-image: url('/assets/images/image7.jpg');">
        <h3>Kayumba tournament 2024/2024</h3>
        <p>KAYUMBA tournament 2024/2025 meet more teams in volleyball in O'Level Teams.</p>
      </div>
    </div>
  </section>
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
        <h3 style="cursor: pointer;">Home values:</h3>
        <p>Excellence</p>
        <p>Cleanliness</p>
        <p>Responsibility</p>
        <p>Empowerment</p>
        <p>Innovation</p>
      </div>
      <div class="column" id="column12">
        <h3 style="cursor: pointer;">What we prefer:</h3>
        <p>God</p>
        <p>Studies</p>
        <p>Discipline</p>
        <p>Sports</p>
        <p>Peace</p>
      </div>
      <div class="column" id="column-sub">
        <h3 style="cursor: pointer;">Subscribe for our newsletter:</h3>
        <input type="text" placeholder="Enter your email here..." />
        <input type="submit" value="Submit" />
        <h4 style="color:#6495ED;margin-bottom:10%">You will be able to know all updates via your email!</h4>
        <div class="tooltip-container">
          <button>Reach to developers</button>
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
        <div>
          © All rights reserved @2025
          <a href="#">By Indatwa</a>
        </div>
      </div>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/js/updates_displayer.js"></script>
</body>

</html>