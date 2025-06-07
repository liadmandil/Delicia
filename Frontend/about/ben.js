loadNavBar();

/* Displaying Member Info */
const benText = 'Ben Gofman is a student at Ruppin Academic Center. He is interested in cyber security. He is passionate about protecting digital systems and learning how to prevent cyber attacks.';
const benEmail = 'bengofman2@gmail.com';
const benPhone = '052-386-5236';

const benParagraph = document.getElementById('ben-text');
const benEmailSpan = document.getElementById('ben-email');
const benPhoneSpan = document.getElementById('ben-phone');

benParagraph.innerHTML = benText;
benEmailSpan.innerHTML = benEmail;
benPhoneSpan.innerHTML = benPhone;