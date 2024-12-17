# Project Rota Business English

## Overview
Project RBE is a comprehensive web-based application designed to facilitate the management of an academic institution. It provides a user-friendly interface for students, teachers, and administrators to interact with the system efficiently. The application is built using PHP and leverages Bootstrap for a responsive design, ensuring accessibility across various devices.

## Features
- **User Authentication**: Secure login system for students, teachers, and administrators.
- **Role-Based Access Control**: Different functionalities and views for each user role.
- **Responsive Design**: Utilizes Bootstrap to ensure the application is mobile-friendly.
- **Session Management**: PHP sessions are used to maintain user state across pages.
- **Custom Styling and Scripts**: Includes personalized CSS and JavaScript for enhanced user experience.
- **Database Integration**: Connects to a MySQL database for data storage and retrieval.

## Installation

### Prerequisites
- **XAMPP**: A PHP development environment to run the application locally.
- **Web Browser**: Any modern browser like Chrome or Firefox.

### Setup
1. **Clone the Repository**: Download the project files to your local machine.
2. **Move to XAMPP**: Place the project directory in the `htdocs` folder of your XAMPP installation.
3. **Start XAMPP**: Ensure Apache and MySQL services are running.
4. **Database Setup**:
   - Access phpMyAdmin via your browser.
   - Create a new database named `project_rbe`.
   - Import the SQL file provided in the `database` directory.
5. **Configure Database Connection**:
   - Open `db.php` and update the database credentials if necessary.

6. **Access the Application**: Open your browser and navigate to `http://localhost/Project RBE/`.

## Usage
- **Homepage**: Log in using your credentials to access the system.
- **Role-Specific Pages**: Depending on your role, you will be directed to the appropriate dashboard (e.g., `student.php`, `teacher.php`, `admin.php`).
- **Exams Management**: Use `examenes.php` to manage and view exams.

## File Structure
- **index.php**: Main entry point of the application.
- **student.php, teacher.php, admin.php**: Role-specific pages for different user types.
- **examenes.php**: Page for managing exams.
- **js/index.js**: Custom JavaScript for client-side interactions.
- **css/index.css**: Custom styles for the application.

## Dependencies
- **Bootstrap 4.6.1**: For responsive design.
- **jQuery 3.6.0**: For DOM manipulation and AJAX calls.
- **Popper.js 1.16.1**: For tooltip and popover positioning.
- **Font Awesome 4.7.0**: For icons.

## Contributing
We welcome contributions! Please fork the repository and submit a pull request for any enhancements or bug fixes. Ensure your code follows the project's coding standards and includes appropriate documentation.

## License
This project is licensed under the MIT License. See the `LICENSE` file for more details.

## Contact
For any inquiries or support, please contact [soth26@gmail.com](soth26@gmail.com).
