# Smart Film Makers - AI-Powered Script Generation Platform

A comprehensive web application that transforms creative ideas into professional film scripts using AI technology.

## Features

### User Features
- **Authentication**: Secure signup, login, and password reset system
- **Project Management**: Create, edit, and manage film projects
- **AI Content Generation**: Generate story foundations, character profiles, production plans, and pitch decks
- **Export Options**: Download projects in PDF, DOCX, and TXT formats
- **Responsive Design**: Mobile-first design that works on all devices

### Admin Features
- **User Management**: View, block/unblock users
- **Content Analytics**: Track project generation and usage statistics
- **AI Prompt Management**: Edit and customize AI prompt templates
- **System Settings**: Configure export formats and site branding

## Technology Stack

- **Frontend**: PHP with MDBootstrap UI framework
- **Backend**: PHP 8.x
- **Database**: MySQL
- **AI Integration**: OpenAI API (GPT-3.5-turbo)
- **Styling**: Custom CSS with MDBootstrap components
- **JavaScript**: Vanilla JS with MDB UI Kit

## Installation

### Prerequisites
- PHP 8.0 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- OpenAI API key

### Setup Instructions

1. **Clone/Download the Project**
   ```bash
   git clone <repository-url>
   cd smart_film_makers
   ```

2. **Configure Database**
   - Create a MySQL database named `smart_film_makers`
   - Update database credentials in `config.php`

3. **Run Database Setup**
   - Navigate to `http://localhost/smart_film_makers/setup.php` in your browser
   - This will create all necessary tables and insert default data

4. **Configure OpenAI API**
   - Open `config.php`
   - Replace `your-openai-api-key-here` with your actual OpenAI API key

5. **Set File Permissions**
   ```bash
   chmod 755 uploads/
   chmod 755 exports/
   ```

6. **Access the Application**
   - **Main Site**: `http://localhost/smart_film_makers/`
   - **Admin Portal**: `http://localhost/smart_film_makers/admin/`

## Default Admin Credentials

- **Email**: admin@smartfilmmakers.com
- **Password**: admin123

## Project Structure

```
smart_film_makers/
├── admin/                  # Admin panel files
│   ├── login.php          # Admin login
│   ├── dashboard.php      # Admin dashboard
│   └── logout.php         # Admin logout
├── api/                   # API endpoints
│   ├── create-project.php # Project creation API
│   ├── generate-content.php # AI content generation
│   └── export.php         # Export functionality
├── assets/                # Static assets
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   └── images/           # Image files
├── includes/             # Include files
├── uploads/              # User uploads
├── exports/              # Generated exports
├── config.php            # Configuration file
├── functions.php         # Core functions
├── database.sql          # Database schema
├── setup.php             # Database setup script
├── index.php             # Landing page
├── login.php             # User login
├── register.php          # User registration
├── dashboard.php         # User dashboard
├── create-project.php    # Create new project
├── project.php           # Project view/edit
└── README.md             # This file
```

## Database Schema

The application uses the following main tables:

- **users**: User accounts and authentication
- **projects**: Film projects and generated content
- **admin_users**: Administrator accounts
- **ai_prompts**: AI prompt templates
- **usage_analytics**: Usage tracking and statistics
- **export_history**: Export tracking

## API Endpoints

### Create Project
```
POST /api/create-project.php
Content-Type: application/json

{
  "title": "Project Title",
  "idea": "Film idea description",
  "genre": "Drama",
  "target_audience": "Adults",
  "language": "English"
}
```

### Generate Content
```
POST /api/generate-content.php
Content-Type: application/json

{
  "project_id": 1,
  "section": "story" // story, characters, production, pitch_deck
}
```

### Export Project
```
GET /api/export.php?project_id=1&format=pdf
// Formats: pdf, docx, txt
```

## Customization

### AI Prompts
Edit AI prompt templates through the admin panel or directly in the `ai_prompts` table.

### Styling
Modify `assets/css/style.css` for custom styling changes.

### Export Formats
Add new export formats by extending the export functions in `functions.php`.

## Security Features

- Password hashing using PHP's password_hash()
- SQL injection prevention with prepared statements
- XSS protection with htmlspecialchars()
- Session-based authentication
- CSRF protection in forms

## Performance Considerations

- Database indexes on frequently queried columns
- Efficient SQL queries with proper joins
- Optimized AI API calls with caching (can be implemented)
- Responsive image loading

## Future Enhancements

- Real-time collaboration features
- Advanced screenplay formatting
- Character relationship mapping
- Budget calculator
- Production timeline generator
- Integration with writing software
- Mobile app development

## Support

For issues and questions:
1. Check the documentation
2. Review error logs
3. Verify database connections
4. Test API endpoints separately

## License

This project is for demonstration purposes. Please ensure compliance with OpenAI's terms of service and applicable licensing requirements.

---

**Note**: This is a demonstration application. For production use, implement additional security measures, error handling, and scalability optimizations.
