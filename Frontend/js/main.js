function init() {
  loadNavBar();
  loadHomePage();
}

// --- דף הבית ---
function loadHomePage() {
  let container = document.getElementById("container");
  clearMain(container);

  let homePage = document.createElement("div");
  homePage.classList.add("home-page");

  const heroSlider = createHeroSlider();
  const aboutSection = createAboutSection();
  const actionSection = createActionSection();

  homePage.appendChild(heroSlider);
  homePage.appendChild(actionSection);
  homePage.appendChild(aboutSection);

  container.appendChild(homePage);

  startHeroSlider();
}

function createHeroSlider() {
  const heroSlider = document.createElement("div");
  heroSlider.id = "hero-slider";

  const images = [
    "./images/slide1.jpeg",
    "./images/slide2.jpeg",
    "./images/slide3.jpeg",
  ];

  images.forEach((src, index) => {
    let img = document.createElement("img");
    img.src = src;
    img.alt = "Slide " + (index + 1);
    img.classList.add("slide");
    if (index === 0) img.classList.add("active"); // התמונה הראשונה תהיה פעילה
    heroSlider.appendChild(img);
  });

  // טקסט
  const sliderText = document.createElement("div");
  sliderText.className = "slider-text";
  sliderText.textContent = "ASIAN KITCHEN & SUSHI BAR";

  heroSlider.appendChild(sliderText);

  //חץ למטה
  const downArrow = document.createElement("div");
  downArrow.classList.add("down-arrow");
  downArrow.innerHTML = "⬇";
  heroSlider.appendChild(downArrow);

  return heroSlider;
}

function startHeroSlider() {
  let currentSlide = 0;
  const slides = document.querySelectorAll("#hero-slider .slide");

  // להפעיל מיד את התמונה הראשונה
  slides[currentSlide].classList.add("active");

  setInterval(() => {
    // להוריד את התמונה הנוכחית
    slides[currentSlide].classList.remove("active");

    // לעבור לתמונה הבאה
    currentSlide = (currentSlide + 1) % slides.length;

    // להדליק את התמונה הבאה
    slides[currentSlide].classList.add("active");
  }, 8000); // כל 8 שניות לעבור תמונה
}

function createAboutSection() {
  
  const aboutSection = document.createElement("div");
  aboutSection.classList.add("about-section");

  const aboutContainer = document.createElement("div");
  aboutContainer.classList.add("about-container");

  const listContainer = document.createElement("div");
  listContainer.classList.add("list-container");

  const h1 = document.createElement("h1");
  h1.textContent = "דילסיה - Fine Cusine";
  aboutContainer.appendChild(h1);

  const p = document.createElement("p");
  p.style.marginTop = "40px";
  p.innerHTML = `
מסעדה אסייתית עדכנית, שילוב בין אוכל אסייתי מסורתי לבר קוקטיילים חדשני.
מקום צעיר עם אוירה ייחודית שבאים לאכול ולבלות בו עם המשפחה והחברים.
בין אם זה דייט על הבר, מפגש עם חברים, או סתם חשק לסושי איכותי,
דליסיה הוא בהחלט המקום האידיאלי לכך.
<br><br>
אנו פתוחים 7 ימים בשבוע. א'-ד' - 12:00-22:30 ו' 23:00 ה' ש' 23:30
נשמח לראותכם בין אורחינו.
`;

  aboutContainer.appendChild(p);
  const ol = document.createElement("ol");
  ol.style.textAlign = "right";
  ol.style.marginRight = "45%";
  const li1 = document.createElement("li");
  const li2 = document.createElement("li");
  const li3 = document.createElement("li");
  li1.innerHTML = "אוכל אסייתי מסורתי";
  li2.innerHTML = "בר קוקטיילים חדשני";
  li3.innerHTML = "אוירה ייחודית";
  ol.appendChild(li1);
  ol.appendChild(li2);
  ol.appendChild(li3);

  listContainer.appendChild(ol);
  aboutSection.appendChild(aboutContainer);
  aboutSection.appendChild(listContainer);

  const repoName = "Delicia";
  const contactDetails = document.createElement("div");
  
  const logo = document.createElement("div");
  logo.className = "logo";
  logo.innerHTML = `<img src="images/logo.png" alt="דליסיה">`;
  contactDetails.appendChild(logo);

  contactDetails.className = "contact-details";
  const details = document.createElement("p");
  details.innerHTML = "אל תדבר לסושי, דבר איתנו!<br> יש לכם שאלות? רעיונות למנות חדשות?<br/> דברו איתנו!<br/><br/>אימייל: info@delicia.co.il<br/> טלפון: 03-1234567";
  contactDetails.appendChild(details);
  aboutSection.appendChild(contactDetails);

  return aboutSection;
}

function createActionSection() {
  const actionSection = document.createElement("div");
  actionSection.classList.add("action-section");

  // 1. טופס הזמנת שולחן
  const reserveDiv = document.createElement("div");
  reserveDiv.classList.add("reservation-form-container");
  reserveDiv.innerHTML = `
    <form id="reservationForm">
      <h2>הזמנת שולחן</h2>
      <input type="text"   name="name"    placeholder="שם מלא"      required>
      <input type="text"    name="phone"   placeholder="טלפון"      required>
      <input type="date"   name="date"    required>
      <input type="time"   name="time"    required>
      <button type="submit">שגר הזמנה!</button>
    </form>
  `;
  actionSection.appendChild(reserveDiv);

  reserveDiv
    .querySelector("#reservationForm")
    .addEventListener("submit", (e) => {
      e.preventDefault();

      const form = e.target;
      const customerName = form.querySelector('input[name="name"]').value;
      const customerPhone = form.querySelector('input[name="phone"]').value;

      console.log("Name:", customerName);
      console.log("Phone:", customerPhone);

      const message = `תודה רבה ${customerName}! ניצור קשר בהקדם במספר ${customerPhone} 😊`;

      reserveDiv.innerHTML = `<p class="thank-you"></p>` + message;
    });

  return actionSection;
}

function startHeroSlider() {
  const slides = document.querySelectorAll("#hero-slider .slide");
  let currentIndex = 0;

  setInterval(() => {
    slides[currentIndex].classList.remove("active");
    currentIndex = (currentIndex + 1) % slides.length;
    slides[currentIndex].classList.add("active");
  }, 3000);
}

function clearMain(container) {
  container.innerHTML = "";
}

init();
