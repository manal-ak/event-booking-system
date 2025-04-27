# 🎟️ Online Event Booking System

## 📚 Overview
This project is a simple **Online Event Booking System** built using **PHP**, **SQLite**, **HTML/CSS**, and **JavaScript**.  
It allows:
- **Customers** to register, login, browse events, add tickets to a cart, and reserve tickets.
- **Admins** to manage events, view event details, add new events, edit or delete events, and view customer bookings.

---

## 👤 Customer Features

- **Registration:** Create a new account with unique email.
- **Login:** Login using email and password.
- **Home Page:** Browse available events (responsive grid layout).
- **Event Page:** View event details, select number of tickets, add to cart.
- **Cart Page:** View selected tickets, booking date, confirm reservation.
- **Booking:** Tickets reserved on "Pay at Event" basis (no payment integration).

---

## 🛡️ Admin Features

- **Login:** Login with fixed credentials (admin / admin123).
- **Manage Events:** View all existing events.
- **Add Event:** Create a new event with name, date, location, ticket price, max tickets, image upload, and description.
- **Edit Event:** Modify event information or upload a new image.
- **View Event:** View full event details (read-only).
- **Delete Event:** Delete events (only if no bookings are linked).
- **View Bookings:** See all customer bookings including name, email, booking date, event name, and ticket quantity.

---

## 🛠️ Technologies Used

- PHP (server-side logic)
- SQLite (database)
- HTML5, CSS3 (frontend)
- JavaScript (form validation, interactions)
- VS Code (development)
- Git & GitHub (version control)

---

## 🛡️ Security & Validation

- **Sessions** are used for login authentication.
- **Form validations** on both frontend (JavaScript) and backend (PHP).
- **Input sanitization** using `htmlspecialchars()` to prevent XSS.
- **Restrict Delete:** Events with existing bookings cannot be deleted.
- **Image Upload:** Handled securely and stored in the `images/` folder.

---

## 🔒 Admin Credentials (for Testing)

| Username | Password |
|:---|:---|
| admin | admin123 |

---


## 🚀 How to Run the Project Locally

1. Make sure you have **PHP** installed on your machine.
2. Clone the repository:
    ```bash
    git clone https://github.com/YOUR_USERNAME/YOUR_REPOSITORY.git
    ```
3. Navigate to the project folder:
    ```bash
    cd event-booking
    ```
4. Start PHP server:
    ```bash
    php -S localhost:8000
    ```
5. Open your browser and go to:
    - Customer: [http://localhost:8000/customer/index.php](http://localhost:8000/customer/index.php)
    - Admin: [http://localhost:8000/admin1/admin.php](http://localhost:8000/admin1/admin.php)

✅ Done!

---

## 🙏 Acknowledgment
 
A project for **CSC 457 - Internet Technologies** course at **King Saud University**.

---

