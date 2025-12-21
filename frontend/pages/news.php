<!DOCTYPE html>
<html>
  <head>
    <title>GSOB INDATWA | School Updates</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="google-adsense-account" content="ca-pub-3738844276852721" />
    <link rel="icon" type="image/x-icon" href="/assets/images/logo.jpg" />
    <script src="/assets/js/script.js" defer></script>
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/homepage.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/style_News.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/screens.css" />
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
    <style>
      .wrappernews2 {
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
      ;(function () {
        emailjs.init('YOUR_USER_ID') // Replace with your EmailJS user ID
      })()
    </script>
  </head>
  <body>
    <section class="newswrapper section">
      <!-- responsive navbar -->
      <img id="menu-button" src="/assets/images/menu5.png" alt="menu_icon" height="25px" width="33px" />
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
                <a href="/gallery">Gallery</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="/extracurricular">Extracurricular</a>
          </li>
          <li>
            <a class="active" href="/news">School Updates</a>
          </li>
          <li>
            <a href="#" id="contact-open">Contacts</a>
          </li>
        </ul>
      </div>
      <div class="topnav" id="myTopnav">
        <div class="row">
          <div class="column" id="logo">
            <img src="/assets/images/logo.png" alt="logo-image" />
            <div style="float: right;padding:10px 0;margin:3% 0">
              <p>
                <b style="font-size:20px">GSO BUTARE</b>
                <br /> <b style="font-size:7px">S'INSTRUIRE POUR MIEUX SERVIR</b>
              </p>
            </div>
          </div>
          <div class="column" id="navbar" style="margin-left:5%;margin-top:1.3%">
            <a href="/index">Home</a>
            <a href="/academics">Academics</a>
            <div class="dropdown">
              <button class="dropbtn">
                Our school
                <i class="fa fa-caret-down"></i>
              </button>
              <div class="dropdown-content">
                <a class="active" href="/about">About us</a>
                <a href="/alumni">Alumni</a>
                <a href="/gallery">Gallery</a>
              </div>
            </div>
            <a href="/extracurricular">Extracurricular</a>
            <a class="active" href="/news">School Updates</a>
            <a href="#" id="contacts-open">Contacts</a>
          </div>
        </div>
      </div>
      <div class="responsive-navbar"></div>
      <div id="contacts" class="modal">
        <div class="modal-content">
          <span class="close">x</span>
          <center>
            <h2 style=" font-family: Impact, fantasy;">Get in touch with us</h2>
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
                    <a href="https://wa.me/250788478609" target="_blank"><img src="/assets/images/icons/whatsapp.ico" alt="whatsapp icon" class="src" /></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br />
    </section>
    <section class="newspagewrapper">
      <center style="margin:2% 0;font-size:40px">
        <h2>Latest news and updates</h2>
      </center>
    </section>
    <section class="wrappernews1">
      <div class="row">
        <div class="column">
          <figure>
            <img src="/assets/images/schlday/day22.JPG" height="320" width="400" />
            <figcaption style="font-size: medium;">
              <p>Today On May 3rd, 2025, GSO Butare Indatwa n'Inkesha held a memorial event to commemorate the 31st anniversary of the 1994 Genocide against the Tutsi, honoring the victims and pledging to foster unity and resilience for a brighter future.</p>
            </figcaption>
          </figure>
        </div>
        <div class="column">
          <figure>
            <img src="/assets/images/schlday/day24.JPG" height="320" width="600" />
            <figcaption style="font-size: medium;">
              <p>
                <button type="button" onclick="openPopup()">Read more</button>
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
          <p>On 03 May 2025, At GSO Butare Indatwa n'Inkesha, we held an event to commemorate the 31st anniversary of the 1994 Genocide against the Tutsi.</p>
        </div>
        <div class="column" style="background-image: url('/assets/images/kwibuka/edited5.JPG');">
          <h3>Commemoration event</h3>
          <p>On May 3rd, 2025, We joined by Hon. Uwizeyimana Evode, as we honored the victims and reaffirmed our commitment to unity and resilience in memorial event to honor the victims of the 1994 Genocide against the Tutsi.</p>
        </div>
        <div class="column" style="background-image: url('/assets/images/kwibuka/edited13.jpg');">
          <h3>Commemoration event</h3>
          <p>On May 3rd, 2025, GSO Butare Indatwa n'Inkesha, joined by members of the Croix Rouge who were present at our school during the time of Genocide against Tusti 1994 , as we reflect and recommit to unity and resilience.</p>
        </div>
      </div>
      <div class="row">
        <div class="column" style="background-image: url('/assets/images/kwibuka/edited9.JPG');">
          <h3>Commemoration event</h3>
          <p>We organized a commemorative ceremony to place flowers at the 1994 Tutsi Genocide Memorial, honoring the victims and reflecting on the tragedy.</p>
        </div>
        <div class="column" style="background-image: url('/assets/images/mos22.jpg');">
          <h3>Visit of minister of sports</h3>
          <p>Kuru uyu wa Gatandatu tariki ya 29 Werurwe 2025, Nyakubahwa Madame Minisitiri wa Sport NELLY MUKAZAYIRE yasuye ishuri rya GROUPE SCOLAIRE OFFICIEL DE BUTARE(Indatwa n'inkesha).</p>
        </div>
        <div class="column" style="background-image: url('/assets/images/event2.jpg');">
          <h3>Genocide Memorial 1994</h3>
          <p>During the period of commemorate the 30st anniversary of the 1994 Genocide against Tutsi, the friends of our school joined us.</p>
        </div>
        <div class="column" style="background-image: url('/assets/images/schday1.jpg');">
          <h3>School Day 2024</h3>
          <p>On 19th May 2024 we celebrated our school day with our lovely students and other friends of the school.</p>
        </div>
        <div class="column" style="background-image: url('/assets/images/image2.jpg');">
          <h3>Kayumba Tournament 2024/2025</h3>
          <p>In KAYUMBA tournament season 2023/2024, many games participated like volleyball, beach volleyball, swimming, and cycling.</p>
        </div>
        <div class="column" style="background-image: url('/assets/images/event3.JPG');">
          <h3>Last Assembly</h3>
          <p>The last assembly took place before the school day. It was an important event for students and staff.</p>
        </div>
        <div class="column" style="background-image: url('/assets/images/sports1.JPG');">
          <h3>Isonga Volleyballers Victory</h3>
          <p>Our Isonga volleyballers won the tournament which met all teams of Isonga volleyballers of the whole country.</p>
        </div>
        <div class="column" style="background-image: url('/assets/images/sports2.jpg');">
          <h3>Indatwa Volleyball Rutsindura Win</h3>
          <p>Indatwa volleyball team won Rutsindura tournament season 2023/2024, prepared by Petit Seminaire Virgo Fidelis.</p>
        </div>
        <div class="column" style="background-image: url('/assets/images/sports4.jpg');">
          <h3>Indatwa Junior Volleyball Win</h3>
          <p>Indatwa junior volleyball team won Rutsindura tournament season 2023/2024, prepared by Petit Seminaire Virgo Fidelis.</p>
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
          <center>
            © All rights reserved @2025
            <a href="#">By Indatwa</a>
          </center>
        </div>
      </div>
    </footer>
    <script>
      document.getElementById('contact-form').addEventListener('submit', function (event) {
        event.preventDefault()
      
        emailjs
          .send('service_5pz7f74', 'template_xgi10po', {
            from_name: document.getElementById('names').value,
            from_email: document.getElementById('email').value,
            message: document.getElementById('suggestion').value
          })
          .then(
            function (response) {
              alert('Message Sent Successfully!')
              document.getElementById('contact-form').reset()
            },
            function (error) {
              alert('Failed to send message. Try again!')
            }
          )
      })
      
      // Select all images in the popup
      const popupImages = document.querySelectorAll('.popup-content .row .column img')
      const fullImageModal = document.getElementById('fullImageModal')
      const fullImage = document.getElementById('fullImage')
      const fullImageClose = document.querySelector('.full-image-close')
      
      let currentImageIndex = 0
      
      // Add click event to each image in the popup
      popupImages.forEach((image, index) => {
        image.addEventListener('click', function () {
          currentImageIndex = index // Set the current image index
          fullImage.src = this.src // Set the full-size image source
          fullImageModal.style.display = 'flex' // Show the modal
        })
      })
      
      // Function to change the image (next/previous)
      function changeImage(direction) {
        currentImageIndex += direction
      
        // Loop around if at the end or beginning
        if (currentImageIndex >= popupImages.length) {
          currentImageIndex = 0
        } else if (currentImageIndex < 0) {
          currentImageIndex = popupImages.length - 1
        }
      
        // Update the full-size image source
        fullImage.src = popupImages[currentImageIndex].src
      }
      
      // Close the full-size image modal
      fullImageClose.addEventListener('click', function () {
        fullImageModal.style.display = 'none'
      })
      
      // Close the modal when clicking outside the image
      fullImageModal.addEventListener('click', function (event) {
        if (event.target === fullImageModal) {
          fullImageModal.style.display = 'none'
        }
      })
      
      // Optional: Add keyboard navigation (left/right arrow keys)
      document.addEventListener('keydown', function (event) {
        if (fullImageModal.style.display === 'flex') {
          if (event.key === 'ArrowLeft') {
            changeImage(-1) // Previous image
          } else if (event.key === 'ArrowRight') {
            changeImage(1) // Next image
          } else if (event.key === 'Escape') {
            fullImageModal.style.display = 'none' // Close modal
          }
        }
      })
    </script>
  </body>
</html>
