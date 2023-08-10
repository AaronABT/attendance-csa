<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

if (isset($_POST['save'])) {
  $event = $_POST['event_name'];
  $check = $_POST['check'];
  $count = count($check);
  
  for ($i = 0 ; $i < $count ; $i++) {
    try {
      //code...
      $sql = "INSERT INTO `tbleventnames`(`student_id`, `event_id`) VALUES ('$check[$i]','$event')";
      $conn->query($sql);
    } catch (\Throwable $th) {
      //throw $th;
      echo "<script>alert('Error: " . $th . "');</script>";
    }
  }

  echo "<script>alert('Students mapped to event successfully');</script>";
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
  <title>Dashboard</title>
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
            <h1 class="h3 mb-0 text-gray-800">Map Student to Event</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Map Student to Event</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">

              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">Map Students to Events</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <form action="" method="post">
                        <div class="form-group row mb-3">
                          <div class="col-xl-6">
                            <label class="form-control-label">Select Event<span class="text-danger ml-2">*</span></label>
                            <?php
                            $qry = "SELECT * FROM tblevents";
                            $result = $conn->query($qry);
                            $num = $result->num_rows;
                            if ($num > 0) {
                              echo ' <select required name="event_name" class="form-control mb-3">';
                              echo '<option value="">--Select Event--</option>';
                              while ($rows = $result->fetch_assoc()) {
                                echo '<option value="' . $rows['id'] . '" >' . $rows['event_name'] . '</option>';
                              }
                              echo '</select>';
                            }
                            ?>
                          </div>
                        </div>
                        <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                          <thead class="thead-light">
                            <tr>
                              <th>#</th>
                              <th>First Name</th>
                              <th>Last Name</th>
                              <th>Admission No</th>
                              <th>Class</th>
                              <th>Division</th>
                              <th>Map</th>
                            </tr>
                          </thead>

                          <tbody>

                            <?php
                            $query = "SELECT * FROM `tblstudents` INNER JOIN tblclass ON tblstudents.classId = tblclass.Id LEFT JOIN tblclassdivision ON tblstudents.classArmId = tblclassdivision.id;";
                            $rs = $conn->query($query);
                            $num = $rs->num_rows;
                            $sn = 0;
                            if ($num > 0) {
                              while ($rows = $rs->fetch_assoc()) {
                                $sn = $sn + 1;
                                echo "
                              <tr>
                                <td>" . $sn . "</td>
                                 <td>" . $rows['firstName'] . "</td>
                                <td>" . $rows['lastName'] . "</td>
                                <td>" . $rows['admissionNumber'] . "</td>
                                <td>" . $rows['className'] . "</td>
                                <td>" . $rows['classArmName'] . "</td>
                                <td>
                                <input name='check[]' type='checkbox' value=" . $rows['admissionNumber'] . " class='form-control'>
                                </td>
                              </tr>";
                              }
                            } else {
                              echo
                              "<div class='alert alert-danger' role='alert'>
                            No Record Found!
                            </div>";
                            }
                            ?>
                          </tbody>
                        </table>
                        <button type="submit" name="save" class="btn btn-primary">Take Attendance</button>
                      </form>
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
      $(document).ready(function() {
        $('#dataTable').DataTable(); // ID From dataTable 
        $('#dataTableHover').DataTable(); // ID From dataTable with Hover
      });
    </script>
</body>

</html>