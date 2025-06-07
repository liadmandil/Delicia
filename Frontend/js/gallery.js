function init() {
  loadNavBar();
  loadHomePage();
}

function loadHomePage() {
  let container = document.getElementById("container");
  clearMain(container);

  let homePage = document.createElement("div");
  homePage.classList.add("home-page");

  const heroSlider = createHeroSlider();

  homePage.appendChild(heroSlider);
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
    "images/slide4.jpeg",
    "images/slide5.jpeg",
    "images/slide6.jpeg",
    "images/slide7.jpeg"
  ];

  images.forEach((src, index) => {
    let img = document.createElement("img");
    img.src = src;
    img.alt = "Slide " + (index + 1);
    img.classList.add("slide");
    if (index === 0) img.classList.add("active"); // התמונה הראשונה תהיה פעילה
    heroSlider.appendChild(img);
  });

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
  }, 3000); // כל 3 שניות לעבור תמונה
}

function clearMain(container) {
  container.innerHTML = "";
}

init();
