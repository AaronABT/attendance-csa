<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
include '../mail.module.php';
//------------------------SAVE--------------------------------------------------

if (isset($_POST['save'])) {

  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $emailAddress = $_POST['emailAddress'];

  $phoneNo = $_POST['phoneNo'];
  $classId = $_POST['classId'];
  $classArmId = $_POST['classArmId'];
  $dateCreated = date("d-m-Y");

  $query = mysqli_query($conn, "select * from tbleventhead where emailAddress ='$emailAddress'");
  $ret = mysqli_fetch_array($query);

  if ($ret > 0) {

    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Email Address Already Exists!</div>";
  } else {
    $sampPass =  rand(100000, 999999);
    $sampPass_2 = md5($sampPass);
    $query = mysqli_query($conn, "INSERT into tbleventhead(firstName,lastName,emailAddress,password,phoneNo,classId,classArmId,dateCreated) 
    value('$firstName','$lastName','$emailAddress','$sampPass_2','$phoneNo','$classId','$classArmId','$dateCreated')");
    mailer($emailAddress, '$firstName $lastName', "CSA Account Created", "Your Password is $sampPass", "Your CSA Account has been created. Your Password is $sampPass. Please Login to your account to change your password.");
    if ($query) {

      $qu = mysqli_query($conn, "update tblclassdivision set isAssigned='1' where Id ='$classArmId'");
      if ($qu) {

        $statusMsg = "<div class='alert alert-success'  style='margin-right:700px;'>Created Successfully!</div>";
      } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
      }
    } else {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
    }
  }
}

//---------------------------------------EDIT-------------------------------------------------------------






//--------------------EDIT------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
  $Id = $_GET['Id'];
  $query = mysqli_query($conn, "select * from tbleventhead where Id ='$Id'");
  $row = mysqli_fetch_array($query);

  //------------UPDATE-----------------------------

  if (isset($_POST['update'])) {

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $emailAddress = $_POST['emailAddress'];

    $phoneNo = $_POST['phoneNo'];
    $classId = $_POST['classId'];
    $classArmId = $_POST['classArmId'];
    $dateCreated = date("d-m-Y");

    $query = mysqli_query($conn, "update tbleventhead set firstName='$firstName', lastName='$lastName',
    emailAddress='$emailAddress',phoneNo='$phoneNo', classId='$classId',classArmId='$classArmId'
    where Id='$Id'");
    if ($query) {

      echo "<script type = \"text/javascript\">
                window.location = (\"createWingHead.php\")
                </script>";
    } else {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
    }
  }
}

// update password
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "update_pass") {
  $sampPass =  rand(100000, 999999);
  $sampPass_2 = md5($sampPass);
  $Id = $_GET['Id'];
  $query = mysqli_query($conn, "update tbleventhead set password = '$sampPass_2' where Id='$Id'");
  if ($query) {
    $query = mysqli_query($conn, "select * from tbleventhead where Id ='$Id'");
    $row = mysqli_fetch_array($query);
    mailer($row['emailAddress'], $row['firstName'], "CSA Account Password Updated", "Your Password is $sampPass", "Your CSA Account password has been updated. Your New Password is $sampPass. Please Login to your account to change your password.");
    // echo "<script type = \"text/javascript\">
    //             window.location = (\"createWingHead.php\")
    //             </script>";
  } else {
    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
  }
}



//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['classArmId']) && isset($_GET['action']) && $_GET['action'] == "delete") {
  $Id = $_GET['Id'];
  $classArmId = $_GET['classArmId'];

  $query = mysqli_query($conn, "DELETE FROM tbleventhead WHERE Id='$Id'");

  if ($query == TRUE) {

    $qu = mysqli_query($conn, "update tblclassdivision set isAssigned='0' where Id ='$classArmId'");
    if ($qu) {

      echo "<script type = \"text/javascript\">
                window.location = (\"createWingHead.php\")
                </script>";
    } else {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
    }
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        xmlhttp.open("GET", "ajaxClassArms.php?cid=" + str, true);
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
            <h1 class="h3 mb-0 text-gray-800">Create Wing Heads</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Wing Heads</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Wing Heads</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Firstname<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="firstName" value="<?php echo $row['firstName']; ?>" id="exampleInputFirstName">
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Lastname<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="lastName" value="<?php echo $row['lastName']; ?>" id="exampleInputFirstName">
                      </div>
                    </div>
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Email Address<span class="text-danger ml-2">*</span></label>
                        <input type="email" class="form-control" required name="emailAddress" value="<?php echo $row['emailAddress']; ?>" id="exampleInputFirstName">
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Phone No<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="phoneNo" value="<?php echo $row['phoneNo']; ?>" id="exampleInputFirstName">
                      </div>
                    </div>
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Class<span class="text-danger ml-2">*</span></label>
                        <?php
                        $qry = "SELECT * FROM tblclass ORDER BY className ASC";
                        $result = $conn->query($qry);
                        $num = $result->num_rows;
                        if ($num > 0) {
                          echo ' <select required name="classId" onchange="classArmDropdown(this.value)" class="form-control mb-3">';
                          echo '<option value="">--Select Class--</option>';
                          while ($rows = $result->fetch_assoc()) {
                            echo '<option value="' . $rows['Id'] . '" >' . $rows['className'] . '</option>';
                          }
                          echo '</select>';
                        }
                        ?>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Division<span class="text-danger ml-2">*</span></label>
                        <?php
                        echo "<div id='txtHint'></div>";
                        ?>
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
                      <h6 class="m-0 font-weight-bold text-primary">All Wing Heads</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email Address</th>
                            <th>Phone No</th>
                            <th>Class</th>
                            <th>Division</th>
                            <th>Date Created</th>
                            <th>Settings</th>
                          </tr>
                        </thead>

                        <tbody>

                          <?php
                          $query = "SELECT tbleventhead.Id,tblclass.className,tblclassdivision.classArmName,tblclassdivision.Id AS classArmId,tbleventhead.firstName,
                      tbleventhead.lastName,tbleventhead.emailAddress,tbleventhead.phoneNo,tbleventhead.dateCreated
                      FROM tbleventhead
                      INNER JOIN tblclass ON tblclass.Id = tbleventhead.classId
                      INNER JOIN tblclassdivision ON tblclassdivision.Id = tbleventhead.classArmId";
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
                                <td>" . $rows['emailAddress'] . "</td>
                                <td>" . $rows['phoneNo'] . "</td>
                                <td>" . $rows['className'] . "</td>
                                <td>" . $rows['classArmName'] . "</td>
                                 <td>" . $rows['dateCreated'] . "</td>
                                <td>
                                <a href='?action=delete&Id=" . $rows['Id'] . "&classArmId=" . $rows['classArmId'] . "'><i class='fas fa-fw fa-trash'></i></a>
                                <a href='?action=edit&Id=" . $rows['Id'] . "'><i class='fas fa-fw fa-edit'></i></a>
                                <a href='?action=update_pass&Id=" . $rows['Id'] . "'><i class='fas fa-fw fa-edit'></i></a>
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
<script>
  <?php
  if (isset($sampPass)) {
    echo "Swal.fire({
            icon: 'success',
            title: 'Password',
            text: 'Your Password is: $sampPass',
            footer: '<a href>Please do not forget your password</a>'
        })";
  }
  ?>
</script>

</html>