import fetchData from "../common/fetchData.js";

document.querySelector('form').addEventListener('submit', async (event) => {
  event.preventDefault();

  document.querySelectorAll('.errMsg').forEach(div => {
    div.style.display = 'none';
    div.textContent = '';
  });

  const form = event.target;

  try {
    const data = {
      username: form.username.value,
      password: form.password.value,
      csrf_token: form.querySelector('input[name="csrf_token"]').value
    };
    const response = await fetchData('POST', 'login.php', data);

    if (response.status === 200) {
      window.location.href = '/~macenmic/index.php';
    }
  } catch (error) {
    console.log(error);
    const formErrorDiv = form.querySelector('#globalErrMsg');
    formErrorDiv.textContent = error.body.message;
    formErrorDiv.style.display = 'block';
  }
});
