<?php
include 'db_connect.php';

if (!isset($_GET['id'])) {
    die("
    <!DOCTYPE html>
    <html><head><title>Error</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head><body class='bg-light d-flex align-items-center justify-content-center min-vh-100'>
    <div class='alert alert-danger'><i class='bi bi-exclamation-triangle-fill me-2'></i>No student ID provided!</div>
    </body></html>");
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM students WHERE id=$id");
$row = $result->fetch_assoc();

if (!$row) {
    die("
    <!DOCTYPE html>
    <html><head><title>Error</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head><body class='bg-light d-flex align-items-center justify-content-center min-vh-100'>
    <div class='alert alert-warning'><i class='bi bi-person-x-fill me-2'></i>Student not found!</div>
    </body></html>");
}

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

    if (empty($idNumber) || empty($name) || empty($age) || empty($email)) {
        $error = "All required fields must be filled!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } else {
        $checkSql = "SELECT id FROM students WHERE idNumber = '$idNumber' AND id != $id";
        $checkResult = $conn->query($checkSql);
        
        if ($checkResult->num_rows > 0) {
            $error = "ID Number already exists for another student!";
        } else {
            $sql = "UPDATE students SET 
                    idNumber='$idNumber',
                    name='$name',
                    age='$age',
                    email='$email',
                    contactNumber='$contactNumber',
                    course='$course',
                    yearLevel='$yearLevel'
                    WHERE id=$id";
            if ($conn->query($sql) === TRUE) {
                $success = "Student information updated successfully!";
                $result = $conn->query("SELECT * FROM students WHERE id=$id");
                $row = $result->fetch_assoc();
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
    <title>Update Student - Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
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
                    <li class="breadcrumb-item active text-white" aria-current="page">Update Student</li>
                </ol>
            </nav>
        </div>
    </nav>
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-person-gear text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h2 class="card-title text-primary mb-1">Update Student Information</h2>
                        <p class="text-muted mb-0">
                            Editing details for: <strong><?= htmlspecialchars($row['name']) ?></strong>
                        </p>
                        <div class="mt-2">
                            <span class="badge bg-primary">
                                Student ID: <?= htmlspecialchars($row['idNumber']) ?>
                            </span>
                        </div>
                    </div>
                </div>
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
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0 text-primary">
                            <i class="bi bi-pencil-square me-2"></i>
                            Edit Student Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="idNumber" class="form-label fw-bold">
                                        <i class="bi bi-card-text me-1 text-primary"></i>
                                        ID Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="idNumber" id="idNumber"
                                           class="form-control form-control-lg"
                                           value="<?= htmlspecialchars($row['idNumber']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="age" class="form-label fw-bold">
                                        <i class="bi bi-calendar3 me-1 text-primary"></i>
                                        Age <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="age" id="age"
                                           class="form-control form-control-lg"
                                           value="<?= htmlspecialchars($row['age']) ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">
                                    <i class="bi bi-person me-1 text-primary"></i>
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" id="name"
                                       class="form-control form-control-lg"
                                       value="<?= htmlspecialchars($row['name']) ?>" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-bold">
                                        <i class="bi bi-envelope me-1 text-primary"></i>
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email" id="email"
                                           class="form-control form-control-lg"
                                           value="<?= htmlspecialchars($row['email']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="contactNumber" class="form-label fw-bold">
                                        <i class="bi bi-telephone me-1 text-primary"></i>
                                        Contact Number
                                    </label>
                                    <input type="tel" name="contactNumber" id="contactNumber"
                                           class="form-control form-control-lg"
                                           value="<?= htmlspecialchars($row['contactNumber']) ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="course" class="form-label fw-bold">
                                        <i class="bi bi-book me-1 text-primary"></i>
                                        Course
                                    </label>
                                    <select name="course" id="course" class="form-select form-select-lg">
                                        <option value="">Select Course</option>
                                        <option value="BSIT" <?php if ($row['course'] == 'BSIT') echo 'selected'; ?>>BSIT</option>
                                        <option value="BSCS" <?php if ($row['course'] == 'BSCS') echo 'selected'; ?>>BSCS</option>
                                        <option value="BSMT" <?php if ($row['course'] == 'BSMT') echo 'selected'; ?>>BSMT</option>
                                        <option value="BSED" <?php if ($row['course'] == 'BSED') echo 'selected'; ?>>BSED</option>
                                        <option value="BSN" <?php if ($row['course'] == 'BSN') echo 'selected'; ?>>BSN</option>
                                        <option value="BSPSYCH" <?php if ($row['course'] == 'BSPSYCH') echo 'selected'; ?>>BSPSYCH</option>
                                        <option value="BSCE" <?php if ($row['course'] == 'BSCE') echo 'selected'; ?>>BSCE</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="yearLevel" class="form-label fw-bold">
                                        <i class="bi bi-calendar-check me-1 text-primary"></i>
                                        Year Level <span class="text-danger">*</span>
                                    </label>
                                    <select name="yearLevel" id="yearLevel" class="form-select form-select-lg" required>
                                        <option value="">Select Year</option>
                                        <option value="1" <?= ($row['yearLevel']=="1")?'selected':'' ?>>1st Year</option>
                                        <option value="2" <?= ($row['yearLevel']=="2")?'selected':'' ?>>2nd Year</option>
                                        <option value="3" <?= ($row['yearLevel']=="3")?'selected':'' ?>>3rd Year</option>
                                        <option value="4" <?= ($row['yearLevel']=="4")?'selected':'' ?>>4th Year</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                                <a href="select.php" class="btn btn-outline-secondary btn-lg">
                                    <i class="bi bi-arrow-left me-2"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-warning btn-lg">
                                    <i class="bi bi-check-circle me-2"></i> Update Student
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0 text-muted">
                            <i class="bi bi-info-circle me-1"></i> Current Student Details
                        </h6>
                    </div>
                    <div class="card-body">
                        <p><strong>ID Number:</strong> <?= htmlspecialchars($row['idNumber']) ?></p>
                        <p><strong>Name:</strong> <?= htmlspecialchars($row['name']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
                        <p><strong>Course & Year:</strong> <?= htmlspecialchars($row['course']) ?> - Year <?= htmlspecialchars($row['yearLevel']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
