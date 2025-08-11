# LBDAIRY - Dairy Management System

A comprehensive Laravel-based dairy management system designed to help farmers, administrators, and super administrators manage dairy operations efficiently.

## Features

### User Management
- **Farmer Role**: Manage farms, livestock, production records, sales, and expenses
- **Admin Role**: Oversee multiple farms, manage farmers, and handle issues
- **Super Admin Role**: System-wide management, user administration, and audit logs

### Core Functionality
- **Farm Management**: Register and manage multiple farms
- **Livestock Tracking**: Comprehensive livestock records with health monitoring
- **Production Records**: Track daily milk production and quality metrics
- **Sales Management**: Record and track milk sales with customer information
- **Expense Tracking**: Monitor farm expenses and costs
- **Issue Management**: Report and track farm-related issues
- **Audit Logging**: Complete system activity tracking

## System Requirements

- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer
- Node.js and NPM (for asset compilation)

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd LBDairy
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   - Copy `.env.example` to `.env`
   - Update database configuration:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=lbdairy_db
     DB_USERNAME=root
     DB_PASSWORD=
     ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Create the database**
   ```bash
   # Create MySQL database
   mysql -u root -p
   CREATE DATABASE lbdairy_db;
   ```

7. **Run database migrations**
   ```bash
   php artisan migrate
   ```

8. **Seed the database with sample data**
   ```bash
   php artisan db:seed
   ```

9. **Compile assets**
   ```bash
   npm run build
   ```

10. **Start the development server**
    ```bash
    php artisan serve
    ```

## Default Login Credentials

### Super Admin
- **Username**: superadmin
- **Password**: password123
- **Email**: superadmin@lbdairy.com

### Admin
- **Username**: admin
- **Password**: password123
- **Email**: admin@lbdairy.com

### Farmers
- **Username**: johnfarmer
- **Password**: password123
- **Email**: john@lbdairy.com

- **Username**: janefarmer
- **Password**: password123
- **Email**: jane@lbdairy.com

## Database Structure

The system includes the following main tables:
- `users` - User accounts with role-based access
- `farms` - Farm information and ownership
- `livestock` - Individual animal records
- `production_records` - Daily milk production data
- `sales` - Sales transactions and customer data
- `expenses` - Farm expense tracking
- `issues` - Problem reporting and management
- `audit_logs` - System activity logging

## API Endpoints

The system provides RESTful API endpoints for:
- User authentication and management
- Farm operations
- Livestock management
- Production tracking
- Sales and expense management
- Issue reporting and resolution

## Security Features

- Role-based access control (RBAC)
- CSRF protection
- Input validation and sanitization
- Audit logging for all system activities
- Secure password hashing
- Session management

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support and questions, please contact the development team or create an issue in the repository.

## Changelog

### Version 1.0.0
- Initial release
- Basic dairy management functionality
- User role management
- Farm and livestock tracking
- Production and sales management
- Issue reporting system
- Audit logging
