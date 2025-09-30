# Copilot Instructions for MainServer v2

## Project Overview

MainServer v2 is a minimalist PHP web server with authentication, administrative control panel, and system console. The project is designed to run on Windows, Linux, and Raspberry Pi systems.

**Key Technologies:**
- Backend: PHP 7.4+ with SQLite
- Frontend: Vanilla JavaScript, HTML5, CSS3
- No framework dependencies - pure PHP and vanilla JS

## Coding Standards

### PHP Code Style

- Use PHP 7.4+ features
- Follow PSR-12 coding standards
- Always use strict types when possible
- Use short array syntax `[]` instead of `array()`
- Always sanitize user inputs with `htmlspecialchars()` or prepared statements
- Use `===` for comparisons instead of `==`
- Always close PHP tags `?>` at the end of files that only contain PHP
- Use `const` for configuration values, not `define()`

Example:
```php
<?php
// Good
const SESSION_TIMEOUT = 600;
$result = htmlspecialchars($user_input);

// Avoid
define('SESSION_TIMEOUT', 600);
$result = $user_input;
?>
```

### JavaScript Code Style

- Use modern ES6+ syntax (const, let, arrow functions)
- Use `fetch()` API for AJAX requests, not XMLHttpRequest
- Always include error handling for async operations
- Use template literals for string interpolation
- Prefer `async/await` over promise chains when possible
- Use `textContent` instead of `innerHTML` when setting text to prevent XSS

Example:
```javascript
// Good
async function loadData() {
    try {
        const response = await fetch('/api/endpoint');
        const data = await response.json();
        element.textContent = data.message;
    } catch (error) {
        console.error('Error:', error);
    }
}

// Avoid
function loadData() {
    fetch('/api/endpoint')
        .then(r => r.json())
        .then(data => {
            element.innerHTML = data.message; // XSS risk
        });
}
```

### HTML/CSS Standards

- Use semantic HTML5 elements
- Always include proper meta tags for charset and viewport
- Use CSS variables for theming (already defined in global.css)
- Maintain responsive design principles
- Keep inline styles to a minimum - prefer CSS classes
- Spanish language is used for user-facing content (`lang="es"`)

## Security Requirements

**Critical Security Rules:**

1. **Authentication & Authorization:**
   - Always validate user sessions before executing any API endpoint
   - Check user roles (normal, admin, owner) before granting access
   - Never trust client-side validation alone

2. **Input Validation:**
   - Sanitize all user inputs with `htmlspecialchars()` for output
   - Use prepared statements or whitelist validation for database queries
   - Validate command inputs against whitelist/blacklist

3. **Command Execution:**
   - Maintain strict whitelist for allowed system commands (see `ALLOWED_COMMANDS` in config)
   - Never execute user input directly without validation
   - Implement blacklist for dangerous commands (`rm -rf /`, `dd`, `mkfs`, etc.)
   - Always use timeouts (30 seconds) for command execution

4. **CSRF Protection:**
   - Include CSRF tokens in all forms that modify data
   - Validate CSRF tokens on the backend

5. **Session Management:**
   - Implement session timeouts (default: 10 minutes)
   - Close sessions before critical system actions
   - Use secure session handling

## Project Structure

```
MainServer_v2/
├── public/                    # Web root directory
│   ├── api/                   # Backend API endpoints
│   │   ├── db.php            # Database configuration
│   │   ├── login.php         # Authentication
│   │   ├── register.php      # User registration
│   │   ├── user_role.php     # Role management
│   │   ├── execute_command.php # System command execution
│   │   └── system_actions.php  # System control actions
│   ├── admin/                # Admin panel (separate from main app)
│   │   ├── inc/              # Admin includes and config
│   │   └── api/              # Admin-specific APIs
│   ├── pages/                # HTML pages
│   │   └── control-panel.html # Admin control panel
│   ├── src/                  # Frontend components
│   │   ├── navbar.html       # Navigation component
│   │   └── navbar.js         # Navigation logic
│   └── styles/
│       └── global.css        # Global styles
├── scripts/                  # Utility scripts
└── README.md                 # Project documentation
```

## Database Schema

The project uses SQLite with the following schema:

