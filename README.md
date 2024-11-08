
# iMangerMieux

A web application for maintaining a journal of consumed foods and tracking nutritional intake.

## Table of Contents

1. [File Structure](#file-structure)
2. [Environment Configuration](#environment-configuration)
3. [Importing Data into the Database](#importing-data-into-the-database)
4. [CRUD Functionalities](#crud-functionalities)
5. [Unit Tests](#unit-tests)

---

## File Structure

The project is organized into backend and frontend sections:

### Backend
```
backend/
├── aliments.php                # CRUD API for 'aliment'
├── config.php                  # Database configuration
├── csvinjection.php            # Helper for CSV injection prevention
├── dashboard.php               # Dashboard data aggregation
├── init_db.php                 # Initializes database tables
├── init_pdo.php                # PDO connection setup
├── journal.php                 # CRUD API for 'journal' entries
├── users.php                   # CRUD API for 'users'
└── sql/
    ├── aliments.csv            # Sample data for 'aliment' table
    ├── database.sql            # SQL script to create tables
    └── users.csv               # Sample data for 'users' table
└── tests/
    ├── test_aliments.php       # Unit tests for 'aliments'
    ├── test_journal.php        # Unit tests for 'journal'
    └── test_users.php          # Unit tests for 'users'
```

### Frontend
```
frontend/
├── aliments.php                # Page displaying aliments data
├── config.php                  # Frontend configuration
├── dashboard.php               # User dashboard with metrics
├── footer.php                  # Footer component
├── home.php                    # Home page
├── index.php                   # Main entry dashboard
├── journal.php                 # Page to view/edit food journal
├── login.php                   # Login form
├── logout.php                  # Logout handling
├── navbar.php                  # Navigation bar component
├── privacy.php                 # Privacy policy page
├── profil.php                  # User profile management
├── README.rdm                  # Frontend-specific README
├── signup.php                  # User signup form
├── terms.php                   # Terms and conditions page
└── css/
    ├── homecss.css             # CSS for home page
    ├── login.css               # CSS for login form
    ├── policy.css              # CSS for policies
    ├── signup.css              # CSS for signup form
    └── style.css               # General styling
└── js/
    ├── aliments.js             # JS for aliments page interactions
    ├── dashboard.js            # JS for dashboard interactivity
    ├── journal.js              # JS for journal page interactions
    ├── login.js                # JS for login form validation
    ├── profil.js               # JS for profile page management
    └── signup.js               # JS for signup form validation
```

## Environment Configuration

1. **Clone the repository**: Download the project files from GitHub.
   
   ```bash
   git clone https://github.com/HAOUA-Hamid/iMangerMieux.git
   ```

2. **Server Setup**: Use a PHP-supported server (e.g., Apache) with MySQL for the database.

3. **Configuration Files**: Update `config.php` files in both `backend/` and `frontend/` directories with your database credentials.

## Importing Data into the Database

To initialize and populate the database with CSV files and the SQL script, follow these steps:

### 1. Database Initialization
- Execute `init_db.php` to create tables using `database.sql`.
- Access:
   ```
   http://localhost/imangermieux/backend/init_db.php
   ```
After running, this script will indicate if the table creation was successful.

### 2. Importing Aliments and Users Data
- Run `csvinjection.php` to import data from `aliments.csv` and `users.csv` into the `aliment` and `utilisateur` tables.
- Access:
   ```
   http://localhost/imangermieux/backend/csvinjection.php
   ```
This script also sanitizes risky characters to prevent CSV injection and inserts the data into the respective tables.

## CRUD Functionalities

### Backend API Endpoints

- **Aliments API** (`backend/aliments.php`)
  - `GET /aliments`: Fetch all or specific aliment by ID.
  - `POST /aliments`: Create new aliment entry.
  - `PUT /aliments/{ID_ALIMENT}`: Update specific aliment.
  - `DELETE /aliments/{ID_ALIMENT}`: Delete specific aliment.

- **Journal API** (`backend/journal.php`)
  - `GET /journal`: Retrieve all journal entries or specific by ID.
  - `POST /journal`: Create new journal entry.
  - `PUT /journal/{ID_JOURNAL}`: Update specific journal entry.
  - `DELETE /journal/{ID_JOURNAL}`: Delete specific journal entry.

- **Users API** (`backend/users.php`)
  - `GET /users`: Retrieve all users or specific user by ID.
  - `POST /users`: Register a new user.
  - `PUT /users/{ID_UTILISATEUR}`: Update user information.
  - `DELETE /users/{ID_UTILISATEUR}`: Delete user.

### Frontend Pages

- **Home** (`frontend/home.php`): Provides an introduction to the app.
- **Dashboard** (`frontend/dashboard.php`): Displays nutritional data.
- **Journal** (`frontend/journal.php`): Manages user’s daily food intake.
- **Profile** (`frontend/profil.php`): Edit user-specific data.
- **Aliments** (`frontend/aliments.php`): View and manage aliments data.

## Unit Tests

Backend unit tests are included in `backend/tests/`, covering CRUD functionalities for:
- **Aliments**: Validates `aliments.php` endpoints.
- **Journal**: Verifies `journal.php` endpoints.
- **Users**: Tests `users.php` endpoints.

---

This README provides an overview of iMangerMieux’s structure, setup instructions, and core functionalities for contributors and developers.
