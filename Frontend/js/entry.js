document.addEventListener("DOMContentLoaded", () => {
  if (shouldShowRegister) {
    const registerForm = document.getElementById("register-form");
    if (registerForm) {
      registerForm.classList.remove("hidden");
    }
  }

  // טען את ה-navbar (אם יש פונקציית loadNavBar בקובץ navbar.js)
  if (typeof loadNavBar === "function") {
    loadNavBar();
  }
});
