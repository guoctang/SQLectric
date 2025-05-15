<?php
session_start();

// Function to check required PHP extensions
function checkExtensions() {
    $requiredExtensions = ['mysqli', 'mbstring', 'json'];
    $missingExtensions = [];

    foreach ($requiredExtensions as $extension) {
        if (!extension_loaded($extension)) {
            $missingExtensions[] = $extension;
        }
    }

    return $missingExtensions;
}

// Function to handle database installation
function installDatabase($host, $user, $password, $dbname) {
    $connection = new mysqli($host, $user, $password);

    if ($connection->connect_error) {
        return "Connection failed: " . $connection->connect_error;
    }

    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if (!$connection->query($sql)) {
        return "Error creating database: " . $connection->error;
    }

    // Select the database
    $connection->select_db($dbname);

    // Load SQL file
    $sqlFile = 'database.sql';
    if (file_exists($sqlFile)) {
        $sqlCommands = file_get_contents($sqlFile);
        if ($connection->multi_query($sqlCommands)) {
            do {
                if ($result = $connection->store_result()) {
                    $result->free();
                }
            } while ($connection->more_results() && $connection->next_result());
        } else {
            return "Error executing SQL file: " . $connection->error;
        }
    } else {
        return "SQL file not found.";
    }

    $connection->close();
    return true;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['step'])) {
        $step = $_POST['step'];

        if ($step == 1) {
            // Welcome step
            echo "<h1>Welcome to the PHP & MySQL Installer</h1>";
            echo "<form method='POST'>";
            echo "<input type='hidden' name='step' value='2'>";
            echo "<input type='submit' value='Next'>";
            echo "</form>";
        } elseif ($step == 2) {
            // Environment check
            $missingExtensions = checkExtensions();
            if (empty($missingExtensions)) {
                echo "<h1>All required extensions are installed.</h1>";
            } else {
                echo "<h1>Missing Extensions:</h1><ul>";
                foreach ($missingExtensions as $extension) {
                    echo "<li>$extension</li>";
                }
                echo "</ul>";
            }
            echo "<form method='POST'>";
            echo "<input type='hidden' name='step' value='3'>";
            echo "<input type='submit' value='Next'>";
            echo "</form>";
        } elseif ($step == 3) {
            // Database information input
            echo "<h1>Enter Database Information</h1>";
            echo "<form method='POST'>";
            echo "<input type='hidden' name='step' value='4'>";
            echo "Host: <input type='text' name='host' required><br>";
            echo "User: <input type='text' name='user' required><br>";
            echo "Password: <input type='password' name='password'><br>";
            echo "Database Name: <input type='text' name='dbname' required><br>";
            echo "<input type='submit' value='Next'>";
            echo "</form>";
        } elseif ($step == 4) {
            // Admin information input
            echo "<h1>Enter Admin Information</h1>";
            echo "<form method='POST'>";
            echo "<input type='hidden' name='step' value='5'>";
            echo "Admin Username: <input type='text' name='admin_user' required><br>";
            echo "Admin Password: <input type='password' name='admin_pass' required><br>";
            echo "<input type='submit' value='Install'>";
            echo "</form>";
        } elseif ($step == 5) {
            // Install database
            $host = $_POST['host'];
            $user = $_POST['user'];
            $password = $_POST['password'];
            $dbname = $_POST['dbname'];

            $result = installDatabase($host, $user, $password, $dbname);
            if ($result === true) {
                echo "<h1>Installation Successful!</h1>";
            } else {
                echo "<h1>Installation Failed:</h1><p>$result</p>";
            }
        }
    } else {
        // Default to welcome step
        echo "<h1>Welcome to the PHP & MySQL Installer</h1>";
        echo "<form method='POST'>";
        echo "<input type='hidden' name='step' value='1'>";
        echo "<input type='submit' value='Start Installation'>";
        echo "</form>";
    }
} else {
    // Default to welcome step
    echo "<h1>Welcome to the PHP & MySQL Installer</h1>";
    echo "<form method='POST'>";
    echo "<input type='hidden' name='step' value='1'>";
    echo "<input type='submit' value='Start Installation'>";
    echo "</form>";
}
?>