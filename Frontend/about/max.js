loadNavBar();

/* Displaying Member Info */
const maxText = 'Maxim Prokopchuk is a student at Ruppin Academic Center. He is interested in web development. He likes to design interfaces and explore modern front-end frameworks.';
const maxEmail = 'maxooonzzz@gmail.com';
const maxPhone = '054-993-9961';

const maxParagraph = document.getElementById('max-text');
const maxEmailSpan = document.getElementById('max-email');
const maxPhoneSpan = document.getElementById('max-phone');

maxParagraph.innerHTML = maxText;
maxEmailSpan.innerHTML = maxEmail;
maxPhoneSpan.innerHTML = maxPhone;