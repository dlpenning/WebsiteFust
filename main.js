const body = document.querySelector('body')
const navScreen = document.querySelector('.nav-screen')
const navBurger = document.querySelector('.nav-burger')
const navClose = document.querySelector('.nav-close')

if (navBurger) {
  navBurger.addEventListener('click', () => {
    openNav()
  })
}

function openNav() {
  body.classList.add('overflow-hidden')
  navScreen.classList.add('open')

  navClose.addEventListener('click', () => {
    closeNav()
  })
}

function closeNav() {
  body.classList.remove('overflow-hidden')
  navScreen.classList.remove('open')
}