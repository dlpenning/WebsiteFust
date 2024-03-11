function checkDateStringValid(dateString) {
  if (dateString === '') {
    return true;
  }

  // Date regex checking for format "01/01/2000" or "01-01-2000"
  const dateRegex = /^\d{1,2}[-\/]\d{1,2}[-\/]\d{4}$/;
  const regexMatch = dateRegex.test(dateString);

  if (!regexMatch) {
    return false;
  }

  const dateParts = dateString.includes('/') ? dateString.split('/') : dateString.split('-');
  const date = new Date(dateParts[2], dateParts[1] - 1, dateParts[0])

  if (isNaN(date.getTime())) {
    return false;
  }

  return true;
}