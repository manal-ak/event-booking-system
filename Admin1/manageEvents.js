// manageEvents.js (Optional)
function confirmDelete(eventId) {
    if (confirm('Are you sure you want to delete this event?')) {
        window.location.href = 'deleteEvent.php?id=' + eventId;
    }
}