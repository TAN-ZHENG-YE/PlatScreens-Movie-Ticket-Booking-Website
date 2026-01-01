const toggleButton = document.querySelector('.toggle');
const toggleBall = document.querySelector('.toggle-ball');
const body = document.body;
const navbar = document.querySelector('.navbar');
const profileText = document.querySelector('.profile-text');
const logo = document.querySelector('.logo');
const menuItems = document.querySelectorAll('.menu-list-item');
const hero = document.querySelector('.hero'); 
const heroH1 = document.querySelector('.heading h1');
const heroContent = document.querySelectorAll('.hero-content h2, .hero-content p');
const wrappers = document.querySelectorAll('.wrapper'); // Changed to plural
const inputs = document.querySelectorAll('.input-box input');



let isLightMode = false; // Track current mode
const applyLightMode = () => {
  body.classList.add('light-mode');
  body.classList.remove('dark-mode');
  toggleBall.style.transform = 'translateX(0px)';
  body.style.backgroundColor = '#ffffff';
  navbar.style.backgroundColor = '#ffffff';
  profileText.style.color = 'darkblue';
  logo.style.color = 'deepskyblue';
  menuItems.forEach(item => item.style.color = '#000000');
  hero.style.backgroundColor = '#ffffff';
  heroH1.style.color = 'deepskyblue';
  heroContent.forEach(element => element.style.color = '#000000');
  wrappers.forEach(wrapper => wrapper.style.backgroundColor = '#ffffff');
  inputs.forEach(input => input.style.border = '2px solid black');
};

const applyDarkMode = () => {
  body.classList.add('dark-mode');
  body.classList.remove('light-mode');
  toggleBall.style.transform = 'translateX(25px)';
  body.style.backgroundColor = '#000000';
  navbar.style.backgroundColor = 'black';
  profileText.style.color = '#ffffff';
  logo.style.color = 'deepskyblue';
  menuItems.forEach(item => item.style.color = 'rgb(56, 189, 230)');
  hero.style.backgroundColor = '#211f30';
  heroH1.style.color = 'yellow';
  heroContent.forEach(element => element.style.color = '#ffffff');
  wrappers.forEach(wrapper => wrapper.style.backgroundColor = '#000000');
  inputs.forEach(input => input.style.border = '2px solid white');
};


// Event listener for the toggle button
toggleButton.addEventListener('click', () => {
  isLightMode = !isLightMode; // Toggle mode
  
  if (isLightMode) {
    applyLightMode();
  } else {
    applyDarkMode();
  }
});

// Set initial mode to dark mode
applyDarkMode();
