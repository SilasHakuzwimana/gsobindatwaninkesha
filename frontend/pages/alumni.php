<!DOCTYPE html>
<html>

<head>
  <title>GSOB INDATWA | Alumnis</title>
  <link rel="icon" type="image/x-icon" href="/assets/images/logo.jpg" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="google-adsense-account" content="ca-pub-3738844276852721" />
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3738844276852721"
    crossorigin="anonymous"></script>
  <script src="/assets/js/script.js" defer></script>
  <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
  <link rel="stylesheet" type="text/css" href="/assets/css/homepage.css" />
  <link rel="stylesheet" type="text/css" href="/assets/css/screens.css" />
  <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
  <script>
    ;
    (function() {
      emailjs.init('YOUR_USER_ID') // Replace with your EmailJS user ID
    })()

    document.getElementById('contact-form').addEventListener('submit', function(event) {
      event.preventDefault()

      emailjs
        .send('service_5pz7f74', 'template_xgi10po', {
          from_name: document.getElementById('names').value,
          from_email: document.getElementById('email').value,
          message: document.getElementById('suggestion').value
        })
        .then(
          function(response) {
            alert('Message Sent Successfully!')
            document.getElementById('contact-form').reset()
          },
          function(error) {
            alert('Failed to send message. Try again!')
          }
        )
    })
  </script>
</head>

