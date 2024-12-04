document.querySelectorAll('.role-select').forEach(select => {
  select.addEventListener('change', async (e) => {
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

document.querySelectorAll('.delete-user-btn').forEach(button => {
  button.addEventListener('click', async (e) => {
    const userId = e.target.dataset.userId;
    const csrf_token = document.querySelector('input[name="csrf_token"]').value;

    if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
      return;
    }

    try {
      const response = await fetch('users.php', {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          userId: userId,
          csrf_token: csrf_token
        })
      });

      const data = await response.json();

      if (response.ok) {
        const userCard = button.closest('.user-card');
        userCard.remove();
      } else {
        alert(data.message || 'Error deleting user');
      }
    } catch (error) {
      console.error('Error:', error);
      alert('Network error while deleting user');
    }
  });
});