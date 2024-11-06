import fetchData from "../common/fetchData.js";

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.delete-hint-btn').forEach(button => {
        button.addEventListener('click', async (e) => {
            const hintId = e.target.dataset.hintId;
            
            if (!confirm('Are you sure you want to delete this hint?')) {
                return;
            }

            try {
                const response = await fetchData('DELETE', 'admin.php', {
                    hintId
                });

                if (response.status === 200) {
                    const hintElement = document.querySelector(`.hint[data-hint-id="${hintId}"]`);
                    if (hintElement) {
                        hintElement.remove();
                    }
                }
            } catch (error) {
                const errorMessage = error?.body?.message || 'Failed to delete hint';
                alert(errorMessage);
            }
        });
    });
});