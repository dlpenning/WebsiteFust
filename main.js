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


document.addEventListener('DOMContentLoaded', function() {
  // Ensure the DOM is fully loaded before adding the event listener
  const form = document.getElementById('fust-membership-form');

  if (form) {
    form.addEventListener('submit', function(event) {
        // Prevent default form submission
        event.preventDefault();

        // Create FormData object from the form element
        const formData = new FormData(form);

        // Convert FormData to a plain object
        const formObject = Object.fromEntries(formData.entries());

        // Stripe import performed by CDN in `head.php`
        const stripe = Stripe('pk_test_51PnxmuBGvnR2SbwjyRras0MFDaRcdBPdApPbVopo3knp4nmPeIcmFRu5tDSlcn1NgYvWfHxKN5xAcDKRJuogY4ao00JxxcOQ3H'); // Replace with your Stripe publishable key

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
  } else {
      console.error('Form not found');
  }
});
