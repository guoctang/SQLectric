# PHP & MySQL Installer

This project provides a simple installation script for setting up a PHP application with a MySQL database, similar to WordPress's one-click installation.

## Project Structure

- `install.php`: The core installation script that handles user input, checks the environment and extensions, and executes the database installation. It guides users through the installation process, including a welcome page, environment checks, database information input, and administrator information input.
  
- `database.sql`: This file contains the structure and initial data for the database, which will be used during the installation process to create and populate the database.

## Requirements

Before running the installation script, ensure that your server meets the following requirements:

- PHP version 7.2 or higher
- MySQL version 5.6 or higher
- Required PHP extensions:
  - mysqli
  - PDO
  - mbstring
  - json

## Installation Instructions

1. **Download the Project**: Clone or download the repository to your local server.

2. **Prepare the Database**: Ensure you have access to a MySQL server and create a database for the installation.

3. **Run the Installer**: Open your web browser and navigate to `install.php`. Follow the on-screen instructions to complete the installation.

4. **Database Configuration**: During the installation, you will be prompted to enter your database information, including the database name, username, and password.

5. **Admin Account Setup**: You will also need to provide details for the administrator account, including username and password.

## Troubleshooting

If you encounter any issues during the installation, please check the following:

- Ensure that all required PHP extensions are enabled.
- Verify that the database credentials are correct.
- Check the server error logs for any additional information.

## License

This project is licensed under the MIT License. Feel free to modify and use it as per your needs.