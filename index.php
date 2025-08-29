<?php
// Include config file
require_once "config/config.php";

// Fetch projects from database
$sql = "SELECT * FROM projects ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch latest about entry
$about = null;
$about_sql = "SELECT * FROM about ORDER BY created_at DESC LIMIT 1";
if ($about_res = mysqli_query($conn, $about_sql)) {
  $about = mysqli_fetch_assoc($about_res);
}

// Fetch contacts
$contacts = [];
$contacts_sql = "SELECT * FROM contacts ORDER BY created_at DESC";
if ($contacts_res = mysqli_query($conn, $contacts_sql)) {
  $contacts = mysqli_fetch_all($contacts_res, MYSQLI_ASSOC);
}
// Fetch educations
$educations = [];
$edu_sql = "SELECT * FROM educations ORDER BY created_at DESC";
if ($edu_res = mysqli_query($conn, $edu_sql)) {
  $educations = mysqli_fetch_all($edu_res, MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Portfolio</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="mediaqueries.css" />
  </head>
  <body>
    <nav id="desktop-nav">
      <div class="logo">Mohammad Moin Uddin Moin</div>
      <div>
        <ul class="nav-links">
          <li><a href="#about">About</a></li>
          <li><a href="#education">Education</a></li>
          <li><a href="#experience">Experience</a></li>
          <li><a href="#projects">My Projects</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </div>
    </nav>
    <nav id="hamburger-nav">
      <div class="logo">Mohammad Moin Uddin Moin</div>
      <div class="hamburger-menu">
        <div class="hamburger-icon" onclick="toggleMenu()">
          <span></span>
          <span></span>
          <span></span>
        </div>
        <div class="menu-links">
          <li><a href="#about" onclick="toggleMenu()">About</a></li>
          <li><a href="#education" onclick="toggleMenu()">Education</a></li>
          <li><a href="#experience" onclick="toggleMenu()">Experience</a></li>
          <li><a href="#projects" onclick="toggleMenu()">Projects</a></li>
          <li><a href="#contact" onclick="toggleMenu()">Contact</a></li>
        </div>
      </div>
    </nav>
    <section id="profile">
      <div class="section__pic-container">
        <img src="./assets/profile-pic.png" alt="Mohammad Moin Uddin Moin profile picture" />
      </div>
      <div class="section__text">
        <p class="section__text__p1">Hello, I'm Mohammad Moin</p>
        <h1 class="title"><span id="rotating-text">CSE student at KUET</span></h1>
        <p class="section__text__p2">I build clean, elegant tech to solve real problems.</p>
        <div class="btn-container">
          <button
            class="btn btn-color-2"
            onclick="window.open('./assets/Mohammad_Moin_Uddin_Moin_Professional_CV.pdf')"
          >
            Download CV
          </button>
          <button class="btn btn-color-1" onclick="location.href='./#contact'">
            Contact Info
          </button>
          <button id="mode-toggle" class="btn btn-color-2" type="button" onclick="toggleDarkMode()">Mode</button>
        </div>
        <div id="socials-container">
          <img
            src="./assets/linkedin.png"
            alt="My LinkedIn profile"
            class="icon"
            onclick="location.href='https://linkedin.com/'"
          />
          <img
            src="./assets/github.png"
            alt="My Github profile"
            class="icon"
            onclick="location.href='https://github.com/'"
          />
        </div>
      </div>
    </section>
    <section id="about">
      <p class="section__text__p1">Get To Know More</p>
      <h1 class="title">About Me</h1>
      <div class="section-container">
        <div class="section__pic-container">
          <img
            src="<?php echo !empty($about['image_url']) ? $about['image_url'] : './assets/about-pic.png'; ?>"
            alt="Profile picture"
            class="about-pic"
          />
        </div>
        <div class="about-details-container">
          <div class="about-containers">
            <div class="details-container">
              <img
                src="./assets/experience.png"
                alt="Experience icon"
                class="icon"
              />
              <h3>Experience</h3>
              <p>2+ years <br />Frontend Development</p>
            </div>
            <div class="details-container">
              <img
                src="./assets/education.png"
                alt="Education icon"
                class="icon"
              />
              <h3>Education</h3>
              <p>B.Sc. in Computer Science and Engineering<br />Khulna University of Engineering and Technology</p>
            </div>
          </div>
          <div class="text-container">
            <p>
              <?php echo isset($about['content']) && $about['content'] !== '' ? htmlspecialchars($about['content']) : 'Hi,, I am Moin From Dhaka,Bangladesh'; ?>
            </p>
          </div>
        </div>
      </div>
      <img
        src="./assets/arrow.png"
        alt="Arrow icon"
        class="icon arrow"
        onclick="location.href='./#education'"
      />
    </section>
    <section id="education">
      <p class="section__text__p1">My Academic Path</p>
      <h1 class="title">Education</h1>
      <div class="experience-details-container">
        <div class="about-containers">
          <?php if(empty($educations)): ?>
            <div class="details-container">
              <h2 class="experience-sub-title">No education records yet</h2>
              <p>Check back later.</p>
            </div>
          <?php else: ?>
            <?php foreach($educations as $edu): ?>
              <div class="details-container">
                <h2 class="experience-sub-title"><?php echo htmlspecialchars($edu['degree']); ?></h2>
                <p><strong><?php echo htmlspecialchars($edu['institution']); ?></strong></p>
                <?php if(!empty($edu['duration'])): ?>
                  <p><?php echo htmlspecialchars($edu['duration']); ?></p>
                <?php endif; ?>
                <?php if(!empty($edu['description'])): ?>
                  <p><?php echo htmlspecialchars($edu['description']); ?></p>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
      <img
        src="./assets/arrow.png"
        alt="Arrow icon"
        class="icon arrow"
        onclick="location.href='./#experience'"
      />
    </section>
    <section id="experience">
      <p class="section__text__p1">Explore My</p>
      <h1 class="title">Experience</h1>
      <div class="experience-details-container">
        <div class="about-containers">
          <div class="details-container">
            <h2 class="experience-sub-title">Frontend Development</h2>
            <div class="article-container">
              <article>
                <img
                  src="./assets/checkmark.png"
                  alt="Experience icon"
                  class="icon"
                />
                <div>
                  <h3>HTML</h3>
                  <p>Experienced</p>
                </div>
              </article>
              <article>
                <img
                  src="./assets/checkmark.png"
                  alt="Experience icon"
                  class="icon"
                />
                <div>
                  <h3>CSS</h3>
                  <p>Experienced</p>
                </div>
              </article>
              <article>
                <img
                  src="./assets/checkmark.png"
                  alt="Experience icon"
                  class="icon"
                />
                <div>
                  <h3>SASS</h3>
                  <p>Intermediate</p>
                </div>
              </article>
              <article>
                <img
                  src="./assets/checkmark.png"
                  alt="Experience icon"
                  class="icon"
                />
                <div>
                  <h3>JavaScript</h3>
                  <p>Basic</p>
                </div>
              </article>
              <article>
                <img
                  src="./assets/checkmark.png"
                  alt="Experience icon"
                  class="icon"
                />
                <div>
                  <h3>TypeScript</h3>
                  <p>Basic</p>
                </div>
              </article>
              <article>
                <img
                  src="./assets/checkmark.png"
                  alt="Experience icon"
                  class="icon"
                />
                <div>
                  <h3>Material UI</h3>
                  <p>Intermediate</p>
                </div>
              </article>
            </div>
          </div>
          <div class="details-container">
            <h2 class="experience-sub-title">Frontend Development</h2>
            <div class="article-container">
              <article>
                <img
                  src="./assets/checkmark.png"
                  alt="Experience icon"
                  class="icon"
                />
                <div>
                  <h3>PostgreSQL</h3>
                  <p>Basic</p>
                </div>
              </article>
              <article>
                <img
                  src="./assets/checkmark.png"
                  alt="Experience icon"
                  class="icon"
                />
                <div>
                  <h3>Node JS</h3>
                  <p>Intermediate</p>
                </div>
              </article>
              <article>
                <img
                  src="./assets/checkmark.png"
                  alt="Experience icon"
                  class="icon"
                />
                <div>
                  <h3>Express JS</h3>
                  <p>Intermediate</p>
                </div>
              </article>
              <article>
                <img
                  src="./assets/checkmark.png"
                  alt="Experience icon"
                  class="icon"
                />
                <div>
                  <h3>Git</h3>
                  <p>Intermediate</p>
                </div>
              </article>
            </div>
          </div>
        </div>
      </div>
      <img
        src="./assets/arrow.png"
        alt="Arrow icon"
        class="icon arrow"
        onclick="location.href='./#projects'"
      />
    </section>
    <section id="projects">
      <p class="section__text__p1">Browse My Recent</p>
      <h1 class="title">Projects</h1>
      <div class="experience-details-container">
        <div class="about-containers">
          <?php if(empty($projects)): ?>
            <div class="details-container color-container">
              <div class="article-container">
                <img
                  src="./assets/project-1.png"
                  alt="Project 1"
                  class="project-img"
                />
              </div>
              <h2 class="experience-sub-title project-title">Project One</h2>
              <div class="btn-container">
                <button
                  class="btn btn-color-2 project-btn"
                  onclick="location.href='https://github.com/'"
                >
                  Github
                </button>
                <button
                  class="btn btn-color-2 project-btn"
                  onclick="location.href='https://github.com/'"
                >
                  Live Demo
                </button>
              </div>
            </div>
          <?php else: ?>
            <?php foreach($projects as $project): ?>
              <div class="details-container color-container">
                <div class="article-container">
                  <img
                    src="<?php echo !empty($project['image_url']) ? $project['image_url'] : './assets/project-1.png'; ?>"
                    alt="<?php echo htmlspecialchars($project['title']); ?>"
                    class="project-img"
                  />
                </div>
                <h2 class="experience-sub-title project-title"><?php echo htmlspecialchars($project['title']); ?></h2>
                <p class="project-description"><?php echo htmlspecialchars($project['description']); ?></p>
                <div class="btn-container">
                  <?php if(!empty($project['github_url'])): ?>
                    <button
                      class="btn btn-color-2 project-btn"
                      onclick="location.href='<?php echo htmlspecialchars($project['github_url']); ?>'"
                    >
                      Github
                    </button>
                  <?php endif; ?>
                  <?php if(!empty($project['demo_url'])): ?>
                    <button
                      class="btn btn-color-2 project-btn"
                      onclick="location.href='<?php echo htmlspecialchars($project['demo_url']); ?>'"
                    >
                      Live Demo
                    </button>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
      <img
        src="./assets/arrow.png"
        alt="Arrow icon"
        class="icon arrow"
        onclick="location.href='./#contact'"
      />
    </section>
    <section id="contact">
      <p class="section__text__p1">Get in Touch</p>
      <h1 class="title">Contact Me</h1>
      <div class="contact-info-upper-container">
        <?php if(empty($contacts)): ?>
          <div class="contact-info-container">
            <img src="./assets/email.png" alt="Email icon" class="icon contact-icon email-icon" />
            <p><a href="mailto:examplemail@gmail.com">Example@gmail.com</a></p>
          </div>
        <?php else: ?>
          <?php foreach($contacts as $contact): ?>
            <div class="contact-info-container">
              <?php 
                $label = ucfirst(strtolower($contact['type'])) . ':';
              ?>
              <p>
                <strong><?php echo htmlspecialchars($label); ?></strong>
                <?php if (stripos($contact['type'], 'email') !== false): ?>
                  <a href="mailto:<?php echo htmlspecialchars($contact['value']); ?>"><?php echo htmlspecialchars($contact['value']); ?></a>
                <?php elseif (stripos($contact['type'], 'phone') !== false || preg_match('/^[+0-9\-()\s]+$/', $contact['value'])): ?>
                  <a href="tel:<?php echo preg_replace('/[^+0-9]/', '', $contact['value']); ?>"><?php echo htmlspecialchars($contact['value']); ?></a>
                <?php elseif (preg_match('/^https?:/i', $contact['value'])): ?>
                  <a href="<?php echo htmlspecialchars($contact['value']); ?>" target="_blank" rel="noreferrer noopener"><?php echo htmlspecialchars($contact['value']); ?></a>
                <?php else: ?>
                  <?php echo htmlspecialchars($contact['value']); ?>
                <?php endif; ?>
              </p>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>
    <footer>
      <p>Copyright &#169; 2023 Mohammad Moin. All Rights Reserved. <a href="login.php" style="font-size: 0.8rem; margin-left: 10px;">Admin Login</a></p>
    </footer>
    <script src="script.js"></script>
  </body>
</html>