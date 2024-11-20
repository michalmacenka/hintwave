import fetchData from "../common/fetchData.js";

document.querySelector('form').addEventListener('submit', async (event) => {
  event.preventDefault();

  // Clear previous errors
  document.querySelectorAll('.errMsg').forEach(div => {
    div.style.display = 'none';
    div.textContent = '';
  });

  const errors = [];
  const form = event.target;
  const isEdit = form.querySelector('input[name="hint_id"]') !== null;

  // Validate title (1-256 characters)
  const title = form.title.value.trim();
  if (!title || title.length < 1 || title.length > 256) {
    errors.push({
      field: form.title,
      message: 'Title must be between 1 and 256 characters long'
    });
  }

  // Validate description (1-1024 characters)
  const description = form.description.value.trim();
  if (!description || description.length < 1 || description.length > 1024) {
    errors.push({
      field: form.description,
      message: 'Description must be between 1 and 1024 characters long'
    });
  }

  // Validate category (must be integer)
  const category = parseInt(form.category.value);
  if (isNaN(category) || category <= 0) {
    errors.push({
      field: form.category,
      message: 'Please select a valid category'
    });
  }

  // Validate reasons (2-12 reasons, each 3-64 characters)
  const reasonInputs = form.querySelectorAll('input[name="reasons[]"]');
  const reasons = Array.from(reasonInputs)
    .map(input => input.value.trim())
    .filter(value => value.length > 0);

  const reasonsContainer = document.getElementById('reasons-container');
  
  if (reasons.length < 2 || reasons.length > 12) {
    errors.push({
      field: reasonsContainer,
      message: 'You must provide between 2 and 12 reasons'
    });
  }

  const invalidReason = reasons.some(reason => reason.length < 3 || reason.length > 64);
  if (invalidReason) {
    errors.push({
      field: reasonsContainer,
      message: 'Each reason must be between 3 and 64 characters'
    });
  }

  if (errors.length > 0) {
    errors.forEach(error => {
      showError(error.field, error.message);
    });
    return;
  }

  try {
    const data = {
      title,
      description,
      category,
      reasons,
      csrf_token: form.querySelector('input[name="csrf_token"]').value
    };

    if (isEdit) {
      data.hint_id = form.querySelector('input[name="hint_id"]').value;
    }

    const response = await fetchData('POST', 'add.php', data);

    if (response.status === 200) {
      window.location.href = '/hints.php';
    }
  } catch (error) {
    const formErrorDiv = document.getElementById('globalErrMsg');
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

window.removeReason = function(button) {
  const reasonInput = button.closest('.reason-input');
  const totalReasons = document.querySelectorAll('.reason-input').length;
  
  if (totalReasons > 2) {
    reasonInput.remove();
    if (totalReasons <= 12) {
      document.getElementById('add-reason-button').style.display = 'flex';
    }
  }
}

const addReasonButton = document.getElementById('add-reason-button');
addReasonButton.addEventListener('click', function() {
  const reasonsContainer = document.getElementById('reasons-container');
  const currentReasons = reasonsContainer.querySelectorAll('.reason-input').length;
  
  if (currentReasons >= 12) {
    return;
  }

  const reasonDiv = document.createElement('div');
  reasonDiv.className = 'reason-input';

  const reasonInput = document.createElement('input');
  reasonInput.type = 'text';
  reasonInput.name = 'reasons[]';
  reasonInput.required = true;
  reasonInput.placeholder = `Reason ${currentReasons + 1}`;

  const removeButton = document.createElement('button');
  removeButton.type = 'button';
  removeButton.className = 'remove-reason-button';
  removeButton.onclick = function() { removeReason(this); };
  removeButton.innerHTML = '<i class="bx bx-x"></i>';

  reasonDiv.appendChild(reasonInput);
  reasonDiv.appendChild(removeButton);

  reasonsContainer.appendChild(reasonDiv);

  if (currentReasons + 1 >= 12) {
    addReasonButton.style.display = 'none';
  }
});

document.addEventListener('DOMContentLoaded', () => {
  const reasonCount = document.querySelectorAll('.reason-input').length;
  if (reasonCount >= 12) {
    addReasonButton.style.display = 'none';
  }
});