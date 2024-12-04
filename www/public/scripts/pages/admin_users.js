document.querySelectorAll('.role-select').forEach(select => {
  select.addEventListener('change', async (e) => {
    console.log('change');
    const select = e.target;
    const userId = select.dataset.userId;
    const newRole = select.value;
    const originalValue = select.getAttribute('data-original-value');
    const csrf_token = document.querySelector('input[name="csrf_token"]').value;

    try {
      const response = await fetch('users.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          userId: userId,
          role: newRole,
          csrf_token: csrf_token
        })
      });

      const data = await response.json();

      if (response.ok) {
        select.setAttribute('data-original-value', newRole);
      } else {
        alert(data.message || 'Error updating user role');
        select.value = originalValue;
      }
    } catch (error) {
      console.error('Error:', error);
      alert('Network error while updating user role');
      select.value = originalValue;
    }
  });

  select.setAttribute('data-original-value', select.value);
});