```sql
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,  -- bcrypt hashed
    role TEXT DEFAULT 'normal', -- normal, admin, owner
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

## API Conventions

### Request Format
- Use JSON for POST request bodies
- Always include `Content-Type: application/json` header
- Include credentials with `credentials: 'include'` for authenticated requests

### Response Format
- Return JSON responses with consistent structure:
```json
{
    "success": true/false,
    "message": "Description",
    "data": { /* response data */ }
}
```

### Error Handling
- Return appropriate HTTP status codes
- Include descriptive error messages
- Log errors for debugging

## Testing Guidelines

**Note:** This project currently has no automated test suite. Manual testing is required.

### Manual Testing Checklist

When making changes, test:

1. **Authentication Flow:**
   - Login with valid/invalid credentials
   - Registration with valid/duplicate usernames
   - Session expiration and logout

2. **Role-Based Access:**
   - Normal users should not access control panel
   - Admin/owner users should access all features

3. **Control Panel:**
   - Command execution with allowed commands
   - System actions (restart/shutdown) with proper confirmations
   - Command blacklist enforcement

4. **Cross-Browser Testing:**
   - Test on Chrome, Firefox, Edge
   - Verify responsive design on mobile

## Deployment Considerations

### Environment Setup

**Windows:**
```cmd
start-php-server.bat
```

**Linux/Raspberry Pi:**
```bash
cd public
php -S localhost:8000 router.php
```

### Initial Configuration

1. Create database: SQLite auto-creates on first run
2. Create owner user: Run `scripts/make_password.php` then `public/api/create_admin_owner.php`
3. Configure allowed commands in `public/admin/inc/config.local.php`

### Security Hardening

- Change default admin credentials immediately
- Review and customize `ALLOWED_COMMANDS` list
- Set appropriate file permissions on Linux systems
- Use HTTPS in production environments
- Configure firewall to restrict access to control panel

## Common Tasks

### Adding a New API Endpoint

1. Create new PHP file in `public/api/`
2. Include session validation and role checks
3. Sanitize all inputs
4. Return JSON response with proper structure
5. Update router.php if needed

### Adding a New Page

1. Create HTML file in `public/pages/`
2. Include navbar component: `<div id="navbar"></div>`
3. Load navbar script: `<script src="../src/navbar.js"></script>`
4. Use global.css for consistent styling
5. Implement role-based access if needed

### Modifying System Commands

1. Edit `ALLOWED_COMMANDS` in `public/admin/inc/config.local.php`
2. Test command execution thoroughly
3. Verify blacklist is not bypassed
4. Ensure proper timeouts are set

## Best Practices

1. **Minimize Changes:** Make surgical, focused changes to existing code
2. **Preserve Functionality:** Never break working features
3. **Test Thoroughly:** Manually test all affected functionality
4. **Document Changes:** Update README.md if adding major features
5. **Security First:** Always prioritize security in code changes
6. **Keep It Simple:** Maintain the minimalist philosophy of the project
7. **Spanish UI:** Keep user-facing text in Spanish (code comments can be in English)
8. **Cross-Platform:** Ensure changes work on Windows, Linux, and Raspberry Pi

## File Naming Conventions

- PHP files: `snake_case.php` (e.g., `execute_command.php`)
- HTML files: `kebab-case.html` (e.g., `control-panel.html`)
- JavaScript files: `camelCase.js` or `kebab-case.js`
- CSS files: `kebab-case.css` or `global.css`

## Development Workflow

1. **Before Making Changes:**
   - Understand the existing code structure
   - Identify minimal changes needed
   - Consider security implications

2. **During Development:**
   - Make incremental changes
   - Test each change manually
   - Check for console errors in browser

3. **After Changes:**
   - Test all affected functionality
   - Verify no regressions
   - Update documentation if needed
   - Test on different environments (if possible)

## Special Considerations

### Raspberry Pi Support
- Some system actions require sudo permissions
- Test system commands with appropriate permissions
- Be aware of platform-specific paths and tools

### Session Management
- Sessions are stored server-side
- Default timeout: 10 minutes (configurable)
- Critical actions close session for security

### Command Blacklist
Dangerous commands that are always blocked:
- `rm -rf /`, `rm -rf *`
- `dd if=`
- `mkfs`, `mkswap`
- `passwd`, `userdel`, `usermod`
- `shutdown`, `reboot`, `halt`, `poweroff`
- And others defined in blacklist

## Code Review Checklist

Before submitting changes, ensure:

- [ ] Code follows project conventions
- [ ] All inputs are properly sanitized
- [ ] Authentication/authorization checks are in place
- [ ] Error handling is implemented
- [ ] No hardcoded credentials or secrets
- [ ] Changes are tested manually
- [ ] No breaking changes to existing features
- [ ] Code is readable and maintainable
- [ ] Spanish used for user-facing text
- [ ] Responsive design is maintained

## Resources

- Project README: `/README.md`
- Configuration: `public/admin/inc/config.local.php`
- Database setup: `public/api/db.php`
- Global styles: `public/styles/global.css`

## Questions?

If unsure about any aspect of the codebase:
1. Check existing code for similar patterns
2. Review the README.md for project context
3. Prioritize security and simplicity
4. Ask for clarification if needed
