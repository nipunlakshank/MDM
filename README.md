# Master Data Management System

## Features

- **User Authentication**
  - User registration, login, and logout
  - Only authenticated users can access the system

* **Role-Based Access Control**
  * Admin and User roles

- **Brands Management**
  - Create, view (with pagination), update, and delete brands
  - Confirmation alerts for delete actions

* **Categories Management**
  * Create, view (with pagination), update, and delete categories
  * Confirmation alerts for delete actions

- **Items Management**
  - Create, view (with pagination), update, and delete items
  - File attachment support for items
  - Confirmation alerts for delete actions

* **Validation**
  * Required fields and input validation with error messages

- **Search & Filters**
  - Find items by code, name, or status

* **Export Options**
  * Export items to **Excel, CSV, and PDF**


## Setup Instructions

### Prerequisites
- PHP 8.4 or higher
- Composer
- MySQL or MariaDB
- Node.js and npm (for frontend dependencies)

### Run Application Locally

#### Option 1 (Using automated script)

- Clone the repository
```bash
git clone https://github.com/nipunlakshank/mdm.git
cd mdm
```

- Run the setup script (for **windows users**, run in **git bash**)
```bash
./setup.sh
```

- Seed the database with sample data
```bash
php artisan db:seed
```

#### Option 2 (Manual Setup)

- Clone the repository
```bash
git clone https://github.com/nipunlakshank/mdm.git
cd mdm
```

- Configure git hooks
```bash
git config core.hooksPath .git-hooks
```

- Install PHP dependencies
```bash
composer install
```

- Install Node.js dependencies
```bash
npm install
```

- Create a `.env` file from the `.env.example` file
```bash
cp .env.example .env
```

- Update the `.env` file with your database credentials
```env
DB_CONNECTION=mysql
DB_USERNAME=root
DB_PASSWORD=your_password
DB_DATABASE=mdm_db
```

- Generate the application key
```bash
php artisan key:generate
```

- Run the database migrations
```bash
php artisan migrate
```

- Seed the database with initial data
```bash
php artisan db:seed
```

- If you are using Laravel Herd, you should link the project to the Herd environment
```bash
herd link
```

- Start the local development server
```bash
composer dev
```

- Access the application in your web browser at `http://localhost:8000` or `http://[project-directory-name].test` if using Herd.

