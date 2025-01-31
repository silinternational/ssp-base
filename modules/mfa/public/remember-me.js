document.addEventListener('DOMContentLoaded', function() {
  const rememberMeCheckbox = document.getElementById('rememberMe');
  
  if (rememberMeCheckbox) {
    const desiredRememberMeState = localStorage.getItem('desiredRememberMeState');
    if (desiredRememberMeState === 'true') {
      rememberMeCheckbox.checked = true;
    }

    rememberMeCheckbox.addEventListener('change', function() {
      localStorage.setItem('desiredRememberMeState', rememberMeCheckbox.checked);
    });
  }
});
