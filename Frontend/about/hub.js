loadNavBar();

/* Displaying Members Info */
const liadText = 'Liad Mandil is a student at Ruppin Academic Center. He is interested in artificial intelligence.';
const etgarText = 'Etgar Avshalomov is a student at Ruppin Academic Center. He is interested in blockchain development.';
const benText = 'Ben Gofman is a student at Ruppin Academic Center. He is interested in cyber security.';
const maxText = 'Maxim Prokopchuk is a student at Ruppin Academic Center. He is interested in web development.';
const managerNumber = '054-535-8768';
const managerEmail = 'etgar.avshalomov@gmail.com';

const liad = document.getElementById('liad-text');
const etgar = document.getElementById('etgar-text');
const ben = document.getElementById('ben-text');
const max = document.getElementById('max-text');
const managerContactNumber = document.getElementById('team-contact-number');
const managerContactEmail = document.getElementById('team-contact-email');

liad.innerHTML = liadText;
etgar.innerHTML = etgarText;
ben.innerHTML = benText;
max.innerHTML = maxText;
managerContactNumber.innerHTML = managerNumber;
managerContactEmail.innerHTML = managerEmail;

/* Description Sounds */
const audio = new Audio();

const liadSound = () => {
    audio.src = 'sound/liad.mp3';
    audio.play();
}
const etgarSound = () => {
    audio.src = 'sound/etgar.mp3';
    audio.play();
}
const benSound = () => {
    audio.src = 'sound/ben.mp3';
    audio.play();
}
const maxSound = () => {
    audio.src = 'sound/max.mp3';
    audio.play();
} 

