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

  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function() {
      localStorage.removeItem('desiredRememberMeState');
    });
  }
});
