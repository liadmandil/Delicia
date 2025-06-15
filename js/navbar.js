function loadNavBar() {
  const navbar = document.getElementById("navbar");
  navbar.lang = "he";
  navbar.dir = "rtl";
  // כפתור המבורגר
  const hamburger = document.createElement("button");
  hamburger.className = "hamburger";
  hamburger.innerHTML = `
              <span></span>
              <span></span>
              <span></span>
            `;

  const ul = document.createElement("ul");
  ul.className = "nav-items";

  // Adjusting the paths
  const path = window.location.pathname;
  const prefix = path.includes("/about/") ? "../" : "";

  const navItems = [
    { text: "יצירת קשר", href: `${prefix}contact.html` },
    { text: "אלבום תמונות", href: `${prefix}gallery.html` },
    { text: "תפריטים", href: `${prefix}menu.php` },
    { text: "קצת עלינו", href: `${prefix}about/hub.html` },
    { text: "צור קשר עם התמיכה", href: `${prefix}ContactUsForm.php` },
    { text: "השאר חוות דעת", href: `${prefix}review.html` },
  ];

  // הוספת הלוגו בראש
  const logoLi = document.createElement("li");
  const logoLink = document.createElement("a");
  logoLink.href = `${prefix}index.php`;
  const logo = document.createElement("div");
  logo.className = "logo";
  logo.innerHTML = `<img src="${prefix}images/logo.png" alt="דליסיה">`;
  logoLink.appendChild(logo);
  logoLi.appendChild(logoLink);
  ul.appendChild(logoLi);

  // הוספת שאר הפריטים
  navItems.forEach((item) => {
    const li = document.createElement("li");

    if (item.type === "logo") {
    } else {
      li.className = "nav-item";
      const a = document.createElement("a");
      a.textContent = item.text;
      a.href = item.href;
      a.style.textDecoration = "none";
      a.style.color = "black";
      li.appendChild(a);
    }

    ul.appendChild(li);
  });

  navbar.appendChild(hamburger);
  navbar.appendChild(ul);

  // פתיחה/סגירה
  hamburger.addEventListener("click", () => {
    navbar.classList.toggle("open");
    hamburger.classList.toggle("active");
  });
}
