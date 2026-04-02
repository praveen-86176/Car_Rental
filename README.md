# Car Rental Agency Web Application

A full-stack web application built for the Software Development Engineering (Web) assignment.

## Tech Stack
- **Frontend**: React.js (Vite), React Router DOM, Axios, Bootstrap 5 UI
- **Backend**: Core PHP REST API
- **Database**: MySQL

## Project Architecture
The project is strictly decoupled into two core components:
1. `api/` - The PHP backend which exposes raw JSON endpoints over `localhost:8000` with strict CORS validations.
2. `frontend/` - The ReactJS application running on `localhost:5173` rendering the dynamic DOM components.

## Target User Roles
- **Guest / Visitor**: Can view all public cars available for rent but must redirect to Login when attempting to book.
- **Customer**: Can securely log in, verify currently booked cars (green badge overlay), and book new cars via date pickers.
- **Car Rental Agency**: Can register agency details, inject new cars into the database, edit vehicle capacity/limits, and view an aggregated table mapping of all customers who have booked their specific vehicles.

## Setup Instructions
### 1. Database Configuration
1. Start your local Apache/MySQL server (XAMPP/Homebrew).
2. Inside `api/config/db.php`, enter your MySQL root password.
3. Access `http://localhost:8000/api/init_db.php` in your browser. This will automatically execute and generate the `car_rental` schema alongside the 4 SQL tables (`customers`, `agencies`, `cars`, `bookings`).

### 2. Booting the Application
You will need two separate terminal windows to emulate the live server routing.

**Terminal 1 (PHP API Server):**
```bash
cd api/
php -S localhost:8000
```

**Terminal 2 (React UI Server):**
```bash
cd frontend/
npm install
npm run dev
```

Navigate to `http://localhost:5173` to launch the application.

## Security Practices
- PDO Prepared Statements mapped to all MySQL inputs blocking SQL Injection.
- Hashes and salts via `password_hash()` and `password_verify()`.
- Route guards and authenticated React layout hooks parsing backend session states.
- Client-side validation hooks mapped natively through HTML/React components mapping constraints dynamically prior to POST requests.
