# NormNinja - Learning Management System

![NormNinja](public/images/logo.png)

A comprehensive, modern Learning Management System (LMS) built with Laravel 12, designed to facilitate seamless interaction between administrators, teachers, and students in an educational environment.

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [System Requirements](#system-requirements)
- [Installation](#installation)
- [User Roles](#user-roles)
- [Key Features](#key-features)
- [Project Structure](#project-structure)
- [Database Schema](#database-schema)
- [How It Works](#how-it-works)
- [Security](#security)
- [Testing](#testing)
- [Deployment](#deployment)
- [Contributing](#contributing)
- [Credits](#credits)

---

## Overview

**NormNinja** is a full-featured Learning Management System that provides a complete educational platform for schools and educational institutions. The system supports three distinct user roles (Admin, Teacher, Student) with specialized dashboards and functionalities tailored to each role's needs.

### What Does NormNinja Do?

- **For Administrators**: Complete control over user management, system monitoring, and institutional oversight
- **For Teachers**: Tools for creating educational content, quizzes, games, tracking student performance, and identifying students who need support
- **For Students**: Access to learning materials, interactive quizzes, educational games, forums, and personal progress tracking

---

## Features

### Core Capabilities

- **User Management**: Three-tier role-based access control (Admin, Teacher, Student)
- **Learning Materials**: Upload and manage educational content (PDFs, documents, presentations, videos)
- **Interactive Quizzes**: Create quizzes with multiple question types and automatic grading
- **Educational Games**: Flashcards, matching games, and gamified quizzes
- **Discussion Forums**: Create and manage topic-based discussion boards
- **Performance Analytics**: Track student progress and identify those needing support
- **Calendar & Reminders**: Personal calendar events and task reminders for students
- **Profile Management**: User profiles with customizable information and profile pictures
- **Responsive Design**: Mobile-friendly interface that works on all devices

---

## Technologies Used

### Backend
- **Framework**: Laravel 12 (PHP 8.2+)
- **Database**: MySQL/PostgreSQL
- **Authentication**: Custom authentication system with bcrypt password hashing
- **Storage**: Laravel Storage with symbolic linking

### Frontend
- **CSS Framework**: Tailwind CSS
- **Icons**: Font Awesome 6
- **JavaScript**: Vanilla JS with fetch API
- **Templating**: Blade Templates

### Development Tools
- **Package Manager**: Composer (PHP), NPM (JavaScript)
- **Build Tools**: Vite
- **Code Quality**: Laravel Pint
- **Testing**: PHPUnit

---

## System Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18.x
- NPM or Yarn
- MySQL >= 8.0 or PostgreSQL >= 13
- Apache/Nginx web server
- 512MB RAM minimum (2GB recommended)
- 1GB free disk space

---

## Installation

### Quick Setup

```bash
# 1. Clone the repository
git clone https://github.com/yourusername/normninja.git
cd normninja

# 2. Install dependencies
composer install
npm install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env file
# Edit DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 5. Run migrations
php artisan migrate

# 6. Create storage link
php artisan storage:link

# 7. Build assets
npm run build

# 8. Start development server
php artisan serve
```

### Create Admin User

```bash
php artisan tinker
```

Then in tinker:
```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@normninja.com',
    'password' => bcrypt('admin123'),
    'role' => 'admin',
    'is_active' => true
]);
```

Access the application at: `http://localhost:8000`

For detailed deployment instructions, see [DEPLOYMENT.md](DEPLOYMENT.md)

---

## User Roles

### 1. Administrator

**Dashboard Features:**
- View system-wide statistics (total students, teachers, active users)
- Quick access to user management functions

**Capabilities:**
- Create, edit, and delete student accounts
- Create, edit, and delete teacher accounts
- Activate/deactivate user accounts
- Manage user profiles and information
- View all system activities
- Full system access and control

### 2. Teacher

**Dashboard Features:**
- View content statistics (materials, quizzes, games, forums)
- Monitor recent student quiz attempts
- Quick action buttons for content creation

**Capabilities:**

**Content Creation:**
- Upload learning materials (PDF, DOC, PPT, videos up to 50MB)
- Create quizzes with multiple question types
- Design educational games (flashcards, matching, gamified quizzes)
- Manage discussion forums

**Assessment:**
- Create quizzes with multiple choice, true/false, and short answer questions
- Set passing scores and time limits
- Automatic grading system
- View detailed quiz results and statistics

**Student Monitoring:**
- Track individual student performance
- View quiz attempt history
- Monitor game engagement
- Identify students needing support based on:
  - Low quiz performance (below 60%)
  - Low completion rates (below 50%)
  - No engagement with materials
  - Declining performance trends

### 3. Student

**Dashboard Features:**
- Personal statistics (completed quizzes, average score, games played)
- Recent activity feed
- Quick access to latest materials, quizzes, and games

**Capabilities:**
- Access all published learning materials
- Take quizzes and view results
- Play educational games
- Participate in forum discussions
- Manage personal calendar events
- Set task reminders
- Track personal progress and performance
- View detailed score history

---

## Key Features

### Learning Materials Management

Teachers can upload various file types:
- **Documents**: PDF, DOC, DOCX
- **Presentations**: PPT, PPTX
- **Videos**: MP4, AVI, MOV
- **Maximum file size**: 50MB

Features:
- Categorization by subject and grade level
- Publish/unpublish controls
- Download functionality for students
- Upload date tracking
- Description and metadata

### Quiz System

**Question Types:**

1. **Multiple Choice**
   - Support for multiple options
   - Single correct answer selection
   - Customizable point values

2. **True/False**
   - Binary choice questions
   - Quick answer format
   - Automatic grading

3. **Short Answer**
   - Text-based responses
   - Case-insensitive matching
   - Flexible answer validation

**Quiz Features:**
- Time limits (optional)
- Passing score thresholds
- Availability date ranges
- Unlimited retakes (configurable)
- Immediate results
- Detailed feedback
- Performance analytics

### Educational Games

**Game Types:**

1. **Flashcards**
   - Digital flash cards with flip animation
   - Term and definition pairs
   - Self-paced learning
   - Progress tracking

2. **Matching Game**
   - Match terms with definitions
   - Drag-and-drop interface
   - Timed challenges
   - Score calculation

3. **Gamified Quiz**
   - Quiz format with game mechanics
   - Points and rewards system
   - Leaderboard integration
   - Competitive learning

### Discussion Forums

- Topic-based discussion boards
- Threaded conversations
- Teacher moderation tools
- Student engagement tracking
- Reply and edit functionality
- Subject categorization

### Student Performance Analytics

**Automatic Support Identification:**

The system identifies students needing support based on:
- Quiz scores below 60% average
- Completion rate below 50%
- Zero engagement with materials
- Performance decline of 10% or more

**Analytics Dashboard:**
- Individual student performance views
- Quiz attempt history with scores
- Game engagement metrics
- Progress over time visualization
- Support status indicators

### Calendar & Reminders

Students can:
- Create personal calendar events
- Set task reminders
- View upcoming deadlines
- Organize study schedules
- Track due dates for quizzes and assignments

---

## Project Structure

```
normninja/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php       # Admin functionality
│   │   │   ├── TeacherController.php     # Teacher features
│   │   │   ├── StudentController.php     # Student features
│   │   │   ├── QuizController.php        # Quiz management
│   │   │   ├── GameController.php        # Game functionality
│   │   │   ├── ForumController.php       # Forum features
│   │   │   └── LearningMaterialController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php        # Role-based access control
│   ├── Models/
│   │   ├── User.php                      # User model (3 roles)
│   │   ├── Quiz.php
│   │   ├── QuizQuestion.php
│   │   ├── QuizAttempt.php
│   │   ├── Game.php
│   │   ├── GameAttempt.php
│   │   ├── LearningMaterial.php
│   │   ├── Forum.php
│   │   ├── ForumPost.php
│   │   ├── CalendarEvent.php
│   │   └── Reminder.php
│   └── Policies/                         # Authorization policies
│       ├── QuizPolicy.php
│       ├── GamePolicy.php
│       ├── ForumPolicy.php
│       └── LearningMaterialPolicy.php
├── database/
│   ├── migrations/                       # Database structure
│   └── seeders/                          # Sample data
├── resources/
│   └── views/
│       ├── admin/                        # Admin views
│       ├── teacher/                      # Teacher views
│       ├── student/                      # Student views
│       ├── quizzes/                      # Quiz views
│       ├── games/                        # Game views
│       ├── forums/                       # Forum views
│       └── learning-materials/           # Material views
├── routes/
│   └── web.php                           # Application routes
└── storage/
    └── app/public/                       # File uploads
```

---

## Database Schema

### Key Tables

**users**
- Stores all user types (admin, teacher, student)
- Includes profile information, role, and active status
- Soft deletes enabled for data recovery

**learning_materials**
- Stores uploaded educational content
- File path, type, and metadata
- Subject and grade level categorization
- Published status control

**quizzes**
- Quiz metadata (title, description, subject)
- Duration, passing score, availability dates
- Links to questions

**quiz_questions**
- Question text and type
- Options stored as JSON
- Correct answers and point values

**quiz_attempts**
- Student quiz submissions
- Answers stored as JSON
- Score, percentage, and completion status

**games**
- Game metadata and type
- Game data stored as JSON
- Subject categorization

**game_attempts**
- Student game plays
- Score and time tracking

**forums**
- Discussion board topics
- Subject categorization
- Active status

**forum_posts**
- Discussion posts and replies
- Parent-child relationship for threading
- User attribution

**calendar_events**
- Student personal calendar
- Event title, description, date

**reminders**
- Student task reminders
- Due dates and completion status

---

## How It Works

### User Journey: Teacher Creates a Quiz

1. Teacher logs in and navigates to dashboard
2. Clicks "Create Quiz" from quick actions
3. Enters quiz details:
   - Title and description
   - Subject and grade level
   - Duration and passing score
   - Availability dates
4. Adds questions:
   - Chooses question type (multiple choice, true/false, short answer)
   - Enters question text
   - Provides options/answers
   - Sets point values
5. Reviews and publishes quiz
6. Students can now see and take the quiz

### User Journey: Student Takes a Quiz

1. Student logs in to dashboard
2. Views available quizzes
3. Clicks on quiz to view details
4. Starts quiz attempt
5. Timer begins (if duration is set)
6. Answers questions in order
7. Submits completed quiz
8. Views immediate results:
   - Score and percentage
   - Pass/fail status
   - Correct answer review
9. Performance recorded in system
10. Teacher can view results and analytics

### User Journey: Identifying Students Needing Support

1. Teacher navigates to "Student Performance" section
2. System automatically analyzes all students:
   - Calculates average quiz scores
   - Determines completion rates
   - Identifies engagement levels
   - Detects performance trends
3. Dashboard displays:
   - Students marked as "Needs Support" (red indicator)
   - Specific reasons for support flag
   - Individual performance metrics
4. Teacher clicks on student for detailed view:
   - Complete quiz history
   - Score trends over time
   - Game engagement
   - Suggested interventions
5. Teacher can provide targeted support

### File Upload System

1. Teacher selects file from device
2. System validates:
   - File type (PDF, DOC, PPT, video formats)
   - File size (maximum 50MB)
3. File uploaded to secure storage
4. Symbolic link created for public access
5. Metadata stored in database
6. Students can download/view file

---

## Security

### Authentication & Authorization
- Secure password hashing with bcrypt
- Session-based authentication
- Role-based access control (RBAC)
- Route middleware protection
- Policy-based authorization

### Data Protection
- CSRF token protection on all forms
- SQL injection prevention via Eloquent ORM
- XSS protection with Blade templating
- Input validation on all user inputs
- Sanitization of file uploads
- Soft deletes for data recovery

### File Upload Security
- Whitelist of allowed file types
- File size restrictions
- Secure file storage outside web root
- File name sanitization
- MIME type validation

### Best Practices
- Environment variables for sensitive data
- HTTPS recommended for production
- Regular security updates
- Database connection encryption
- Rate limiting on authentication

---

## Testing

### Create Test Users

**Create a Teacher:**
```bash
php artisan tinker
```
```php
\App\Models\User::create([
    'name' => 'Test Teacher',
    'email' => 'teacher@normninja.com',
    'password' => bcrypt('teacher123'),
    'role' => 'teacher',
    'is_active' => true
]);
```

**Create a Student:**
```php
\App\Models\User::create([
    'name' => 'Test Student',
    'email' => 'student@normninja.com',
    'password' => bcrypt('student123'),
    'role' => 'student',
    'student_id' => 'STU001',
    'is_active' => true
]);
```

### Default Login Credentials

After setup:
- **Admin**: admin@normninja.com / admin123
- **Teacher**: teacher@normninja.com / teacher123
- **Student**: student@normninja.com / student123

### Run Tests

```bash
php artisan test
```

---

## Deployment

### Production Checklist

- [ ] Set `APP_ENV=production` in .env
- [ ] Set `APP_DEBUG=false` in .env
- [ ] Configure production database
- [ ] Set up HTTPS/SSL certificate
- [ ] Run optimization commands:
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  composer install --optimize-autoloader --no-dev
  ```
- [ ] Configure proper file permissions
- [ ] Set up automated backups
- [ ] Configure email settings for notifications
- [ ] Set up queue workers for background tasks
- [ ] Configure file upload limits in php.ini

### Quick Deploy Script

```bash
composer setup    # Install and configure
composer dev      # Start development servers
composer test     # Run tests
```

For detailed deployment instructions, see [DEPLOYMENT.md](DEPLOYMENT.md)

---

## What's Included

### Current Implementation Status

**Fully Implemented:**
- Admin dashboard and user management
- Teacher dashboard and content creation
- Student dashboard and learning interface
- Learning materials upload and management
- Complete quiz system with 3 question types
- Educational games (flashcards, matching, gamified quizzes)
- Discussion forums with threaded replies
- Student performance analytics
- Calendar events and reminders
- Profile management for all user types
- Responsive design with Tailwind CSS
- Role-based access control
- File upload system with validation
- Automatic quiz grading
- Student support identification system
- Game leaderboards and statistics

**Database:**
- 14 migration files
- Complete schema for all features
- Foreign key relationships
- Indexes for performance
- Soft deletes for data integrity

**Views:**
- 45+ Blade template files
- Admin, teacher, and student interfaces
- Quiz creation and taking interfaces
- Game play interfaces
- Forum discussion views
- Dashboard layouts for all roles
- Profile management pages
- Calendar views

**Controllers:**
- AdminController (user management)
- TeacherController (teacher features)
- StudentController (student features)
- QuizController (quiz management)
- QuizQuestionController (question CRUD)
- GameController (game functionality)
- ForumController (forum features)
- LearningMaterialController (material management)
- CalendarEventController (calendar features)
- ReminderController (reminder features)
- AuthController (authentication)

---

## Future Enhancements

### Planned Features

1. **Email Notifications**
   - Quiz assignment notifications
   - Grade notifications
   - Support alerts for teachers
   - Calendar reminders

2. **Advanced Analytics**
   - Learning curve visualization
   - Predictive analytics for student success
   - Custom report generation
   - Export analytics to PDF/Excel

3. **Assignments System**
   - Create and manage assignments
   - File submission support
   - Rubric-based grading
   - Peer review functionality

4. **Collaboration Tools**
   - Group projects
   - Peer review system
   - Study groups
   - Collaborative documents

5. **Mobile Application**
   - Native iOS app
   - Native Android app
   - Offline access to materials
   - Push notifications

6. **Integration**
   - Google Classroom sync
   - Microsoft Teams integration
   - Zoom meeting integration
   - Calendar sync (Google, Outlook)

7. **Advanced Gamification**
   - Achievement badges
   - Student leaderboards
   - Reward points system
   - Progress unlockables

8. **Content Enhancement**
   - Video streaming support
   - Interactive content creation
   - Markdown editor for materials
   - LaTeX support for math equations

---

## Contributing

We welcome contributions to NormNinja! Here's how you can help:

### Getting Started

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Development Guidelines

- Follow PSR-12 coding standards
- Write meaningful commit messages
- Add tests for new features
- Update documentation as needed
- Ensure all tests pass before submitting PR

### Code Style

```bash
# Run Laravel Pint for code formatting
./vendor/bin/pint
```

---

## Support & Troubleshooting

### Common Issues

**Issue: 500 Error**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

**Issue: Storage link not working**
```bash
rm public/storage
php artisan storage:link
```

**Issue: Permission denied**
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Getting Help

- Check logs: `storage/logs/laravel.log`
- Review documentation: [FEATURES.md](FEATURES.md) and [DEPLOYMENT.md](DEPLOYMENT.md)
- Contact the development team

---

## Credits

**Developed by:** Data Voyagers Team

### Team Members
- Project Lead & Backend Developer
- Frontend Developer & UI/UX Designer
- Database Architect
- Quality Assurance & Testing

### Technologies & Frameworks
- Laravel Framework - Taylor Otwell
- Tailwind CSS - Adam Wathan
- Font Awesome - Dave Gandy

### Special Thanks
- The Laravel community
- All contributors and testers
- Educational institutions providing feedback

---

## License

This project is licensed under the MIT License. See LICENSE file for details.

---

## Version History

**v1.0.0** (Current)
- Initial release
- Complete LMS functionality
- Admin, teacher, and student roles
- Quiz, game, and forum systems
- Learning materials management
- Student performance analytics
- Calendar and reminders
- Responsive design

---

## Contact

For questions, suggestions, or support:
- Email: support@normninja.com
- Website: https://normninja.com
- GitHub: https://github.com/yourusername/normninja

---

**Made with ❤️ for education**

Transform your educational institution with NormNinja - where learning meets technology!
