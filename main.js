/**
 * Set up event listeners for the mobile navigation screen
 */
function setupNavigation() {
  const body = document.querySelector('body')
  const navScreen = document.querySelector('.nav-screen')
  const navBurger = document.querySelector('.nav-burger')
  const navClose = document.querySelector('.nav-close')

  if (navBurger) {
    navBurger.addEventListener('click', openNav)
  }

  function openNav() {
    // Add classes to open the nav
    body.classList.add('overflow-hidden');
    navScreen.classList.add('open');
  
    // Automatically open ancestor submenus of the current menu item in the mobile nav
    const navScreenMenu = navScreen.querySelector('.menu'); // Target nav-screen's menu only
    const currentItem = navScreenMenu.querySelector('.current-menu-item'); // Use this menu only
  
    if (currentItem) {
      let parent = currentItem.closest('.current-menu-ancestor');
  
      // Traverse and open all ancestor menu items
      while (parent) {
        parent.classList.add('open'); // Add 'open' class
        parent = parent.parentElement.closest('.current-menu-ancestor'); // Move to next ancestor
      }
    }
  
    // Attach event to close button
    navClose.addEventListener('click', closeNav);
  }
  

  function closeNav() {
    body.classList.remove('overflow-hidden')
    navScreen.classList.remove('open')
  }
  
  // Add event listener to submenus to toggle them open/closed
  const menuItems = document.querySelectorAll('.menu-item-has-children > a');
  
  menuItems.forEach((menuItem) => {
    menuItem.addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default link behavior

        const parentItem = this.parentElement;

        // Toggle 'open' class on the clicked item
        parentItem.classList.toggle('open');

        // Optional: Close other open submenus if necessary
        parentItem
            .parentElement
            .querySelectorAll('.menu-item-has-children')
            .forEach((sibling) => {
                if (sibling !== parentItem) {
                    sibling.classList.remove('open');
                }
            });
        });
    });
}


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


/**
 * Set up an event listener to catch the form submission of the (normal) member
 * signup form and send the user to a Stripe external payment session.
 */
function setupMemberSignupSubmission(form) {
  document.addEventListener('wpcf7mailsent', function(event) {
    // Prevent default form submission
    event.preventDefault();

    // Create FormData object from the form element and convert to plain object
    const formData = new FormData(form);
    const formObject = Object.fromEntries(formData.entries());

    // Stripe import performed by CDN in `head.php`
    const stripe = Stripe('pk_live_51PnxmuBGvnR2SbwjS59okVDU8zrXDjKjcshTrdYyzG4Fwa1grvyCQioj5Kq4szrwqWWcwdGzq3T8aTnd5xtvdPLn009rbkmvQH');

    // Create a Checkout Session
    fetch('/wp-json/stripe/v1/create-checkout-session', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        formData: formObject,
      }),
    })
    .then(response => response.json())
    .then(session => {
      const { success, data } = session;
      if (!success) {
        throw "Failed to set up payment session";
      }
      // Redirect to Stripe Checkout
      return stripe.redirectToCheckout({ sessionId: data.id });
    })
    .catch(error => {
      console.error('Error:', error);
    });
  });
}

/**
 * Set up an event listener to catch the form submission of the (normal) member
 * signup form and send the user to a Stripe external payment session.
 */
function setupGuestMemberSignupSubmission(form) {
  document.addEventListener('wpcf7mailsent', function(event) {
    // Prevent default form submission
    event.preventDefault();

    // Create FormData object from the form element and convert to plain object
    const formData = new FormData(form);
    const formObject = Object.fromEntries(formData.entries());

    fetch('/wp-json/guest-member/v1/apply-as-guest-member', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        formData: formObject,
      }),
    })
    .catch(error => {
      console.error('Error:', error);
    });
  });
}


/**
 * Set up 
 */
document.addEventListener('DOMContentLoaded', function() {
  // Ensure the DOM is fully loaded before adding the event listener
  const form = document.getElementById('fust-membership-form');
  const guestForm = document.getElementById('fust-guest-membership-form');

  setupNavigation()
  setupMemberSignup()

  if (form) {
    setupMemberSignupSubmission(form);
  }

  if (guestForm) {
    setupGuestMemberSignupSubmission(guestForm);
  }
});
