const btn = document.querySelector('.btn');
const header = document.querySelector('header');


const nav = document.querySelector("nav");
const toggleBtn = document.querySelector('.menu-hamburger');
toggleBtn.addEventListener('click', ()=> {
  nav.classList.toggle('show');
})