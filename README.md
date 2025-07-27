# Vue.js & Laravel Dockerized Authentication App

This project demonstrates a multi-step user registration and authentication system built with Vue.js (frontend) and a Laravel API (backend), all orchestrated using Docker Compose for a streamlined development environment.

## Features

* **User Authentication:** Secure login and logout functionality.

* **Two-Step Registration Form:**

  * **Step 1:** Collects personal information (First Name, Middle Name (optional), Surname, Email, Mobile Number).

  * **Step 2:** Collects account credentials (Username, Password, Password Confirmation).

* **Client-Side Validation:** Real-time validation for form fields.

* **Email Availability Check:** Asynchronous check during registration to ensure the email is not already in use (requires backend endpoint).

* **Password Requirements:** Enforces minimum length (8 characters) and at least one number.

* **Success Modal:** Displays a confirmation popup after successful registration before redirecting to login.

* **Vue Router:** Handles client-side routing for seamless navigation.

* **Route Protection:** Navigation guards prevent unauthenticated users from accessing protected routes (e.g., Dashboard) and authenticated users from accessing login/register pages.

* **API Integration:** Communicates with a Laravel backend for registration and login processes.

* **Dockerized Environment:** Uses Docker Compose to manage isolated development environments for both frontend and backend services.

* **Centralized Styling:** All component-specific and global styles are managed in a single `auth.css` file for consistency.

## Technologies Used

* **Frontend:**

  * Vue.js 3

  * Vue Router 4

  * Vite (for fast development)

  * HTML, CSS (Custom styling)

* **Backend:**

  * Laravel (PHP Framework) - *Assumed for API endpoints*

* **Containerization:**

  * Docker

  * Docker Compose

## Installation

```bash
git clone https://github.com/battcor/registration.git .
cd registration/
cp api/.env.example api/.env
ln -s api/.env .env
docker compose up --build
docker compose exec web composer install
docker compose exec web php artisan migrate --seed
```

Go to http://localhost:8080/