<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="google-adsense-account" content="ca-pub-3738844276852721" />
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3738844276852721" crossorigin="anonymous"></script>
    <title>GSOB INDATWA | Gallery</title>
    <link rel="icon" type="image/x-icon" href="/assets/images/logo.jpg" />
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/homepage.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/screens.css" />
    <style>
      .heading {
        font-size: 33px;
        line-height: 45px;
        font-weight: 600;
        color: rgb(1, 1, 74);
        text-align: center;
        margin-top: 0px;
        margin-bottom: 20px;
      }
      .subheadcenter {
        text-align: center;
        font-size: 20px;
        line-height: 35px;
        max-width: 750px;
        color: #a3a3a3;
        margin: 0 auto;
      }
      .gallery * {
        font-family: Nunito, sans-serif;
      }
      .gallery .responsive-container-block {
        min-height: 75px;
        height: fit-content;
        width: 100%;
        padding: 10px;
        display: flex;
        flex-wrap: wrap;
        margin: 0 auto;
        justify-content: flex-start;
      }
      .gallery .responsive-container-block.bigContainer {
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 50px 30px;
      }
      .gallery .responsive-container-block.headerContainer {
        max-width: 1320px;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: 0 auto 40px;
      }
      .gallery .responsive-container-block.Container {
        max-width: 1320px;
        margin: 0 auto;
        position: relative;
        padding: 0;
        height: 1380px;
      }
      .gallery .row1 {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: justify;
        margin: 0 0 30px;
      }
      .gallery .project {
        position: absolute;
        width: 59%;
        height: 440px;
        padding: 0;
      }
      .gallery .btn-box {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none;
        pointer-events: none; /* Prevent btn-box from capturing clicks */
      }
      .gallery .project:hover .btn-box {
        display: block;
      }
      .gallery .btn img {
        cursor: pointer;
        width: 20px;
        height: 20px;
        object-fit: cover;
      }
      .gallery .smallImage {
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: pointer;
      }
      .gallery .project.project2 {
        left: 59%;
        width: 41%;
        height: 700px;
      }
      .gallery .project.project2 .gallery-item .smallImage {
        height: 700px;
      }
      .gallery .project.project3 {
        top: 440px;
        width: 41%;
        height: 855px;
      }
      .gallery .project.project3 .gallery-item .smallImage {
        height: 855px;
      }
      .gallery .project.project4 {
        left: 41%;
        top: 440px;
        width: 59%;
        height: 415px;
      }
      .gallery .project.project5 {
        top: 880px;
        left: 41%;
        width: 29.5%;
        height: 415px;
      }
      .gallery .project.project5 .gallery-item .smallImage {
        height: 415px;
      }
      .gallery .project.project6 {
        top: 880px;
        left: 70.5%;
        width: 29.5%;
        height: 415px;
      }
      .gallery .project.project6 .gallery-item .smallImage {
        height: 415px;
      }
      .full-image-modal {
        display: none;
        position: fixed;
        z-index: 10000;
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
        width: 90%;
        height: 65vh;
        display: block;
        margin: auto;
        object-fit: contain;
      }
      .full-image-content img {
        width: 100%;
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
        z-index: 10001;
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
      .nav-button img {
        width: 20px;
        height: 20px;
      }
      @media (max-width: 768px) {
        .gallery .project.project2 {
          height: 245px;
        }
        .gallery .responsive-container-block.Container {
          max-width: 740px;
          height: 770px;
        }
        .gallery .project.project1 {
          height: 245px;
        }
        .gallery .project.project3 {
          height: 525px;
          top: 245px;
        }
        .gallery .project.project4 {
          top: 245px;
          height: 245px;
        }
        .gallery .project.project5 {
          top: 490px;
          height: 280px;
        }
        .gallery .project.project6 {
          top: 490px;
          height: 280px;
        }
        .gallery .row1 {
          margin-bottom: 25px;
        }
        .full-image-content {
          width: 90%;
          height: 55vh;
        }
      }
      @media (max-width: 500px) {
        .gallery .project {
          position: relative;
          width: 100%;
          height: 250px;
          margin-bottom: 20px;
        }
        .gallery .project.project2,
        .gallery .project.project3,
        .gallery .project.project4,
        .gallery .project.project5,
        .gallery .project.project6 {
          left: 0;
          top: 0;
          width: 100%;
          height: 250px;
        }
        .gallery .responsive-container-block.Container {
          height: auto;
          margin-bottom: 50px;
        }
        .gallery .responsive-container-block.headerContainer {
          padding: 0;
        }
        .gallery .row1 {
          margin-bottom: 22px;
        }
        .full-image-content {
          width: 90%;
          height: 55vh;
        }
        .nav-button {
          padding: 8px;
          font-size: 24px;
        }
      }
      *,
      *:before,
      *:after {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
      }
      body {
        margin: 0;
      }
      .wk-desk-1 {
        width: 8.333333%;
      }
      .wk-desk-2 {
        width: 16.666667%;
      }
      .wk-desk-3 {
        width: 25%;
      }
      .wk-desk-4 {
        width: 33.333333%;
      }
      .wk-desk-5 {
        width: 41.666667%;
      }
      .wk-desk-6 {
        width: 50%;
      }
      .wk-desk-7 {
        width: 58.333333%;
      }
      .wk-desk-8 {
        width: 66.666667%;
      }
      .wk-desk-9 {
        width: 75%;
      }
      .wk-desk-10 {
        width: 83.333333%;
      }
      .wk-desk-11 {
        width: 91.666667%;
      }
      .wk-desk-12 {
        width: 100%;
      }
      @media (max-width: 1024px) {
        .wk-ipadp-1 {
          width: 8.333333%;
        }
        .wk-ipadp-2 {
          width: 16.666667%;
        }
        .wk-ipadp-3 {
          width: 25%;
        }
        .wk-ipadp-4 {
          width: 33.333333%;
        }
        .wk-ipadp-5 {
          width: 41.666667%;
        }
        .wk-ipadp-6 {
          width: 50%;
        }
        .wk-ipadp-7 {
          width: 58.333333%;
        }
        .wk-ipadp-8 {
          width: 66.666667%;
        }
        .wk-ipadp-9 {
          width: 75%;
        }
        .wk-ipadp-10 {
          width: 83.333333%;
        }
        .wk-ipadp-11 {
          width: 91.666667%;
        }
        .wk-ipadp-12 {
          width: 100%;
        }
      }
      @media (max-width: 768px) {
        .wk-tab-1 {
          width: 8.333333%;
        }
        .wk-tab-2 {
          width: 16.666667%;
        }
        .wk-tab-3 {
          width: 25%;
        }
        .wk-tab-4 {
          width: 33.333333%;
        }
        .wk-tab-5 {
          width: 41.666667%;
        }
        .wk-tab-6 {
          width: 50%;
        }
        .wk-tab-7 {
          width: 58.333333%;
        }
        .wk-tab-8 {
          width: 66.666667%;
        }
        .wk-tab-9 {
          width: 75%;
        }
        .wk-tab-10 {
          width: 83.333333%;
        }
        .wk-tab-11 {
          width: 91.666667%;
        }
        .wk-tab-12 {
          width: 100%;
        }
      }
      @media (max-width: 500px) {
        .wk-mobile-1 {
          width: 8.333333%;
        }
        .wk-mobile-2 {
          width: 16.666667%;
        }
        .wk-mobile-3 {
          width: 25%;
        }
        .wk-mobile-4 {
          width: 33.333333%;
        }
        .wk-mobile-5 {
          width: 41.666667%;
        }
        .wk-mobile-6 {
          width: 50%;
        }
        .wk-mobile-7 {
          width: 58.333333%;
        }
        .wk-mobile-8 {
          width: 66.666667%;
        }
        .wk-mobile-9 {
          width: 75%;
        }
        .wk-mobile-10 {
          width: 83.333333%;
        }
        .wk-mobile-11 {
          width: 91.666667%;
        }
        .wk-mobile-12 {
          width: 100%;
        }
      }
    </style>
  </head>
  <body class="gallerys">
    <section class="gsobgallery section">
      <img id="menu-button" src="/assets/images/menu5.png" height="25px" width="33px" />
      <div id="menuwrapper">
        <ul id="menu-list">
          <li>
            <a href="/index">Home</a>
          </li>
          <li>
            <a href="/academics">Academics</a>
          </li>
          <li>
            <a href="#">Our School</a>
            <ul class="submenu">
              <li>
                <a href="/about">About us</a>
              </li>
              <li>
                <a href="/alumni">Alumni</a>
              </li>
              <li>
                <a class="active" href="/gallery">Gallery</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="/extracurricular">Extracurricular</a>
          </li>
          <li>
            <a href="/news">School Updates</a>
          </li>
          <li>
            <a href="#" id="contact-open">Contacts</a>
          </li>
        </ul>
      </div>
      <div class="topnav" id="myTopnav">
        <div class="row">
          <div class="column" id="logo">
            <img src="/assets/images/logo.png" />
            <div style="float: right;padding:10px 0;margin:3% 0">
              <p>
                <b style="font-size:20px">GSO BUTARE</b><br /><b style="font-size:7px">S'INSTRUIRE POUR MIEUX SERVIR</b>
              </p>
            </div>
          </div>
          <div class="column" id="navbar" style="margin-left:5%;margin-top:1.3%">
            <a id="home" href="/index">Home</a>
            <a href="/academics">Academics</a>
            <div class="dropdown">
              <button class="dropbtn">Our school <i class="fa fa-caret-down"></i></button>
              <div class="dropdown-content">
                <a href="/about">About us</a>
                <a href="/alumni">Alumni</a>
                <a class="active" href="/gallery">Gallery</a>
              </div>
            </div>
            <a href="/extracurricular">Extracurricular</a>
            <a href="/news">School Updates</a>
            <a href="#" id="contacts-open">Contacts</a>
          </div>
        </div>
      </div>
      <div class="responsive-navbar"></div>
      <div id="contacts" class="modal">
        <div class="modal-content">
          <span class="close">x</span>
          <center>
            <h2 style="font-family: Impact, fantasy;">Get in touch with us</h2>
          </center>
          <div class="row">
            <div class="column">
              <center>
                <form id="contact-form">
                  <h3>Tell us your suggestion here</h3>
                  <div class="form-field">
                    <p>Enter your name:</p>
                    <input type="text" id="names" placeholder="Enter name..." name="fname" />
                  </div>
                  <div class="form-field">
                    <p>Email address:</p>
                    <input type="email" id="email" placeholder="Enter email..." name="email" />
                  </div>
                  <div class="form-field">
                    <p>Your suggestion:</p>
                    <textarea id="suggestion" name="suggestion"></textarea>
                  </div>
                  <center>
                    <input type="submit" value="submit" />
                  </center>
                </form>
              </center>
            </div>
            <div id="dialog" style="height:fit-content" class="column">
              <div class="column-last">
                <center>
                  <h4>Visit us on:</h4>
                </center>
                <div class="column-last-one">
                  <div class="img">
                    <a href="https://x.com/gsobindatwa?" target="_blank"><img src="/assets/images/icons/twitter.ico" alt="X icon" class="src" /></a>
                  </div>
                  <div class="img">
                    <a href="https://www.youtube.com/@indatwaninkeshagsob7953" target="_blank"><img src="/assets/images/icons/youtube.ico" alt="Youtube icon" class="src" /></a>
                  </div>
                  <div class="img">
                    <a href="https://www.facebook.com/p/Gsob-Indatwa-100015003844601/?" target="_blank"><img src="/assets/images/icons/facebook.ico" alt="facebook icon" class="src" /></a>
                  </div>
                  <div class="img">
                    <a href="https://www.instagram.com/indatwa_inkesha_school/" target="_blank"><img src="/assets/images/icons/instagram.ico" alt="instagram icon" class="src" /></a>
                  </div>
                  <div class="img">
                    <a href="https://wa.me/" target="_blank"><img src="/assets/images/icons/whatsapp.ico" alt="whatsapp icon" class="src" /></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="gallerywrapper">
      <div class="gallery" unique-script-id="w-w-dm-id">
        <div class="responsive-container-block bigContainer">
          <div class="responsive-container-block headerContainer">
            <div class="row1">
              <h1 class="text-blk heading">Our school Gallery</h1>
            </div>
            <center class="subheadcenter">
              <p class="text-blk subHeading">Explore our school Gallery showcasing a vibrant collection of traditional paintings and cutting-edge digital creations from our talented students and faculty.</p>
            </center>
          </div>
          <div class="responsive-container-block Container">
            <div class="project project1">
              <div class="gallery-item">
                <img class="smallImage" src="/assets/images/gallery/1.jpg" />
                <div class="btn-box">
                  <button class="btn"><img src="img/play.png" /></button>
                </div>
              </div>
            </div>
            <div class="project project2">
              <div class="gallery-item">
                <img class="smallImage" src="/assets/images/gallery/2.jpg" />
                <div class="btn-box">
                  <button class="btn"><img src="/assets/images/play.png" /></button>
                </div>
              </div>
            </div>
            <div class="project project3">
              <div class="gallery-item">
                <img class="smallImage" src="/assets/images/gallery/3.jpg" />
                <div class="btn-box">
                  <button class="btn"><img src="/assets/images/play.png" /></button>
                </div>
              </div>
            </div>
            <div class="project project4">
              <div class="gallery-item">
                <img class="smallImage" src="/assets/images/gallery/4.jpg" />
                <div class="btn-box">
                  <button class="btn"><img src="/assets/images/play.png" /></button>
                </div>
              </div>
            </div>
            <div class="project project5">
              <div class="gallery-item">
                <img class="smallImage" src="/assets/images/gallery/5.JPG" />
                <div class="btn-box">
                  <button class="btn"><img src="/assets/images/play.png" /></button>
                </div>
              </div>
            </div>
            <div class="project project6">
              <div class="gallery-item">
                <img class="smallImage" src="/assets/images/gallery/6.JPG" />
                <div class="btn-box">
                  <button class="btn"><img src="/assets/images/play.png" /></button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="fullImageModal" class="full-image-modal">
          <span class="full-image-close">x</span>
          <img class="full-image-content" id="fullImage" />
          <button class="nav-button prev-button" onclick="changeImage(-1)"><img src="left.png" alt="Previous" onerror="this.style.display='none';this.parentNode.textContent='❮'" /></button>
          <button class="nav-button next-button" onclick="changeImage(1)"><img src="/assets/images/right.png" alt="Next" onerror="this.style.display='none';this.parentNode.textContent='❯'" /></button>
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
          <h3 style="cursor: pointer;">Subscribe for our news later:</h3>
          <input type="text" placeholder="Enter your email here..." />
          <input type="submit" value="Submit" />
          <h4 style="color:#6495ED;margin-bottom:10%">You will able to know all Updates via your email!</h4>
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
          <center>
            © All copyright reserved @2025 <a href="#">By Indatwa</a>
          </center>
        </div>
      </div>
    </footer>
    <script src="/assets/js/script.js" defer></script>
    <script>
      const galleryItems = document.querySelectorAll('.gallery-item')
      const galleryImages = document.querySelectorAll('.gallery-item .smallImage')
      const fullImageModal = document.getElementById('fullImageModal')
      const fullImage = document.getElementById('fullImage')
      const fullImageClose = document.querySelector('.full-image-close')
      let currentImageIndex = 0
      
      console.log('Gallery images found:', galleryImages.length) // Debug: Check if images are selected
      
      // Prevent play button from capturing clicks
      document.querySelectorAll('.btn-box .btn').forEach((button) => {
        button.addEventListener('click', function (event) {
          event.stopPropagation()
          console.log('Play button clicked, stopping propagation') // Debug: Confirm play button click
        })
      })
      
      // Event delegation for gallery items
      galleryItems.forEach((item, index) => {
        item.addEventListener('click', function (event) {
          if (event.target.classList.contains('smallImage')) {
            console.log('Image clicked:', index, 'Src:', event.target.src) // Debug: Confirm click
            currentImageIndex = index
            fullImage.src = event.target.src
            fullImageModal.style.display = 'flex'
            console.log('Modal display set to:', fullImageModal.style.display) // Debug: Confirm modal display
          }
        })
      })
      
      function changeImage(direction) {
        currentImageIndex += direction
        if (currentImageIndex >= galleryImages.length) {
          currentImageIndex = 0
        } else if (currentImageIndex < 0) {
          currentImageIndex = galleryImages.length - 1
        }
        fullImage.src = galleryImages[currentImageIndex].src
        console.log('Changed image to index:', currentImageIndex, 'Src:', fullImage.src) // Debug: Confirm image change
      }
      
      fullImageClose.addEventListener('click', function () {
        fullImageModal.style.display = 'none'
        console.log('Modal closed') // Debug: Confirm close
      })
      
      fullImageModal.addEventListener('click', function (event) {
        if (event.target === fullImageModal) {
          fullImageModal.style.display = 'none'
          console.log('Modal closed by clicking outside') // Debug: Confirm close
        }
      })
      
      document.addEventListener('keydown', function (event) {
        if (fullImageModal.style.display === 'flex') {
          if (event.key === 'ArrowLeft') {
            changeImage(-1)
            console.log('Left arrow pressed') // Debug: Confirm key press
          } else if (event.key === 'ArrowRight') {
            changeImage(1)
            console.log('Right arrow pressed') // Debug: Confirm key press
          } else if (event.key === 'Escape') {
            fullImageModal.style.display = 'none'
            console.log('Escape key pressed') // Debug: Confirm key press
          }
        }
      })
    </script>
  </body>
</html>