<body>
  <section class="gsobalumni section">
    <!-- responsive navbar -->
    <img id="menu-button" src="/assets/images/menu5.png" height="34px" width="33px" />
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
              <a class="active" href="/alumni">Alumni</a>
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
              <b style="font-size:20px">GSO BUTARE</b>
              <br /> <b style="font-size:7px">S'INSTRUIRE POUR MIEUX SERVIR</b>
            </p>
          </div>
        </div>
        <div class="column" id="navbar" style="margin-left:5%;margin-top:1.3%">
          <a id="home" href="/index">Home</a>
          <a href="/academics">Academics</a>
          <div class="dropdown">
            <button class="dropbtn">
              Our school
              <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content">
              <a href="/about">About us</a>
              <a class="active" href="/alumni">Alumni</a>
              <a href="/gallery">Gallery</a>
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
                  <a href="https://x.com/gsobindatwa?" target="_blank"><img src="/assets/images/icons/twitter.ico"
                      alt="X icon" class="src" /></a>
                </div>
                <div class="img">
                  <a href="https://www.youtube.com/@indatwaninkeshagsob7953" target="_blank"><img
                      src="/assets/images/icons/youtube.ico" alt="Youtube icon" class="src" /></a>
                </div>
                <div class="img">
                  <a href="https://www.facebook.com/p/Gsob-Indatwa-100015003844601/?" target="_blank"><img
                      src="/assets/images/icons/facebook.ico" alt="facebook icon" class="src" /></a>
                </div>
                <div class="img">
                  <a href="https://www.instagram.com/indatwa_inkesha_school/" target="_blank"><img
                      src="/assets/images/icons/instagram.ico" alt="instagram icon" class="src" /></a>
                </div>
                <div class="img">
                  <a href="https://wa.me/" target="_blank"><img src="/assets/images/icons/whatsapp.ico"
                      alt="whatsapp icon" class="src" /></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <br />
  </section>
  <section class="alumniwrapper">
    <section class="alumni">
      <div class="alumni-home">
        <h2>WELCOME TO GSOB INDATWA N'INKESHA ALUMNI</h2>
        <b>The School of excellence in all time</b>
      </div>
    </section>
  </section>
  <section class="alumin-text reveal">
    <h1>What about GSO Butare alumni?</h1>
    <br />
    <div id="row" class="textpar">
      <p>Welcome to our official Alumni where memories flourish, connections endure, and futures unite. Step into a
        digital realm that celebrates the rich tapestry of experiences woven by our esteemed graduates. Whether you're a
        recent alum eager to reconnect or a seasoned veteran of GSOB, this virtual hub serves as a beacon, guiding us
        back to our roots and propelling us forward into the boundless opportunities of tomorrow. Together, let's
        reminisce, inspire, and pave the way for generations to come.</p>
    </div>
    <div id="row">
      <div id="column" class="column1">
        <img src="/assets/images/assembly1.jpg" width="97%" alt="" />
      </div>
      <div id="column" class="column2">
        <div>
          <div class="paragraph">
            <p>At GSOB, excellence isn't just a goal it's a tradition. For decades, our institution has been a cradle of
              knowledge, a forge of character, and a launchpad for dreams. From the halls of academia to the arenas of
              sports, from the stage of the arts to the frontiers of innovation, GSOB alumni have left an indelible mark
              on the world. But our story isn't just about individual achievements; it's about the collective spirit
              that binds us together. It's about the camaraderie forged during late-night study sessions and the
              lifelong friendships kindled on the field of play. It's about the mentors who shaped our minds and the
              peers who shaped our hearts. As we journey through these digital corridors of remembrance, let us not only
              celebrate our triumphs but also reflect on the values that define us. Integrity, perseverance, compassion
              - these are the pillars upon which our alma mater stands, and they are the guiding stars that continue to
              light our way.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="alumni-last reveal">
    <center>
      <h1 style="padding:15px 0;">Alumni in part of politicians</h1>
    </center>
    <br />
    <div class="row">
      <div class="column">
        <img src="/assets/images/ledend1.jpg" alt="King KIGELI v Ndahindurwa J Baptiste" class="src" height="350"
          width="350" />
        <h3>Kigeli V N.J Baptiste</h3>
        <div class="legendspara">
          <p>He was King of Rwandan kingdom and also he was last king of Rwanda before independent.</p>
        </div>
      </div>
      <div class="column">
        <img src="/assets/images/legend3.jpg" alt="" class="src" height="350" width="350" />
        <h3>Melchior Ndadaye</h3>
        <div class="legendspara">
          <p>He was a Burundian banker and politician who became the first democratically elected and first</p>
        </div>
      </div>
      <div class="column">
        <img src="/assets/images/legend4.jpg" alt="" class="src" height="450" width="350" />
        <h3>Joseph Cimpaye</h3>
        <div class="legendspara">
          <p>Joseph Cimpaye. He was a Burundian politician and writer. And he was Born into an educated family.</p>
        </div>
      </div>
    </div>

    <div class="reveal">
      <center>
        <h1 style="padding:15px 0;">Alumni in part of Intertainment</h1>
      </center>
      <br />
      <div class="row">
        <div class="column">
          <img src="/assets/images/star1.jpg" alt="Dream boyz" class="src" height="350" width="350" />
          <h3>Dream boyz</h3>
          <div class="legendspara">
            <p>Rwandan R&B duo composed of Nemeye Platini (Platini) and Claude Mujyanama (T.M.C.).</p>
          </div>
        </div>
        <div class="column">
          <img src="/assets/images/ivan.jpg" alt="" class="src" height="350" width="350" />
          <h3>MAHORO Ivan</h3>
          <div class="legendspara">
            <p>MAHORO Ivan's sports who started his journey back when he was in Our school and now plays for national
              team</p>
          </div>
        </div>
        <div class="column">
          <img src="/assets/images/madison.jpg" alt="" class="src" height="350" width="350" />
          <h3>Madison Placide</h3>
          <div class="legendspara">
            <p>Madison Placide's sports who started his journey back when he was in Our school and now he plays in
              nation team</p>
          </div>
        </div>
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
          <a href="academics">Academics</a>
        </p>
        <p>
          <a href="extracurricular">Extracarricular</a>
        </p>
        <p>
          <a href="news">Updates</a>
        </p>
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
          &copy; All copyright reserved @2025
          <a href="#">By Indatwa</a>
        </center>
      </div>
    </div>
  </footer>
</body>

</html>