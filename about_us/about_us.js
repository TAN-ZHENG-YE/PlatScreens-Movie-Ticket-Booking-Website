const toggleButton = document.querySelector('.toggle');
const toggleBall = document.querySelector('.toggle-ball');
const body = document.body;
const navbar = document.querySelector('.navbar');
const profileText = document.querySelector('.profile-text');
const logo = document.querySelector('.logo');
const menuItems = document.querySelectorAll('.menu-list-item');
const hero = document.querySelector('.hero'); // Target the hero section directly
const heroH1 = document.querySelector('.heading h1'); // Target the hero section directly
const heroContent = document.querySelectorAll('.hero-content h2, .hero-content p');

let isLightMode = false; // Track current mode

toggleButton.addEventListener('click', () => {
  isLightMode = !isLightMode; // Toggle mode
  
  if (isLightMode) {
    // Light mode
    body.style.backgroundColor = '#ffffff';
    profileText.style.color = 'darkblue';
    logo.style.color = 'deepskyblue';
    menuItems.forEach(item => item.style.color = '#000000');
    hero.style.backgroundColor = '#ffffff'; // Change hero background color to white
    heroH1.style.color = 'deepskyblue';
    heroContent.forEach(element => element.style.color = '#000000');
  } else {
    // Dark mode
    body.style.backgroundColor = '#000000';
    profileText.style.color = '#ffffff';
    logo.style.color = 'deepskyblue';
    menuItems.forEach(item => item.style.color = 'rgb(56, 189, 230)');
    hero.style.backgroundColor = '#211f30'; // Change hero background color back to original
    heroH1.style.color = 'yellow';  
    heroContent.forEach(element => element.style.color = '#000000');  
  }
});







