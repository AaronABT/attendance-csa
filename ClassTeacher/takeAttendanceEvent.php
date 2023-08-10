<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$query = "SELECT tblclass.className,tblclassdivision.classArmName 
    FROM tbleventhead
    INNER JOIN tblclass ON tblclass.Id = tbleventhead.classId
    INNER JOIN tblclassdivision ON tblclassdivision.Id = tbleventhead.classArmId
    Where tbleventhead.Id = '$_SESSION[userId]'";
$rs = $conn->query($query);
$num = $rs->num_rows;
$rrw = $rs->fetch_assoc();


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
            <h1 class="h3 mb-0 text-gray-800">Take Attendance (Today's Date : <?php echo $todaysDate = date("m-d-Y"); ?>)</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">All Student in Class</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <form method="post">
                <div class="form-group row mb-3">
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
                <button type="submit" name="view" class="btn btn-primary">Load Students</button>
              </form>
              <br>
              <br>

              <!-- Input Group -->
              <form method="post">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card mb-4">
                      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">All Student in (<?php echo $rrw['className'] . ' - ' . $rrw['classArmName']; ?>) Class</h6>
                        <h6 class="m-0 font-weight-bold text-danger">Note: <i>Click on the checkboxes besides each student to take attendance!</i></h6>
                      </div>
                      <div class="table-responsive p-3">
                        <div class="form-group row mb-3">
                          <div class="col-xl-6">
                            <label class="form-control-label">Select Start Time<span class="text-danger ml-2">*</span></label>
                            <input type="time" class="form-control" name="timeTaken" id="timeTaken" placeholder="dd-mm-yyyy">
                            <input disabled style="display: none;" type="number" class="form-control" name="event_id" id="event_id" value="<?php echo $_POST["event"] ?>">
                          </div>
                          <div class="col-xl-6">
                            <label class="form-control-label">Select End Time<span class="text-danger ml-2">*</span></label>
                            <input type="time" class="form-control" name="timeTakenEnd" id="timeTakenEnd" placeholder="dd-mm-yyyy">
                          </div>
                        </div>
                        <?php echo $statusMsg; ?>
                        <table class="table align-items-center table-flush table-hover">
                          <thead class="thead-light">
                            <tr>
                              <th>#</th>
                              <th>First Name</th>
                              <th>Last Name</th>
                              <th>Wing</th>
                              <th>Admission No</th>
                              <th>Class</th>
                              <th>Division</th>
                              <th>Mark</th>
                            </tr>
                          </thead>

                          <tbody>

                            <?php
                            if (isset($_POST["view"])) {
                              // Execute the query and fetch the result set
                              $events = mysqli_query($conn, "SELECT * FROM `tblevents`");
                              $eventsArray = array();
                              while ($row = mysqli_fetch_assoc($events)) {
                                $eventsArray[] = $row;
                              }
                              $query = "SELECT tblstudents.Id,tblstudents.classId as ClassIDDefiner,tblstudents.classArmId as DivisionIDDefiner,tblstudents.admissionNumber,tblclass.className,tblclass.Id As classId,tblclassdivision.classArmName,tblclassdivision.Id AS classArmId,tblstudents.firstName,
                              tblstudents.lastName,tblstudents.Wing,tblstudents.admissionNumber,tblstudents.dateCreated FROM `tbleventnames` INNER JOIN `tblstudents` ON `tbleventnames`.`student_id` = `tblstudents`.`admissionNumber` INNER JOIN `tblclass` ON `tblstudents`.`classId` = `tblclass`.`Id` INNER JOIN `tblclassdivision` ON `tblstudents`.`classArmId` = `tblclassdivision`.`id` WHERE `tbleventnames`.`event_id` = '" . $_POST['event'] . "'";
                              $rs = $conn->query($query);
                              $num = $rs->num_rows;
                              $sn = 0;
                              $status = "";
                              if ($num > 0) {
                                while ($rows = $rs->fetch_assoc()) {
                                  $sn = $sn + 1;
                                  echo "
                                  <tr>
                                  <td>" . $sn . "</td>
                                  <td>" . $rows['firstName'] . "</td>
                                  <td>" . $rows['lastName'] . "</td>
                                  <td>" . $rows['Wing'] . "</td>
                                  <td>" . $rows['admissionNumber'] . "</td>
                                  <td class='classID' data-rel=" . $rows["ClassIDDefiner"] . ">" . $rows['className'] . "</td>
                                  <td class='classArmID' data-rel=" . $rows["DivisionIDDefiner"] . ">" . $rows['classArmName'] . "</td>
                                  <td>
                                  <input name='check[]' type='checkbox' value=" . $rows['admissionNumber'] . " class='form-control'>
                                  </td>
                                </tr>";
                                  // echo "<input name='admissionNo[]' value=" . $rows['admissionNumber'] . " type='hidden' class='form-control'>";
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
                        <button type="submit" id="submit" name="save" <?php echo "value='" . $_POST['event'] . "'" ?> class="btn btn-primary">Take Attendance</button>
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

    $("#submit").click(function(e) {
      e.preventDefault();
      // check if the time is present and the selected end time is gretee than start time
      const end_time = document.querySelector("#timeTakenEnd").value;
      const start_time = document.querySelector("#timeTaken").value;
      if (end_time == "" || start_time == "") {
        alert("Please select the time");
        return;
      }
      if (end_time < start_time) {
        alert("End time cannot be less than start time");
        return;
      }
      var checked = $("input[type=checkbox]").length;
      var data = []
      var event_id = $("#event_id").val();
      for (var i = 0; i < checked; i++) {
        var classID = $(".classID").eq(i).attr("data-rel");
        var classArmID = $(".classArmID").eq(i).attr("data-rel");
        var admissionNumber = $("input[type=checkbox]").eq(i).val();
        data.push({
          event_id: event_id,
          start_time: start_time,
          end_time: end_time,
          classID: classID,
          classArmID: classArmID,
          admissionNumber: admissionNumber,
          isChecked: ($("input[type=checkbox]").eq(i).is(":checked")) ? 1 : 0
        })
      }
      console.log(data)
      // post this data to this page using submit response 
      $.ajax({
        url: "takeAttendance.php",
        type: "POST",
        data: {
          save: true,
          data: data
        },
        success: function(response) {
          if (response == "") {
            alert("No data to save");
          } else if (response["status"] == "success") {
            alert("Attendance saved successfully");
          } else {
            alert(response["message"]);
          }
        }
      });
    });
  </script>
</body>

</html>