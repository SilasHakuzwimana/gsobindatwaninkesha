<!DOCTYPE html>
<html>

<head>
  <title>GSOB INDATWA | About</title>
  <link rel="icon" type="image/x-icon" href="./images/logo.jpg">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="google-adsense-account" content="ca-pub-3738844276852721">
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3738844276852721"
    crossorigin="anonymous"></script>
  <script src="./styles & scripts/script.js" defer></script>
  <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
  <link rel="stylesheet" type="text/css" href="/assets/css/homepage.css" />
  <link rel="stylesheet" type="text/css" href="/assets/css/screens.css" />
  <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
  <script>
  (function() {
    emailjs.init("YOUR_USER_ID"); // Replace with your EmailJS user ID
  })();

  document.getElementById("contact-form").addEventListener("submit", function(event) {
    event.preventDefault();

    emailjs.send("service_5pz7f74", "template_xgi10po", {
      from_name: document.getElementById("names").value,
      from_email: document.getElementById("email").value,
      message: document.getElementById("suggestion").value
    }).then(function(response) {
      alert("Message Sent Successfully!");
      document.getElementById("contact-form").reset();
    }, function(error) {
      alert("Failed to send message. Try again!");
    });
  });
  </script>
</head>

<body>
  <!-- <div class="loader-wrapper">-->
  <!--   <div class="loader">-->
  <!--    <div class="dot-spinner">-->
  <!--        <div class="dot-spinner__dot"></div>-->
  <!--        <div class="dot-spinner__dot"></div>-->
  <!--        <div class="dot-spinner__dot"></div>-->
  <!--        <div class="dot-spinner__dot"></div>-->
  <!--        <div class="dot-spinner__dot"></div>-->
  <!--        <div class="dot-spinner__dot"></div>-->
  <!--        <div class="dot-spinner__dot"></div>-->
  <!--        <div class="dot-spinner__dot"></div>-->
  <!--    </div>-->
  <!--    <p>Powered  by<span> INDATWA</span></p>-->
  <!--  </div> -->
  <!--</div>-->
  <section class="gsobabout section">
    <!--responsive navbar-->
    <img id="menu-button" src="img/menu5.png" height="25px" width="33px">
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
              <a href="/gallery"> Gallery</a>
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
          <a href="#" id="contact-open">Contacts </a>
        </li>
      </ul>
    </div>
    <div class="topnav" id="myTopnav">
      <div class="row">
        <div class="column" id="logo">
          <img src="/assets/images/logo.png">
          <div style="float: right;padding:10px 0;margin:3% 0">
            <p><b style="font-size:20px">GSO BUTARE </b>
              <br /> <b style="font-size:7px">S'INSTRUIRE POUR MIEUX SERVIR</b>
            </p>
          </div>
        </div>
        <div class="column" id="navbar" style="margin-left:5%;margin-top:1.3%">
          <a id="home" href="/index">Home</a>
          <a href="/academics">Academics</a>
          <div class="dropdown">
            <button class="dropbtn">Our school
              <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content">
              <a class="active" href="/about">About us</a>
              <a href="/alumni">Alumin</a>
              <a href="/gallery">Gallery</a>
            </div>
          </div>
          <a href="/extracurricular">Extracurricular</a>
          <a href="/news">School Updates</a>
          <a href="#" id="contacts-open">Contacts</a>
        </div>
      </div>
    </div>
    <div class="responsive-navbar">

    </div>
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
                  <p>Enter your name:</p><input type="text" id="names" placeholder="Enter name..." name="fname">
                </div>
                <div class="form-field">
                  <p>Email address:</p><input type="email" id="email" placeholder="Enter email..." name="email">
                </div>
                <div class="form-field">
                  <p>Your suggestion:</p><textarea id="suggestion" name="suggestion"></textarea>
                </div>
                <center><input type="submit" value="submit"></center>
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
                  <a href="https://x.com/gsobindatwa?" target="_blank">
                    <img src="/assets/images/icons/twitter.ico" alt="X icon" class="src">
                  </a>
                </div>
                <div class="img">
                  <a href="https://www.youtube.com/@indatwaninkeshagsob7953" target="_blank">
                    <img src="/assets/images/icons/youtube.ico" alt="Youtube icon" class="src">
                  </a>
                </div>
                <div class="img">
                  <a href="https://www.facebook.com/p/Gsob-Indatwa-100015003844601/?" target="_blank">
                    <img src="/assets/images/icons/facebook.ico" alt="facebook icon" class="src">
                  </a>
                </div>
                <div class="img">
                  <a href="https://www.instagram.com/indatwa_inkesha_school/" target="_blank">
                    <img src="/assets/images/icons/instagram.ico" alt="instagram icon" class="src">
                  </a>
                </div>
                <div class="img">
                  <a href="https://wa.me/" target="_blank">
                    <img src="/assets/images/icons/whatsapp.ico" alt="whatsapp icon" class="src">
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <br />
  </section>
  <section class="aboutwrapper">
    <section class="about">
      <div class="about-us">
        <h1 style="color: rbg(1,1,74);padding:25px;color:dodgerblue;">About GSOB INDATWA N' INKESHA</h1>
        <h5 style="margin-top:-2%;padding:15px">Let's share to you our History,Mision & Vision</h5>
      </div>
    </section>
  </section>
  <section class="history">
    <h1>Our school History</h1>
    <br />
    <div id="row" class="textpar">
      <p>The Groupe Scolaire Officiel de Butare (GSOB) Indatwa n'inkesha, also known as the Indatwa
        n'Inkesha School, is a historic secondary school in Huye District in Butare (formerly Astrida),
        Rwanda. As well as being the oldest secondary school in the country, it is regarded as one of the
        most prestigious and successful public schools in Rwanda.
        The school, originally known as the Groupe Scolaire d'Astrida, was established by the Brothers of
        Charity in 1929. It was intended to educate students who would form an élite to serve as chiefs and
        medical, agricultural, and veterinary assistants in the Belgian mandatory administration in Ruanda-Urundi.
      </p>
      <p> It took students, often the children of chiefs and other dignitaries, from both modern-day Rwanda
        and Burundi. Between 1932 and 1957, the Groupe Scolaire d'Astrida admitted 964 students from
        Rwanda. After independence, the school was renamed the Groupe Scolaire de Butare. This school is regarded as one
        of the most selective schools in Rwanda.</p>
    </div>
  </section>
  <div class="missionhead">
    <center>
      <h1 style="color:rgb(1,1,74)" ;>What shapes our Futures</h1>
    </center>
  </div>
  <section class="missions reveal">
    <article class="cirwrap">
      <div class="cir">
        <h2>Our Mission</h2>
        <p>The mission of GSO Butare is to provide learners with quality education and commitment to bring a positive
          changes towards sustainable development of our country. </p>
      </div>
      <div class="cir">
        <h2>Our Vision</h2>
        <p>GSO Butare strives to be always a school of excellence that provides a moral,social,scientific and holistic
          education through promoting individual compentences and skills. </p>
      </div>
      <div class="cir">
        <h2>Our values</h2>
        <p>GSO Butare values academic excellence, integrity, inclusivity, innovation, and community engagement,
          fostering a nurturing environment for holistic development. </p>
      </div>
      <div class="cir">
        <h2 style="color:rgb(211, 205, 205);">Our Goals</h2>
        <p>GSO Butare aims to empower students through a comprehensive education that emphasizes critical thinking,
          leadership skills, cultural awareness, and social responsibility, preparing them for future challenges.</p>
      </div>
    </article>
  </section>
  <footer class="alumni1 ">
    <div class="row">
      <div class="column" id="column10">
        <h3>Quick links:</h3>
        <p><a href="/index">Home</a></p>
        <p><a href="#">Our school</a></p>
        <p><a href="/academics">Academics</a></p>
        <p><a href="/extracurricular">Extracarricular</a></p>
        <p><a href="/news">Updates</a></p>
      </div>
      <div class="column" id="column11">
        <h3 style="cursor: pointer;">Home values:</h3>
        <p>Excelence</p>
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
        <p>peace</p>
      </div>
      <div class="column" id="column-sub">
        <h3 style="cursor: pointer;">Subscribe for our news later:</h3>
        <input type="text" placeholder="Enter your email here...">
        <input type="submit" value="Submit">
        <h4 style="color:#6495ED;margin-bottom:10%">You will able to know all Updates via your email!</h4>
        <div class="tooltip-container">
          <button> Reach to developers</button>
          <div class="tooltip">
            <div class="row">
              <a href="https://www.instagram.com/c.a.s___t.r.o" target="_blank">@Castro</a>
              <a href="https://www.instagram.com/kfa___brice1" target="_blank">@KFabrice</a>
            </div>
            <div class="row">
              <a href="https://www.instagram.com/other.guy34" target="_blank">@Tris</a>
              <a href="https://www.instagram.com/_janson__/" target="_blank">@Hush </a>
            </div>
          </div>
        </div>
      </div>
      <div class="copyright">
        <center>&copy; All copyright reserved @2025
          <a href="#">By Indatwa</a>
        </center>
      </div>
    </div>
  </footer>
</body>

</html>