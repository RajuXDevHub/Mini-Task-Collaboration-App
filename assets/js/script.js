document.addEventListener('DOMContentLoaded', function() {
    // Add Task
    document.getElementById('addTaskForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('includes/add_task.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error adding task');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the task');
        });
    });

    // Edit Task Modal Setup
    document.querySelectorAll('.edit-task').forEach(button => {
        button.addEventListener('click', function() {
            const taskId = this.getAttribute('data-id');
            const title = this.getAttribute('data-title');
            const deadline = this.getAttribute('data-deadline');
            const priority = this.getAttribute('data-priority');
            const status = this.getAttribute('data-status');
            
            document.getElementById('edit_task_id').value = taskId;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_deadline').value = deadline;
            document.getElementById('edit_priority').value = priority;
            document.getElementById('edit_status').value = status;
            
            const editModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
            editModal.show();
        });
    });

    // Update Task
    document.getElementById('editTaskForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('includes/update_task.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error updating task');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the task');
        });
    });

    // Delete Task
    document.querySelectorAll('.delete-task').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this task?')) {
                const taskId = this.getAttribute('data-id');
                
                fetch('includes/delete_task.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${taskId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Error deleting task');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the task');
                });
            }
        });
    });
});