<?php
include 'db_connect.php';

// Handle POST delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = intval($_POST['id']);
    $conn->query("DELETE FROM students WHERE id=$id");
    header("Location: select.php");
    exit;
}

// Default sorting
$sort = "id";
$order = "ASC";

// Check if sorting is requested
if (isset($_GET['sort'])) $sort = $_GET['sort'];
if (isset($_GET['order'])) $order = $_GET['order'];

// Toggle order for next click
$nextOrder = ($order == "ASC") ? "DESC" : "ASC";

// Fetch data with sorting
$result = $conn->query("SELECT * FROM students ORDER BY $sort $order");

// Function to add Bootstrap sort icons
function sortIcon($column, $sort, $order) {
    if ($column == $sort) return $order == "ASC" ? '<i class="bi bi-caret-up-fill"></i>' : '<i class="bi bi-caret-down-fill"></i>';
    return '<i class="bi bi-arrow-down-up"></i>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Management System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">
            <i class="bi bi-mortarboard-fill me-2"></i> Student Management System
        </span>
    </div>
</nav>

<!-- Main Container -->
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="card-title text-primary mb-1"><i class="bi bi-people-fill me-2"></i>Student Records</h2>
                            <p class="text-muted mb-0">Manage and organize student information</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="insert.php" class="btn btn-success btn-lg"><i class="bi bi-plus-circle me-2"></i>Add New Student</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 text-primary"><i class="bi bi-table me-2"></i>Student Database</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="studentsTable">
                            <thead class="table-dark">
                                <tr>
                                    <th><a href="?sort=id&order=<?php echo $nextOrder; ?>" class="text-white text-decoration-none">ID <span><?php echo sortIcon("id", $sort, $order); ?></span></a></th>
                                    <th><a href="?sort=idNumber&order=<?php echo $nextOrder; ?>" class="text-white text-decoration-none">ID Number <span><?php echo sortIcon("idNumber", $sort, $order); ?></span></a></th>
                                    <th><a href="?sort=name&order=<?php echo $nextOrder; ?>" class="text-white text-decoration-none">Name <span><?php echo sortIcon("name", $sort, $order); ?></span></a></th>
                                    <th>Age</th>
                                    <th><a href="?sort=email&order=<?php echo $nextOrder; ?>" class="text-white text-decoration-none">Email <span><?php echo sortIcon("email", $sort, $order); ?></span></a></th>
                                    <th>Contact Number</th>
                                    <th>Course</th>
                                    <th>Year Level</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $result->data_seek(0);
                            while($row = $result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td class="text-center"><span class="badge bg-primary rounded-pill">#<?php echo str_pad($row['id'],3,'0',STR_PAD_LEFT); ?></span></td>
                                    <td><strong class="text-primary"><?php echo htmlspecialchars($row['idNumber']); ?></strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" style="width:32px;height:32px;font-size:14px;color:white;">
                                                <?php echo strtoupper(substr($row['name'],0,1)); ?>
                                            </div>
                                            <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                                        </div>
                                    </td>
                                    <td class="text-center"><span class="badge bg-light text-dark"><?php echo htmlspecialchars($row['age']); ?> yrs</span></td>
                                    <td><a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="text-decoration-none"><i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($row['email']); ?></a></td>
                                    <td><i class="bi bi-telephone me-1 text-muted"></i><?php echo htmlspecialchars($row['contactNumber']); ?></td>
                                    <td><span class="badge bg-info text-white"><?php echo htmlspecialchars($row['course']); ?></span></td>
                                    <td class="text-center"><span class="badge bg-warning text-dark">Year <?php echo htmlspecialchars($row['yearLevel']); ?></span></td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-primary" title="Edit Student"><i class="bi bi-pencil"></i></a>
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?php echo $row['id']; ?>" data-name="<?php echo htmlspecialchars($row['name']); ?>"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i> Confirm Deletion</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p class="mb-0 fs-5">Are you sure you want to delete <strong id="studentName"></strong>’s record? This action <span class="text-danger fw-bold">cannot be undone</span>.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i> Cancel</button>
                                <form method="POST" id="deleteForm" class="d-inline">
                                    <input type="hidden" name="id" id="deleteStudentId">
                                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash-fill me-1"></i> Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Delete Modal -->
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const deleteModal = document.getElementById('deleteModal');
const studentNameEl = document.getElementById('studentName');
deleteModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const studentId = button.getAttribute('data-id');
    const studentName = button.getAttribute('data-name');
    studentNameEl.textContent = studentName;
    document.getElementById('deleteStudentId').value = studentId;
});
</script>
</body>
</html>
