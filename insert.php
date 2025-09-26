<?php
include 'db_connect.php';

$error = "";
$success = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idNumber = trim($_POST['idNumber']);
    $name = trim($_POST['name']);
    $age = trim($_POST['age']);
    $email = trim($_POST['email']);
    $contactNumber = trim($_POST['contactNumber']);
    $course = trim($_POST['course']);
    $yearLevel = trim($_POST['yearLevel']);

    // Basic validation
    if (empty($idNumber) || empty($name) || empty($age) || empty($email)) {
        $error = "All required fields must be filled!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } else {
        // Check if ID Number already exists
        $checkSql = "SELECT id FROM students WHERE idNumber = '$idNumber'";
        $checkResult = $conn->query($checkSql);
        
        if ($checkResult->num_rows > 0) {
            $error = "ID Number already exists!";
        } else {
            $sql = "INSERT INTO students (idNumber, name, age, email, contactNumber, course, yearLevel) 
                    VALUES ('$idNumber', '$name', '$age', '$email', '$contactNumber', '$course', '$yearLevel')";
            if ($conn->query($sql) === TRUE) {
                $success = "Student added successfully!";
                $idNumber = $name = $age = $email = $contactNumber = $course = $yearLevel = "";
            } else {
                $error = "Database Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Student - Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">
            <a href="select.php" class="navbar-brand mb-0 h1">
                <i class="bi bi-mortarboard-fill me-2"></i>
                Student Management System
            </a>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="select.php" class="text-white-50 text-decoration-none">
                            <i class="bi bi-house-fill me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Add Student</li>
                </ol>
            </nav>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                
                <!-- Header Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-person-plus-fill text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h2 class="card-title text-primary mb-1">Add New Student</h2>
                        <p class="text-muted mb-0">Enter student information to add to the database</p>
                    </div>
                </div>

                <!-- Alert Messages -->
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Error!</strong> <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <strong>Success!</strong> <?= htmlspecialchars($success) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Form Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0 text-primary">
                            <i class="bi bi-form-check me-2"></i>
                            Student Information Form
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="needs-validation" novalidate id="studentForm">
                            <div class="row">
                                <!-- ID Number -->
                                <div class="col-md-6 mb-3">
                                    <label for="idNumber" class="form-label fw-bold">
                                        <i class="bi bi-card-text me-1 text-primary"></i>
                                        ID Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="idNumber" 
                                           id="idNumber"
                                           class="form-control form-control-lg" 
                                           placeholder="e.g., 2024-001234"
                                           value="<?= isset($idNumber) ? htmlspecialchars($idNumber) : '' ?>"
                                           required>
                                    <div class="invalid-feedback">
                                        Please provide a valid ID number.
                                    </div>
                                </div>

                                <!-- Age -->
                                <div class="col-md-6 mb-3">
                                    <label for="age" class="form-label fw-bold">
                                        <i class="bi bi-calendar3 me-1 text-primary"></i>
                                        Age <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           name="age" 
                                           id="age"
                                           class="form-control form-control-lg" 
                                           placeholder="Enter age"
                                           min="16" 
                                           max="100"
                                           value="<?= isset($age) ? htmlspecialchars($age) : '' ?>"
                                           required>
                                    <div class="invalid-feedback">
                                        Please provide a valid age (16-100).
                                    </div>
                                </div>
                            </div>

                            <!-- Full Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">
                                    <i class="bi bi-person me-1 text-primary"></i>
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name"
                                       class="form-control form-control-lg" 
                                       placeholder="Enter full name"
                                       value="<?= isset($name) ? htmlspecialchars($name) : '' ?>"
                                       required>
                                <div class="invalid-feedback">
                                    Please provide the student's full name.
                                </div>
                            </div>

                            <div class="row">
                                <!-- Email -->
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-bold">
                                        <i class="bi bi-envelope me-1 text-primary"></i>
                                        Email Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           name="email" 
                                           id="email"
                                           class="form-control form-control-lg" 
                                           placeholder="student@example.com"
                                           value="<?= isset($email) ? htmlspecialchars($email) : '' ?>"
                                           required>
                                    <div class="invalid-feedback">
                                        Please provide a valid email address.
                                    </div>
                                </div>

                                <!-- Contact Number -->
                                <div class="col-md-6 mb-3">
                                    <label for="contactNumber" class="form-label fw-bold">
                                        <i class="bi bi-telephone me-1 text-primary"></i>
                                        Contact Number
                                    </label>
                                    <input type="tel" 
                                           name="contactNumber" 
                                           id="contactNumber"
                                           class="form-control form-control-lg" 
                                           placeholder="e.g., +63 912 345 6789"
                                           value="<?= isset($contactNumber) ? htmlspecialchars($contactNumber) : '' ?>">
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>Optional field
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Course -->
                                <div class="col-md-6 mb-3">
                                    <label for="course" class="form-label fw-bold">
                                        <i class="bi bi-book me-1 text-primary"></i>
                                        Course
                                    </label>
                                    <select name="course" id="course" class="form-select form-select-lg">
                                        <option value="">Select Course</option>
                                        <option value="BSIT">BSIT</option>
                                        <option value="BSCS">BSCS</option>
                                        <option value="BSMT">BSMT</option>
                                        <option value="BSED">BSED</option>
                                        <option value="BSN">BSN</option>
                                        <option value="BSPSYCH">BSPSYCH</option>
                                        <option value="BSCE">BSCE</option>
                                    </select>
                                </div>

                                <!-- Year Level -->
                                <div class="col-md-6 mb-3">
                                    <label for="yearLevel" class="form-label fw-bold">
                                        <i class="bi bi-calendar-check me-1 text-primary"></i>
                                        Year Level <span class="text-danger">*</span>
                                    </label>
                                    <select name="yearLevel" id="yearLevel" class="form-select form-select-lg" required>
                                        <option value="">Select Year Level</option>
                                        <option value="1">1st Year</option>
                                        <option value="2">2nd Year</option>
                                        <option value="3">3rd Year</option>
                                        <option value="4">4th Year</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a year level.
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                                <a href="select.php" class="btn btn-outline-secondary btn-lg me-md-2">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Cancel
                                </a>
                                <button type="reset" class="btn btn-outline-warning btn-lg me-md-2">
                                    <i class="bi bi-arrow-clockwise me-2"></i>
                                    Reset Form
                                </button>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    Add Student
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-3 mt-5">
        <div class="container-fluid text-center">
            <small>&copy; 2024 Student Management System. All rights reserved.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Bootstrap form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                Array.prototype.forEach.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>
