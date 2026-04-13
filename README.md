# VAS - Visitor & Attendance System

VAS is a modern web-based application designed to simplify visitor management and attendance tracking in organizations. Built with Laravel 10 and a clean, scalable architecture, VAS helps businesses monitor user activity, manage visitor records, and track attendance efficiently in one integrated system.

## Features

- **Visitor Management** - Register, check-in/check-out visitors with detailed information
- **Attendance Tracking** - Record and monitor employee attendance in real-time
- **User & Role Management** - Multi-level access control (Admin, Editor, Viewer, Guest)
- **Authentication & Access Control** - Secure login with role-based permissions
- **Dashboard Overview** - Real-time statistics and recent activities
- **Responsive Design** - Mobile-friendly interface with Tailwind CSS

## Access Levels

| Level | Role | Permissions |
|-------|------|-------------|
| 1 | Admin | Full access to all features (users, visitors, attendance) |
| 2 | Editor | Manage visitors and attendance, cannot manage users |
| 3 | Staf | View-only access to assigned data |
| 4 | Attendee | Limited access, cannot register visitors |

## Tech Stack

- **Backend**: Laravel 10 (PHP 8.2)
- **Web Server**: Nginx Alpine
- **Database**: MySQL 8.0
- **Cache/Queue**: Redis
- **Container**: Docker & Docker Compose
- **Database UI**: phpMyAdmin
- **Frontend**: Blade Templates, Tailwind CSS, Font Awesome

## System Architecture

```bash
VAS uses a multi-container Docker setup:

┌─────────────────────────────────────────────────────────┐
│ Docker Network │
│ ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌──────────┐ │
│ │ App │ │ Web │ │ DB │ │ Redis │ │
│ │ (PHP-FPM)│ │ (Nginx) │ │ (MySQL) │ │ (Cache) │ │
│ └────┬────┘ └────┬────┘ └────┬────┘ └────┬─────┘ │
│ │ │ │ │ │
│ └────────────┼────────────┼────────────┘ │
│ │ │ │
│ ┌─────┴─────┐ ┌────┴─────┐ │
│ │phpMyAdmin │ │ Volume │ │
│ │ (UI) │ │ (db_data)│ │
│ └───────────┘ └──────────┘ │
└─────────────────────────────────────────────────────────┘
```

- **app**: PHP 8.2-FPM container running Laravel application
- **web**: Nginx Alpine web server (port 8080)
- **db**: MySQL 8.0 database server (port 3306)
- **redis**: Redis for caching and session management
- **phpmyadmin**: Database management UI (port 8081)

## Installation

### Prerequisites

- Docker & Docker Compose
- Git

### Project Structure

```bash
visitor-attendance-system/
├── src/                    # Laravel application source code
│   ├── app/               # Application core
│   ├── config/            # Configuration files
│   ├── database/          # Migrations and seeders
│   ├── resources/         # Views, assets, language files
│   ├── routes/            # Web and API routes
│   └── storage/           # Logs, cache, sessions
├── nginx/
│   └── default.conf       # Nginx server configuration
├── php.ini                # PHP configuration overrides
├── Dockerfile             # PHP-FPM container definition
├── docker-compose.yml     # Docker services orchestration
└── README.md             # Project documentation
```

### Quick Start

1. **Clone the repository**
```bash
git clone https://github.com/gylangsatria/Visitor-Attendance-System.git
cd Visitor-Attendance-System

```

2. **Build and run containers**
```bash
docker compose build --no-cache
cp .env.example src/.env
docker compose up -d
```

3. **Install PHP dependencies**
```bash
docker exec -it vas-app composer install
```

4. **Configure environment**
```bash
docker exec -it vas-app php artisan key:generate
```

5. **Run database migrations**
```bash
docker exec -it vas-app php artisan migrate
docker exec -it vas-app php artisan db:seed
```

6. **Set permissions (optional)**
```bash
docker exec -it vas-app chmod -R 775 storage bootstrap/cache
```

## Access URLs & Credentials

| Service | URL | Credentials |
|---------|-----|-------------|
| **Application** | http://localhost:8080 | Email: `admin@vas.com`<br>Password: `admin123` |
| **phpMyAdmin** | http://localhost:8081 | Server: `db`<br>Username: `vas_user`<br>Password: `dbpassword` |

### Default User Accounts

| Role | Access Level | Email | Password |
|------|-------------|-------|----------|
| Admin | Level 1 | admin@vas.com | admin123 |
| Editor | Level 2 | staff@vas.com | staff123 |
| Viewer | Level 3 | user@vas.com | user123 |

### Database Credentials

| Parameter | Value |
|-----------|-------|
| Host | `db` (inside container) |
| Port | `3306` |
| Database | `vas_db` |
| Username | `vas_user` |
| Password | `dbpassword` |




### Bug
- Permission failed. 

### Troubleshoot
1. Masuk ke container
```bash
docker exec -it vas-app bash
```

2. Fix permission langsung
```bash
# Buat semua folder
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permission ekstrim
chmod -R 777 storage
chmod -R 777 bootstrap/cache

# Hapus cache lama
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/views/*.php
rm -rf storage/framework/cache/*.php

# Generate fresh cache
php artisan config:cache
php artisan view:cache
php artisan route:cache

# Test
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```