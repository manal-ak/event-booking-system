// addEvent.js
document.addEventListener('DOMContentLoaded', function () {
    const addEventForm = document.getElementById('addEventForm');
    const eventNameError = document.getElementById('eventNameError');
    const eventDateError = document.getElementById('eventDateError');
    const locationError = document.getElementById('locationError');
    const ticketPriceError = document.getElementById('ticketPriceError');
    const maxTicketsError = document.getElementById('maxTicketsError');

    addEventForm.addEventListener('submit', function (event) {
        let eventName = document.getElementById('eventName').value;
        let eventDate = document.getElementById('eventDate').value;
        let location = document.getElementById('location').value;
        let ticketPrice = document.getElementById('ticketPrice').value;
        let maxTickets = document.getElementById('maxTickets').value;
        let hasError = false;

        if (!eventName) {
            eventNameError.textContent = 'Event Name is required.';
            hasError = true;
        } else {
            eventNameError.textContent = '';
        }

        if (!eventDate) {
            eventDateError.textContent = 'Event Date is required.';
            hasError = true;
        } else {
            eventDateError.textContent = '';
        }

        if (!location) {
            locationError.textContent = 'Location is required.';
            hasError = true;
        } else {
            locationError.textContent = '';
        }

        if (!ticketPrice) {
            ticketPriceError.textContent = 'Ticket Price is required.';
            hasError = true;
        } else {
            ticketPriceError.textContent = '';
        }

        if (!maxTickets) {
            maxTicketsError.textContent = 'Maximum Tickets is required.';
            hasError = true;
        } else {
            maxTicketsError.textContent = '';
        }

        if (hasError) {
            event.preventDefault();
        }
    });
});