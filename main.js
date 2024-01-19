/**
 * Set up event listeners for the mobile navigation screen
 */
function setupNavigation() {
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
}

setupNavigation()


/**
 * Set up event listeners for members sign-up page
 */
function setupMemberSignup() {
  const wpcf7Form = document.querySelector('.wpcf7-form')
  const institutionSelect = document.querySelector('[name="institution"]')


  if (institutionSelect) {
    institutionSelect.addEventListener('change', (e) => {
      if (e.target.value === 'No study in Tilburg') {
        wpcf7Form.setAttribute('data-institution-else-visible', 'true')
      } else {
        wpcf7Form.setAttribute('data-institution-else-visible', 'false')
      }
    })
  }
}

setupMemberSignup()
