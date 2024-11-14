<?php
session_start();

// Include the db.php file using the correct relative path
require_once __DIR__ . '/../db.php'; // Moves one directory up to access db.php

// Example student data - this can be fetched from the database as needed
$students = [
    ['name' => "John Doe (Roll No: 001)"],
    ['name' => "Jane Smith (Roll No: 002)"],
    ['name' => "Michael Johnson (Roll No: 003)"],
];

// Retrieve class name from the URL parameter
$class_name = isset($_GET['class']) ? htmlspecialchars($_GET['class']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details - Aptitude</title>
    <link rel="stylesheet" href="teacher.css"> <!-- Same CSS for consistency -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for icons -->
    <style>
        /* Panel Style */
        .student-panel {
            background-color: #f5f5f5;
            border: 2px solid #ccc;
            border-radius: 8px;
            margin: 10px;
            padding: 20px;
            text-align: center;
            width: 200px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none; /* Hide by default */
            position: absolute;
            background-color: #d0b8a8;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #8d493a;
        }

        .student-panel:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .student-panel h3 {
            margin-bottom: 10px;
            font-size: 18px;
            cursor: pointer;
        }

        .view-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .view-button:hover {
            background-color: #45a049;
        }

        .student-panels {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Left Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="profile">
                <img src="profile-pic.png" alt="Profile Image" class="profile-img">
                <h3 id="username">John Doe</h3>
                <p id="reg-number">Reg No: 12345678</p>
            </div>
            <div id="menu" class="menu">
                <ul>
                    <li onclick="navigateTo('home')">Home</li>
                    <li>
                        <div class="dropdown">
                            <span onclick="toggleDropdown()">Resources</span>
                            <div class="dropdown-content" id="resources-dropdown">
                                <a href="#" onclick="navigateTo('aptitude')">Aptitude</a>
                                <a href="#" onclick="navigateTo('verbal')">Verbal</a>
                                <a href="#" onclick="navigateTo('softskills')">Soft Skills</a>
                                <a href="#" onclick="navigateTo('training')">Professional Training</a>
                            </div>
                        </div>
                    </li>
                    <li onclick="navigateTo('chat')">Chat</li>
                    <li onclick="navigateTo('history')">My History</li>
                    <li onclick="navigateTo('faq')">FAQ</li>
                </ul>
            </div>
        </div>

        <button id="menuToggle" class="menu-icon">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Main Content Area -->
        <div class="main-content">
            <div class="top-bar">
                <div class="search-container">
                    <input type="text" placeholder="Search...">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <h2>View Students' Marks: Aptitude <span id="class-name"><?php echo $class_name; ?></span></h2>

            <div class="student-panels" id="student-panels-container">
                <!-- Panels will be inserted dynamically -->
            </div>
        </div>
    </div>

    <script src="teacher.js"></script> <!-- Link to the external JavaScript file -->
</body>
</html>
