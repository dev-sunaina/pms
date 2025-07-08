# MySQL Database Setup for PMS

This project has been configured to use MySQL (MariaDB) as the database backend instead of SQLite.

## Database Configuration

### Database Details
- **Database Name**: `pms_db`
- **Username**: `pms_user`
- **Password**: `pms_password`
- **Host**: `127.0.0.1`
- **Port**: `3306`
- **Charset**: `utf8mb4`
- **Collation**: `utf8mb4_unicode_ci`

### Installation Steps

1. **Install MariaDB Server**
   ```bash
   apt update
   apt install -y mariadb-server mariadb-client
   service mariadb start
   ```

2. **Install PHP MySQL Extension**
   ```bash
   apt install -y php8.2-mysql
   ```

3. **Create Database and User**
   ```sql
   CREATE DATABASE pms_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   CREATE USER 'pms_user'@'localhost' IDENTIFIED BY 'pms_password';
   GRANT ALL PRIVILEGES ON pms_db.* TO 'pms_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

4. **Update Laravel Configuration**
   - Copy `.env.example` to `.env`
   - Update database configuration in `.env`:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=pms_db
     DB_USERNAME=pms_user
     DB_PASSWORD=pms_password
     ```

5. **Run Migrations and Seeders**
   ```bash
   cd backend
   php artisan config:clear
   php artisan config:cache
   php artisan migrate
   php artisan db:seed
   ```

## Sample Data

The database is seeded with the following sample data:

### Users
- **Admin User** (admin@example.com) - Role: admin
- **Manager User** (manager@example.com) - Role: manager  
- **Developer User** (developer@example.com) - Role: member

All users have the password: `password`

### Teams
- **Development Team** - Main development team for the project

### Projects
- **Project Management System** - Active project

### Tasks
- Setup Authentication System (completed, high priority)
- Create Project Dashboard (in_progress, medium priority)
- Implement Task Management (todo, high priority)

## Verification

To verify the MySQL setup is working:

1. **Test Database Connection**
   ```bash
   mysql -u pms_user -ppms_password pms_db -e "SHOW TABLES;"
   ```

2. **Test API Endpoints**
   ```bash
   # Login
   curl -X POST http://localhost:12000/api/login \
     -H "Content-Type: application/json" \
     -d '{"email":"admin@example.com","password":"password"}'
   
   # Get user profile (replace TOKEN with actual token)
   curl -H "Authorization: Bearer TOKEN" \
     http://localhost:12000/api/user
   ```

3. **Check Sample Data**
   ```sql
   SELECT name, email, role FROM users;
   SELECT name, status FROM projects;
   SELECT title, status, priority FROM tasks;
   ```

## Benefits of MySQL over SQLite

1. **Better Performance** - Optimized for concurrent access
2. **Scalability** - Handles larger datasets more efficiently
3. **Advanced Features** - Full-text search, stored procedures, triggers
4. **Production Ready** - Industry standard for web applications
5. **Better Concurrency** - Multiple users can access simultaneously
6. **Data Integrity** - ACID compliance with better transaction support