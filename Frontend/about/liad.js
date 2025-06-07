loadNavBar();

/* Displaying Member Info */
const liadText = 'Liad Mandil is a student at Ruppin Academic Center. He is interested in artificial intelligence. In his free time, he likes to read tech blogs and experiment with AI models.';
const liadEmail = 'liadmandil01@gmail.com';
const liadPhone = '054-209-9293';

const liadParagraph = document.getElementById('liad-text');
const liadEmailSpan = document.getElementById('liad-email');
const liadPhoneSpan = document.getElementById('liad-phone');

liadParagraph.innerHTML = liadText;
liadEmailSpan.innerHTML = liadEmail;
liadPhoneSpan.innerHTML = liadPhone;