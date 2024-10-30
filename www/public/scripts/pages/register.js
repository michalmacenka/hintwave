import fetchData from "../common/fetchData.js";

// Birth date inputs

const daysInMonth = (month, year) => new Date(year, month, 0).getDate();

const yearSelect = document.getElementById('birth_year');
const monthSelect = document.getElementById('birth_month');
const daySelect = document.getElementById('birth_day');

const updateDays = () => {
  const year = parseInt(yearSelect.value);
  const month = parseInt(monthSelect.value);
  const numDays = daysInMonth(month, year);

  daySelect.innerHTML = '';

  for (let i = 1; i <= numDays; i++) {
    const option = document.createElement('option');
    option.value = i;
    option.text = i;
    daySelect.appendChild(option);
  }
}

yearSelect.addEventListener('change', updateDays);
monthSelect.addEventListener('change', updateDays);

window.onload = updateDays;


// Form validation

document.querySelector('form').addEventListener('submit', async (event) => {
  event.preventDefault();

  document.querySelectorAll('.errMsg').forEach(div => {
    div.style.display = 'none';
    div.textContent = '';
  });

  const errors = [];
  const form = event.target;

  // Validate username
  const username = form.username.value;
  if (!username || username.length < 3 || username.length > 35) {
    errors.push({
      field: form.username,
      message: 'Username must be between 3 and 35 characters long'
    });
  }

  // Validate password
  const password = form.password.value;
  if (!password || password.length < 8 || !/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.#^()+])[A-Za-z\d@$!%*?&.#^()+]{8,}$/.test(password)) {
    errors.push({
      field: form.password,
      message: 'Password must be at least 8 characters long, including uppercase, lowercase, number and special character'
    });
  }

  // Validate confirm password
  const confirmPassword = form.confirm_password.value;
  if (password !== confirmPassword) {
    errors.push({
      field: form.confirm_password,
      message: 'Passwords do not match'
    });
  }

    // Validate image if uploaded
    const imageInput = form.profile_image;
    if (imageInput.files.length > 0) {
      const file = imageInput.files[0];
      const maxSize = 5 * 1024 * 1024; // 5MB
      const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
  
      if (!allowedTypes.includes(file.type)) {
        errors.push({
          field: imageInput,
          message: 'Please upload JPEG, PNG or WebP image'
        });
      }
  
      if (file.size > maxSize) {
        errors.push({
          field: imageInput,
        message: 'Image must be smaller than 5MB'
      });
    }
  }

  // Validate birth date
  const year = parseInt(form.birth_year.value);
  const month = parseInt(form.birth_month.value);
  const day = parseInt(form.birth_day.value);

  const birthDate = new Date(year, month - 1, day);
  const today = new Date();

  let age = today.getFullYear() - birthDate.getFullYear();
  const monthDiff = today.getMonth() - birthDate.getMonth();

  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }

  if (age < 13) {
    errors.push({
      field: form.querySelector('.birthDateGroup'),
      message: 'You must be at least 13 years old to register'
    });
  }


  if (errors.length > 0) {
    errors.forEach(error => {
      if (error.field.classList.contains('birthDateGroup')) {
        const errorDiv = error.field.querySelector('.errMsg');
        errorDiv.textContent = error.message;
        errorDiv.style.display = 'block';
      } else {
        showError(error.field, error.message);
      }
    });
    return;
  }

  try {
    const data = {
      username: form.username.value,
      password: form.password.value,
      confirm_password: form.confirm_password.value,
      birth_year: form.birth_year.value,
      birth_month: form.birth_month.value, 
      birth_day: form.birth_day.value,
      csrf_token: form.querySelector('input[name="csrf_token"]').value
    };

    
    const imageFile = form.profile_image.files[0];
    if (imageFile) {
        const base64Image = await new Promise((resolve) => {
            const reader = new FileReader();
            reader.onloadend = () => resolve(reader.result);
            reader.readAsDataURL(imageFile);
        });
        data.profile_image = {
            data: base64Image,
            type: imageFile.type,
            size: imageFile.size
        };
    }

    
    const response = await fetchData('POST', 'register.php', data);
    if (response.status === 200) {
      window.location.href = '/index.php';
    }
  } catch (error) {
    const formErrorDiv = form.querySelector('#globalErrMsg');
    formErrorDiv.textContent = error.body.message;
    formErrorDiv.style.display = 'block';
  }
});

const showError = (input, message) => {
  const errorDiv = input.nextElementSibling;
  if (errorDiv && errorDiv.className === 'errMsg') {
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
  }
}