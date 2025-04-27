// deleteEvent.js (Optional - for enhanced confirmation)
document.addEventListener('DOMContentLoaded', function () {
    const deleteButton = document.getElementById('deleteButton');
    if (deleteButton) {
        deleteButton.addEventListener('click', function (event) {
            event.preventDefault(); // Stop default form submission

            //  Replace with a nicer modal/dialog if you want
            if (confirm('Are you sure you want to delete this event?')) {
                window.location.href = deleteButton.href; //  Or submit a form via JS
            }
        });
    }
});