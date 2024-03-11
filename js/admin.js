/**
 * Entrypoint
 */
function entrypoint() {
  console.log('%cInitialized admin.js!', 'color: #29cc8d; font-weight: bold;');

  setupActivityPostTypeValidation();
}

document.addEventListener('DOMContentLoaded', entrypoint);

function setupActivityPostTypeValidation() {
  const fustDateInput = document.getElementById('fust_date')
  if (!fustDateInput) {
    return;
  }

  /**
   * Validate the date input format
   * 
   * @param e FocusEvent
   */
  const handleBlur = (e) => {
    const value = e.currentTarget?.value
    const inputValid = checkDateStringValid(value)
    const fustDateErrorLabel = document.getElementById('fust_date_error')

    if (!inputValid) {
      fustDateInput.classList.add('error')

      if (!fustDateErrorLabel) {
        // Create error label and append it after fustDateInput in the DOM
        const errorLabel = document.createElement('div');
        errorLabel.id = 'fust_date_error';
        errorLabel.className = 'error-label';
        errorLabel.textContent = 'Invalid date format';

        // Append error label after fustDateInput
        fustDateInput.parentNode.insertBefore(errorLabel, fustDateInput.nextSibling);
      }

      return;
    }

    // If input is valid, attempt to remove errors if they exist
    fustDateInput.classList.remove('error')
    if (fustDateErrorLabel) {
      fustDateErrorLabel.parentNode.removeChild(fustDateErrorLabel);
    }

    // Parse and reformat the date
    const dateParts = value.includes('/') ? value.split('/') : value.split('-');
    const date = new Date(dateParts[2], dateParts[1] - 1, dateParts[0])

    fustDateInput.value = date.toLocaleDateString('en-GB'); // 'en-GB' specifies the format as dd/mm/yyyy
  }

  fustDateInput.addEventListener('blur', (e) => handleBlur(e))
}