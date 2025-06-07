loadNavBar();

/* Displaying Member Info */
const etgarText = 'Etgar Avshalomov is a student at Ruppin Academic Center. He is interested in blockchain development. He likes to experiment with smart contracts and stay updated on the latest trends in the crypto world.';
const etgarEmail = 'etgar.avshalomov@gmail.com';
const etgarPhone = '054-535-8768';

const etgarParagraph = document.getElementById('etgar-text');
const etgarEmailSpan = document.getElementById('etgar-email');
const etgarPhoneSpan = document.getElementById('etgar-phone');

etgarParagraph.innerHTML = etgarText;
etgarEmailSpan.innerHTML = etgarEmail;
etgarPhoneSpan.innerHTML = etgarPhone;