<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if (isset($_POST['save'])) {
  $classId = $_POST['classId'];
  $classArmName = $_POST['classArmName'];

  // Check if class arm already exists
  $stmt = $conn_pdo->prepare("SELECT * FROM tblclassdivision WHERE classArmName = :classArmName AND classId = :classId");
  $stmt->execute(array(':classArmName' => $classArmName, ':classId' => $classId));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Class Arm Already Exists!</div>";
  } else {
    // Insert new class arm
    $stmt = $conn_pdo->prepare("INSERT INTO tblclassdivision (classId, classArmName, isAssigned) VALUES (:classId, :classArmName, '0')");
    $stmt->execute(array(':classId' => $classId, ':classArmName' => $classArmName));

    if ($stmt) {
      $statusMsg = "<div class='alert alert-success'  style='margin-right:700px;'>Created Successfully!</div>";
    } else {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
    }
  }
}

// --------------------- EDIT CLASS ARM --------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
  $Id = $_GET['Id'];

  // Fetch class arm to edit
  $stmt = $conn_pdo->prepare("SELECT * FROM tblclassdivision WHERE Id = :Id");
  $stmt->execute(array(':Id' => $Id));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    // Update class arm
    if (isset($_POST['update'])) {
      $classId = $_POST['classId'];
      $classArmName = $_POST['classArmName'];

      $stmt = $conn_pdo->prepare("UPDATE tblclassdivision SET classId = :classId, classArmName = :classArmName WHERE Id = :Id");
      $stmt->execute(array(':classId' => $classId, ':classArmName' => $classArmName, ':Id' => $Id));

      if ($stmt) {
        echo "<script type='text/javascript'>window.location = 'createDivisions.php';</script>";
      } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
      }
    }
  }
}

// --------------------- DELETE CLASS ARM --------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
  $Id = $_GET['Id'];

  // Delete class arm
  $stmt = $conn_pdo->prepare("DELETE FROM tblclassdivision WHERE Id = :Id");
  $stmt->execute(array(':Id' => $Id));

  if ($stmt) {
    echo "<script type='text/javascript'>window.location = 'createDivisions.php';</script>";
  } else {
    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/attnlg.jpg" rel="icon">
  <?php include 'includes/title.php'; ?>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include "Includes/sidebar.php"; ?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include "Includes/topbar.php"; ?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create Class Arms</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Class Arms</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Class Arms</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Class<span class="text-danger ml-2">*</span></label>
                        <?php
                        $qry = "SELECT * FROM tblclass ORDER BY className ASC";
                        $stmt = $conn_pdo->prepare($qry);
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $num = count($result);
                        if ($num > 0) {
                          echo '<select required name="classId" class="form-control mb-3">';
                          echo '<option value="">--Select Class--</option>';
                          foreach ($result as $rows) {
                            echo '<option value="' . $rows['Id'] . '" >' . $rows['className'] . '</option>';
                          }
                          echo '</select>';
                        }
                        ?>

                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Class Division<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="classArmName"
                          value="<?php echo $row['classArmName']; ?>" id="exampleInputFirstName"
                          placeholder="Class Division">
                      </div>
                    </div>
                    <?php
                    if (isset($Id)) {
                      ?>
                      <button type="submit" name="update" class="btn btn-warning">Update</button>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <?php
                    } else {
                      ?>
                      <button type="submit" name="save" class="btn btn-primary">Save</button>
                      <?php
                    }
                    ?>
                  </form>
                </div>
              </div>

              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">All Class Arm</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Class Name</th>
                            <th>Class Division</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>Delete</th>
                          </tr>
                        </thead>

                        <tbody>

                          <?php
                          $query = "SELECT tblclassdivision.Id,tblclassdivision.isAssigned,tblclass.className,tblclassdivision.classArmName 
                          FROM tblclassdivision
                          INNER JOIN tblclass ON tblclass.Id = tblclassdivision.classId ORDER BY tblclass.className ASC, tblclassdivision.classArmName ASC";
                          $stmt = $conn_pdo->prepare($query);
                          $stmt->execute();
                          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                          $num = count($results);
                          $sn = 0;
                          $status = "";
                          if ($num > 0) {
                            foreach ($results as $rows) {
                              if ($rows['isAssigned'] == '1') {
                                $status = "Assigned";
                              } else {
                                $status = "Unassigned";
                              }
                              $sn = $sn + 1;
                              echo "
                    <tr>
                      <td>" . $sn . "</td>
                      <td>" . $rows['className'] . "</td>
                      <td>" . $rows['classArmName'] . "</td>
                      <td>" . $status . "</td>
                      <td><a href='?action=edit&Id=" . $rows['Id'] . "'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                      <td><a href='?action=delete&Id=" . $rows['Id'] . "'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                    </tr>";
                            }
                          } else {
                            echo "<div class='alert alert-danger' role='alert'>No Record Found!</div>";
                          }


                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!--Row-->

            <!-- Documentation Link -->
            <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>For more documentations you can visit<a href="https://getbootstrap.com/docs/4.3/components/forms/"
                  target="_blank">
                  bootstrap forms documentations.</a> and <a
                  href="https://getbootstrap.com/docs/4.3/components/input-group/" target="_blank">bootstrap input
                  groups documentations</a></p>
            </div>
          </div> -->

          </div>
          <!---Container Fluid-->
        </div>
        <!-- Footer -->
        <?php include "Includes/footer.php"; ?>
        <!-- Footer -->
      </div>
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
      $(document).ready(function () {
        $('#dataTable').DataTable(); // ID From dataTable 
        $('#dataTableHover').DataTable(); // ID From dataTable with Hover
      });
    </script>
</body>

</html>