<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';



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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js" integrity="sha512-r22gChDnGvBylk90+2e/ycr3RVrDi8DIOkIGNhJlKfuyQM4tIRAI062MaV8sfjQKYVGjOBaZBOA87z+IhZE9DA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
            <h1 class="h3 mb-0 text-gray-800">View Event Attendance</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">View Event Attendance</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">View Event Attendance</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Date<span class="text-danger ml-2">*</span></label>
                        <input type="date" class="form-control" name="dateTaken" id="exampleInputFirstName" placeholder="dd-mm-yyyy">
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Event Name<span class="text-danger ml-2">*</span></label>
                        <?php
                        $sql = "SELECT * FROM `tblevents`";
                        $result = $conn->query($sql);
                        echo "<select name='event' id='event' class='form-control'>";
                        while ($row = $result->fetch_assoc()) {
                          unset($id, $name);
                          $id = $row['id'];
                          $name = $row['event_name'];
                          echo '<option value="' . $id . '">' . $name . '</option>';
                        }
                        echo "</select>";
                        ?>
                      </div>
                    </div>
                    <button type="submit" name="view" class="btn btn-primary">View Attendance</button>

                  </form>
                </div>
              </div>

              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">Class Attendance</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Wing</th>
                            <th>Admission No</th>
                            <th>Class</th>
                            <th>Division</th>
                            <th>Status</th>
                            <th>Date</th>
                          </tr>
                        </thead>

                        <tbody>

                          <?php

                          if (isset($_POST['view'])) {

                            $dateTaken = $_POST['dateTaken'];
                            $event_id = $_POST['event'];
                            $query = "SELECT tblattendance.Id, tblattendance.status, DATE_FORMAT(tblattendance.dateTimeTaken, '%d-%m-%Y %h:%i %a') AS dateTimeTaken, tblclass.className, tblclassdivision.classArmName, tblsessionterm.sessionName, tblsessionterm.termId, tblterm.termName, tblstudents.firstName, tblstudents.lastName, tblstudents.Wing, tblstudents.admissionNumber 
                            FROM tblattendance 
                            INNER JOIN tblclass ON tblclass.Id = tblattendance.classId 
                            INNER JOIN tblclassdivision ON tblclassdivision.Id = tblattendance.classArmId 
                            INNER JOIN tblsessionterm ON tblsessionterm.Id = tblattendance.sessionTermId 
                            INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId 
                            INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo 
                            INNER JOIN tblevents ON tblevents.id = tblattendance.event_id 
                            WHERE tblevents.id = '$event_id' 
                            " . ((isset($_POST['dateTaken']) && $_POST["dateTaken"] != "") ? "tblattendance.dateTimeTaken BETWEEN '$dateTaken 00:00:00' AND '$dateTaken $event_id' AND " : '') . "
                            ORDER BY tblattendance.dateTimeTaken DESC";
                            $rs = $conn->query($query);
                            $num = $rs->num_rows;
                            $sn = 0;
                            $status = "";
                            if ($num > 0) {
                              while ($rows = $rs->fetch_assoc()) {
                                if ($rows['status'] == '1') {
                                  $status = "Present";
                                  $colour = "#00FF00";
                                } else {
                                  $status = "Absent";
                                  $colour = "#FF0000";
                                }
                                $sn = $sn + 1;
                                echo "
                              <tr>
                                <td>" . $sn . "</td>
                                 <td>" . $rows['firstName'] . "</td>
                                <td>" . $rows['lastName'] . "</td>
                                <td>" . $rows['Wing'] . "</td>
                                <td>" . $rows['admissionNumber'] . "</td>
                                <td>" . $rows['className'] . "</td>
                                <td>" . $rows['classArmName'] . "</td>
                                <td style='background-color:" . $colour . "'>" . $status . "</td>
                                <td>" . $rows['dateTimeTaken'] . "</td>
                              </tr>";
                              }
                            } else {
                              echo
                              "<div class='alert alert-danger' role='alert'>
                            No Record Found!
                            </div>";
                            }
                          }
                          ?>
                        </tbody>
                      </table>
                      <br>
                      <button name="view" class="btn btn-primary" onclick="download()">Download Report</button>
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

function download() {
  // Get the table element by its ID
var table = document.getElementById("dataTableHover");

// Create a new Workbook object
var workbook = XLSX.utils.book_new();

// Add a new worksheet to the workbook
var worksheet = XLSX.utils.table_to_sheet(table);
XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet1");

// Convert the workbook to a binary string
var wbout = XLSX.write(workbook, { bookType: "xlsx", type: "binary" });

// Create a Blob object from the binary string
var blob = new Blob([s2ab(wbout)], { type: "application/octet-stream" });

// Create a download link and trigger the download
var link = document.createElement("a");
link.href = URL.createObjectURL(blob);
link.download = `eventAttendance ${new Date().toLocaleDateString()}.xlsx`;
link.click();

// Helper function to convert string to ArrayBuffer
function s2ab(s) {
  var buf = new ArrayBuffer(s.length);
  var view = new Uint8Array(buf);
  for (var i=0; i<s.length; i++) {
    view[i] = s.charCodeAt(i) & 0xFF;
  }
  return buf;
}

}

      </script>
</body>

</html>