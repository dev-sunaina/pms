# Project Management Tool

A comprehensive project management application built with Laravel backend and React frontend, featuring team collaboration, task management, timesheet tracking, and real-time chat functionality.

## Features

### Core Functionality
- **User Authentication**: Secure login/register with Laravel Sanctum
- **Team Management**: Create and manage teams with role-based access
- **Project Management**: Create, organize, and track projects
- **Task Management**: Assign tasks, set priorities, and track progress
- **Timesheet Tracking**: Log work hours and generate reports
- **Real-time Chat**: Team communication with Pusher integration

### Technical Features
- **RESTful API**: Complete Laravel API with proper authentication
- **Modern Frontend**: React with TypeScript and Tailwind CSS
- **Real-time Updates**: Pusher integration for live notifications
- **Responsive Design**: Mobile-friendly interface
- **Role-based Access**: Admin and member roles with proper permissions

## Tech Stack

### Backend
- **Laravel 11**: PHP framework with modern features
- **Laravel Sanctum**: API authentication
- **SQLite**: Database (easily configurable to other databases)
- **Pusher**: Real-time broadcasting
- **Laravel Policies**: Authorization and permissions

### Frontend
- **React 18**: Modern React with hooks
- **TypeScript**: Type-safe JavaScript
- **Vite**: Fast build tool and dev server
- **Tailwind CSS**: Utility-first CSS framework
- **React Router**: Client-side routing
- **Axios**: HTTP client with interceptors
- **React Hook Form**: Form handling
- **React Hot Toast**: Notifications
- **Heroicons**: Beautiful SVG icons

## Project Structure

```
project_management_tool/
├── backend/                 # Laravel API
│   ├── app/
│   │   ├── Http/Controllers/Api/  # API controllers
│   │   ├── Models/              # Eloquent models
│   │   ├── Policies/            # Authorization policies
│   │   └── Events/              # Broadcasting events
│   ├── database/
│   │   └── migrations/          # Database migrations
│   └── routes/
│       └── api.php             # API routes
├── frontend/                # React application
│   ├── src/
│   │   ├── components/         # React components
│   │   ├── contexts/           # React contexts
│   │   ├── services/           # API services
│   │   ├── types/              # TypeScript interfaces
│   │   └── App.tsx             # Main application
│   └── public/                 # Static assets
└── README.md
```

## Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- npm or yarn

### Backend Setup

1. **Navigate to backend directory**:
   ```bash
   cd backend
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Set up environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database** (SQLite is pre-configured):
   ```bash
   touch database/database.sqlite
   ```

5. **Run migrations**:
   ```bash
   php artisan migrate
   ```

6. **Start the server**:
   ```bash
   php artisan serve --host=0.0.0.0 --port=12000
   ```

### Frontend Setup

1. **Navigate to frontend directory**:
   ```bash
   cd frontend
   ```

2. **Install dependencies**:
   ```bash
   npm install
   ```

3. **Start development server**:
   ```bash
   npm run dev
   ```

The application will be available at:
- Backend API: http://localhost:12000
- Frontend: http://localhost:12005

## API Endpoints

### Authentication
- `POST /api/register` - User registration
- `POST /api/login` - User login
- `POST /api/logout` - User logout
- `GET /api/user` - Get authenticated user

### Teams
- `GET /api/teams` - List user's teams
- `POST /api/teams` - Create new team
- `GET /api/teams/{id}` - Get team details
- `PUT /api/teams/{id}` - Update team
- `DELETE /api/teams/{id}` - Delete team
- `POST /api/teams/{id}/members` - Add team member
- `DELETE /api/teams/{teamId}/members/{userId}` - Remove team member

### Projects
- `GET /api/projects` - List user's projects
- `POST /api/projects` - Create new project
- `GET /api/projects/{id}` - Get project details
- `PUT /api/projects/{id}` - Update project
- `DELETE /api/projects/{id}` - Delete project

### Tasks
- `GET /api/tasks` - List user's tasks
- `POST /api/tasks` - Create new task
- `GET /api/tasks/{id}` - Get task details
- `PUT /api/tasks/{id}` - Update task
- `DELETE /api/tasks/{id}` - Delete task

### Timesheets
- `GET /api/timesheets` - List user's timesheets
- `POST /api/timesheets` - Create timesheet entry
- `GET /api/timesheets/{id}` - Get timesheet details
- `PUT /api/timesheets/{id}` - Update timesheet
- `DELETE /api/timesheets/{id}` - Delete timesheet

### Chat
- `GET /api/teams/{teamId}/messages` - Get team messages
- `POST /api/teams/{teamId}/messages` - Send message

## Database Schema

### Users
- id, name, email, password, role, avatar, timestamps

### Teams
- id, name, description, created_by, timestamps

### Projects
- id, name, description, team_id, status, start_date, end_date, created_by, timestamps

### Tasks
- id, title, description, project_id, assigned_to, status, priority, due_date, created_by, timestamps

### Timesheets
- id, user_id, task_id, hours, description, date, timestamps

### Chat Messages
- id, team_id, user_id, message, timestamps

### Pivot Tables
- team_user (team_id, user_id, role)

## Authentication & Authorization

The application uses Laravel Sanctum for API authentication with the following features:

- **Token-based authentication**: Secure API tokens for frontend communication
- **Role-based access**: Admin and member roles with different permissions
- **Policy-based authorization**: Laravel policies for resource access control
- **CORS configuration**: Proper cross-origin resource sharing setup

## Real-time Features

Real-time functionality is implemented using Pusher:

- **Live chat**: Instant message delivery
- **Notifications**: Real-time updates for task assignments
- **Presence channels**: See who's online (ready for implementation)

## Development

### Running Tests
```bash
# Backend tests
cd backend
php artisan test

# Frontend tests
cd frontend
npm test
```

### Building for Production
```bash
# Frontend build
cd frontend
npm run build

# Backend optimization
cd backend
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Environment Configuration

### Backend (.env)
```env
APP_NAME="Project Management Tool"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:12000

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

SANCTUM_STATEFUL_DOMAINS=localhost:12005,127.0.0.1:12005

PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

BROADCAST_DRIVER=pusher
```

### Frontend
The frontend automatically connects to the backend API at `http://localhost:12000`.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is open-source and available under the MIT License.

## Support

For support and questions, please create an issue in the repository.