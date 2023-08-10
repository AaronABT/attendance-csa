<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// GET THE CURRENT TERM
$query = "SELECT * FROM tblsessionterm WHERE isActive = 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result);
$term = $row['Id'];
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



  <script>
    function classArmDropdown(str) {
      if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
      } else {
        if (window.XMLHttpRequest) {
          // code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp = new XMLHttpRequest();
        } else {
          // code for IE6, IE5
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("txtHint").innerHTML = this.responseText;
          }
        };
        xmlhttp.open("GET", "ajaxClassArms2.php?cid=" + str, true);
        xmlhttp.send();
      }
    }
  </script>
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
            <h1 class="h3 mb-0 text-gray-800">Student Hours for Current Semester</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Student Hours</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="col-lg-12">
                <div class="form-group row mb-3">
                  <div class="col-xl-6">
                    <label class="form-control-label">Select Semester<span class="text-danger ml-2">*</span></label>
                    <form method="get">
                      <?php
                      if (isset($_GET['term']) && !empty($_GET['term'])) {
                        $term = $_GET['term'];
                      }
                      $query = "SELECT tblsessionterm.Id, tblsessionterm.sessionName, tblterm.termName 
                      FROM `tblsessionterm` 
                      INNER JOIN `tblterm` ON tblsessionterm.termId = tblterm.Id";
                      $result = mysqli_query($conn, $query);
                      echo "<select class='form-control' name='term' id='term' value='$term'>";
                      while ($row = mysqli_fetch_array($result)) {
                        $Id = $row['Id'];
                        $sessionName = $row['sessionName'];
                        $termName = $row['termName'];
                        echo "<option value='$Id'>$sessionName $termName</option>";
                      }
                      echo "</select>";

                      ?>
                      <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </form>
                  </div>
                </div>
                <div class="card mb-4">
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Hours Worked by Volunteers</h6>
                  </div>
                  <div class="table-responsive p-3">
                    <?php
                    $sql = "SELECT tblsessionterm.Id, tblsessionterm.sessionName, tblterm.termName FROM `tblsessionterm` INNER JOIN `tblterm` ON tblsessionterm.termId = tblterm.Id WHERE tblsessionterm.Id = $term; ";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_array($result);
                    $sessionName = $row['sessionName'];
                    $termName = $row['termName'];
                    echo "<h4 class='text-center'>Hours Worked by Volunteers for $sessionName $termName (ID: $term)</h4>";
                    ?>
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
                          <th>Hours</th>
                        </tr>
                      </thead>

                      <tbody>

                        <?php
                        if (isset($_GET['term']) && !empty($_GET['term'])) {
                          $term = $_GET['term'];
                        }
                        $query = "SELECT tblstudents.Id,tblclass.className,tblclassdivision.classArmName,tblclassdivision.Id AS classArmId,tblstudents.firstName,
                      tblstudents.lastName,tblstudents.Wing,tblstudents.admissionNumber,tblstudents.dateCreated, SUM(TIMESTAMPDIFF(MINUTE, dateTimeTaken, dateTimeEnd)) AS totalMinutes
                      FROM tblattendance 
                      INNER JOIN tblstudents ON tblattendance.admissionNo = tblstudents.admissionNumber
                      INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
                      INNER JOIN tblclassdivision ON tblclassdivision.Id = tblstudents.classArmId
                      WHERE tblattendance.sessionTermId = '$term'
                      GROUP BY admissionNo;";
                        $rs = $conn->query($query);
                        $num = $rs->num_rows;
                        $sn = 0;
                        $status = "";

                        if ($num > 0) {
                          while ($rows = $rs->fetch_assoc()) {
                            $hours = floor($rows['totalMinutes'] / 60);
                            $minutes = $rows['totalMinutes'] % 60;
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
                                <td>" . $hours . " hours " . $minutes . " minutes"  . "</td>
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
link.download = `studentHours ${new Date().toLocaleDateString()}.xlsx`;
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