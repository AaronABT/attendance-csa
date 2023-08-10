<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

if (isset($_POST["save"])) {
    $activity = $_POST["activity"];
    $dateTaken = date('Y/m/d', time()) . ' ' . $_POST['timeTaken'] . ':00';
    $dateEnd = date('Y/m/d', time()) . ' ' . $_POST['timeTakenEnd'] . ':00';
    $volunteers = $_POST["final"];
    // GETTING TERM
    $query = mysqli_query($conn, "select * from tblsessionterm where isActive ='1'");
    $rows = mysqli_fetch_array($query);
    $sessionTermId = $rows['Id'];
    // END OF TERM
    // STARTING OF DATA COLLECTION
    $query = "SELECT admissionNumber, classId, classArmId FROM tblstudents";
    $result = mysqli_query($conn, $query);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $data = array();
    foreach ($rows as $row) {
        $data[$row['admissionNumber']] = $row;
    }
    $names = array();
    // PROCESSING ATTENDANCE
    for ($i = 0; $i < count($volunteers); $i++) {
        if ($data[(int) $volunteers[$i]] == null) {
            $names[] = $volunteers[$i];
            continue;
        }
        $query = "SELECT * FROM tblattendance WHERE admissionNo = '" . $volunteers[$i] . "' AND dateTimeTaken BETWEEN '$dateTaken' AND '$dateEnd'";
        $result = $conn->query($query);
        $count = $result->num_rows;
        if ($count > 0) {
            $names[] = $volunteers[$i];
            continue;
        }
        $query = "INSERT INTO `tblattendance` (`Id`, `admissionNo`, `classId`, `classArmId`, `sessionTermId`, `status`, `dateTimeTaken`, `dateTimeEnd`, `event_id`) VALUES (NULL, '" . $volunteers[$i] . "', '" . $data[(int) $volunteers[$i]]["classId"] . "', '" . $data[(int) $volunteers[$i]]["classArmId"] . "', '$sessionTermId', '1', '$dateTaken', '$dateEnd', '$activity') ";
        $conn->query($query);
    }
    header( "Content-Type: application/json");
    echo '{"status": "success","message": "Attendance successfully taken","names": "' . implode(", ", $names) . '"}';
    exit();
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
                        <h1 class="h3 mb-0 text-gray-800">Import Candidates from Google Forms</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Import Candidates from Google Forms</li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Form Basic -->
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Import Candidates from Google Forms</h6>
                                    <?php echo $statusMsg; ?>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row mb-3">
                                        <div class="col-xl-6">
                                            <label class="form-control-label">Activity<span class="text-danger ml-2">*</span></label>
                                            <?php
                                            $sql = "SELECT * FROM `tblevents`";
                                            $result = $conn->query($sql);
                                            echo "<select class='form-control' name='activity' id='activity'>";
                                            echo "<option selected disabled>Select Activity</option>";
                                            while ($row = mysqli_fetch_array($result)) {
                                                echo "<option value='" . $row['id'] . "'>" . $row['event_name'] . "</option>";
                                            }
                                            echo "</select>";
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <div class="col-xl-6">
                                            <label class="form-control-label">Start Time<span class="text-danger ml-2">*</span></label>
                                            <input type="time" class="form-control" name="timeTaken" id="timeTaken" placeholder="dd-mm-yyyy">
                                        </div>
                                        <div class="col-xl-6">
                                            <label class="form-control-label">End Time<span class="text-danger ml-2">*</span></label>
                                            <input type="time" class="form-control" name="timeTakenEnd" id="timeTakenEnd" placeholder="dd-mm-yyyy">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <div class="col-xl-6">
                                            <div class="buttons">
                                                <!--Add buttons to initiate auth sequence and sign out-->
                                                <button id="authorize_button" class="btn btn-primary" onclick="handleAuthClick()">Authorize</button>
                                                <button id="signout_button" class="btn btn-warning" onclick="handleSignoutClick()">Sign Out</button>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="select">
                                                <select id="forms_select" class="form-control">
                                                    <option selected disabled>Select a Google Form</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <div id="content_debug" class="box"></div>
                                        <div id="content_debug_error" class="box"></div>
                                    </div>
                                    <button name="view" class="btn btn-primary" id="load_candidate" onclick="loadFormToImportController()">Load Attendance</button>
                                </div>
                            </div>

                            <!-- Input Group -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card mb-4">
                                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                            <h6 class="m-0 font-weight-bold text-primary">Imported Volunteers</h6>
                                        </div>
                                        <div class="table-responsive p-3">
                                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>First Name</th>
                                                        <th>Last Name</th>
                                                        <th>Admission No</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="id_import">

                                                </tbody>
                                            </table>
                                            <br>
                                            <button name="view" class="btn btn-primary" id="load_candidate" onclick="importDataToDb()">Save Attendance</button>
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
            // $(document).ready(function() {
            //   $('#dataTable').DataTable(); // ID From dataTable 
            //   $('#dataTableHover').DataTable(); // ID From dataTable with Hover
            // });
            /* exported gapiLoaded */
            /* exported gisLoaded */
            /* exported handleAuthClick */
            /* exported handleSignoutClick */

            // TODO(developer): Set to client ID and API key from the Develo
            // TODO(developer): Set to client ID and API key from the Developer Console
            const CLIENT_ID = '893961761740-2tgbbo3v6i9n8e6ltb6dempbk1di6rqh.apps.googleusercontent.com';
            const API_KEY = 'AIzaSyCWJBEKHO_rBd2fpzL1bgomLAyfIc8XmIA';

            // Discovery doc URL for APIs used by the quickstart
            const DISCOVERY_DOC = 'https://www.googleapis.com/discovery/v1/apis/drive/v3/rest';

            // Authorization scopes required by the API; multiple scopes can be
            // included, separated by spaces.
            const SCOPES = 'https://www.googleapis.com/auth/drive.metadata.readonly https://www.googleapis.com/auth/forms.responses.readonly';

            let tokenClient;
            let gapiInited = false;
            let gisInited = false;

            document.getElementById('authorize_button').style.visibility = 'hidden';
            document.getElementById('signout_button').style.visibility = 'hidden';

            /**
             * Callback after api.js is loaded.
             */
            function gapiLoaded() {
                gapi.load('client', initializeGapiClient);
                gapi.load('forms', 'v1');
            }
            var accessToken

            /**
             * Callback after the API client is loaded. Loads the
             * discovery doc to initialize the API.
             */
            async function initializeGapiClient() {
                await gapi.client.init({
                    apiKey: API_KEY,
                    discoveryDocs: [DISCOVERY_DOC]
                });
                gapiInited = true;
                maybeEnableButtons();
            }

            /**
             * Callback after Google Identity Services are loaded.
             */
            function gisLoaded() {
                tokenClient = google.accounts.oauth2.initTokenClient({
                    client_id: CLIENT_ID,
                    scope: SCOPES,
                    callback: '', // defined later
                });
                gisInited = true;
                maybeEnableButtons();
            }

            /**
             * Enables user interaction after all libraries are loaded.
             */
            function maybeEnableButtons() {
                if (gapiInited && gisInited) {
                    document.getElementById('authorize_button').style.visibility = 'visible';
                }
            }

            /**
             *  Sign in the user upon button click.
             */
            function handleAuthClick() {
                tokenClient.callback = async (resp) => {
                    if (resp.error !== undefined) {
                        throw (resp);
                    }
                    document.getElementById('signout_button').style.visibility = 'visible';
                    document.getElementById('authorize_button').innerText = 'Refresh';
                    await listFiles();
                };

                if (gapi.client.getToken() === null) {
                    // Prompt the user to select a Google Account and ask for consent to share their data
                    // when establishing a new session.
                    tokenClient.requestAccessToken({
                        prompt: 'consent'
                    });
                } else { // Skip display of account chooser and consent dialog for an existing session.
                    tokenClient.requestAccessToken({
                        prompt: ''
                    });
                }
            }

            let formData;

            /**
             *  Sign out the user upon button click.
             */
            function handleSignoutClick() {
                const token = gapi.client.getToken();
                if (token !== null) {
                    google.accounts.oauth2.revoke(token.access_token);
                    gapi.client.setToken('');
                    document.getElementById('content_debug').innerText = '';
                    document.getElementById('authorize_button').innerText = 'Authorize';
                    document.getElementById('signout_button').style.visibility = 'hidden';
                }
            }

            async function listFiles() {
                // get the bearer token
                accessToken = gapi.client.getToken();
                let response;
                try {
                    response = await gapi.client.drive.files.list({
                        'q': "mimeType='application/vnd.google-apps.form'",
                        'pageSize': 50,
                        'fields': 'files(id, name, webViewLink)'
                    });
                } catch (err) {
                    document.getElementById('content_debug').innerText = err.message;
                    return;
                }
                const forms = response.result.files;
                if (!forms || forms.length == 0) {
                    document.getElementById('content_debug').innerText = 'No Google Forms found.';
                    return;
                }

                // Append options to select element
                const selectEl = document.getElementById('forms_select');
                console.log('forms', forms);
                forms.forEach((form) => {
                    const optionEl = document.createElement('option');
                    optionEl.textContent = form.name;
                    optionEl.value = form.id;
                    selectEl.appendChild(optionEl);
                });

                // Update content to indicate success
                document.getElementById('content_debug').innerText = 'Google Forms successfully loaded.';
                const select = document.getElementById('forms-select');
                let formId;

                selectEl.addEventListener('change', () => {
                    formId = selectEl.value;
                    document.getElementById('content_debug').innerText = 'Downloading Associated Google Form Responses';
                    //                   displayFormResponses(formId);
                    getFormResponses(formId, function(responses) {
                        console.log(responses);
                        formData = parseResponses(JSON.stringify(responses))
                        document.getElementById('content_debug').innerText = "All Data Loaded Successfully. Ready to Enter data";
                        // create a new table that shows the responses
                        let x = `<table class="table align-items-center table-flush table-hover"><tr><th>Type</th><th>Response</th></tr>`
                        for (let i in parseResponses(JSON.stringify(responses))[0]) {
                            x += `<tr><td>
                <select class="importHandler">
                  <option value="first_name">First Name</option>
                  <option value="last_name">Last Name</option>
                  <option value="full_name">Full Name</option>
                  <option value="adm_no">Admission No</option>
                  <option value="class">Class</option>
                  <option value="division">Division</option>
                  <option value="email">Email</option>
                  <option value="phone">Phone</option>
                  <option value="wing">Wing</option>
                  <option value="ignore">Other</option>
                </select>
              </td><td>${parseResponses(JSON.stringify(responses))[0][i]}</td></tr>`
                        }
                        document.getElementById('content_debug').innerHTML = x
                    });
                });
            }

            let importDataToDb = () => {
                let final = []
                for (let i in formData) {
                    if (formData[i]["adm_no"] != null && !isNaN(parseInt(formData[i]["adm_no"]))) {
                        final.push(parseInt(formData[i]["adm_no"]))
                    }
                }
                const activity = document.querySelector("#activity").value
                const timeTaken = document.querySelector("#timeTaken").value
                const timeTakenEnd = document.querySelector("#timeTakenEnd").value

                const data = {
                    save: true,
                    activity,
                    timeTaken,
                    timeTakenEnd,
                    final
                }
                console.log(data)

                $.post("./importGFormData.php", data, (res) => {
                    if (res.status == "success") {
                        if (res.names.length > 0) {
                            alert(`Data Imported Successfully. The following students attendance was not added due to attendance conflict: ${res.names}`)
                        } else {
                            alert("Data Imported Successfully.")
                        }
                    } else {
                        alert("Error Occured")
                    }
                })
            }


            let parseResponses = (responses) => {
                const parsedResponses = [];
                const responsesObj = JSON.parse(responses)["responses"];
                for (let i in Object.keys(responsesObj)) {
                    const response = responsesObj[Object.keys(responsesObj)[i]]["answers"];
                    let x = []
                    for (let j in Object.keys(response)) {
                        x.push(response[Object.keys(response)[j]]["textAnswers"]["answers"][0]["value"])
                    }
                    parsedResponses.push(x)
                }
                return parsedResponses
            }


            function getFormResponses(formId, callback) {
                var apiUrl = "https://forms.googleapis.com/v1/forms/" + formId + "/responses";
                fetch(apiUrl, {
                    headers: {
                        "Authorization": "Bearer " + accessToken['access_token'],
                        "Content-Type": "application/json"
                    }
                }).then(response => response.json()).then(data => {
                    callback(data);
                });
            }

            const changeFormat = (formatter, data) => {
                switch (formatter) {
                    case "first_name":
                        return data;
                        break;
                    case "last_name":
                        return data;
                        break;
                    case "full_name":
                        let name = [data.split(" ")[0], data.replace(`${data.split(" ")[0]} `, "")]
                        break;
                    case "adm_no":
                        return data;
                        break;
                    case "class":
                        return data;
                        break;
                    case "division":
                        return data;
                        break;
                    case "email":
                        return data;
                        break;
                    case "other":
                        return data;
                        break;
                    case "phone":
                        try {
                            // get the datatype of data
                            console.log(data)
                            if (typeof data == "number") {
                                return data
                            } else {
                                return parseInt(data)
                            }
                        } catch {
                            document.querySelector("#content_debug_error").innerHTML += "<br>A Contact number failed to pass test. Check Number and try again. (Number saved as 123456789)"
                            return 1234657890
                        }
                        break;
                    case "wing":
                        return findClosestMatch(data, ["Drishti", "Disha", "Media & Communication", "Activity Center"]);
                        break;
                    default:
                        break;
                }
            }

            let loadFormToImportController = () => {
                let val = []
                document.querySelectorAll(".importHandler").forEach((e) => {
                    val.push($(e).val())
                })
                if (new Set(val).size !== val.length) {
                    alert("Unique Constrain Failure. One or more fields have duplicate import option")
                    return
                }
                var jsonFormatted = []

                for (let i = 0; i < formData.length; i++) {
                    // get the index of a value in val
                    let x = {}
                    for (let j = 0; j < val.length; j++) {
                        if (val[j] == "other") continue;
                        x[val[j]] = changeFormat(val[j], formData[i][j])
                    }
                    jsonFormatted.push(x)
                }

                for (let i = 0; i < jsonFormatted.length; i++) {
                    html = `
          <tr>
          <td>${i+1}</td>
          <td>${jsonFormatted[i]["first_name"]}</td>
          <td>${jsonFormatted[i]["last_name"]}</td>
          <td>${jsonFormatted[i]["adm_no"]}</td>
          </tr>
        `
                    document.querySelector("#id_import").innerHTML += html;
                }
                formData = jsonFormatted;
            }


            // Algorithm Used to find the closest match to a string in an array
            function findClosestMatch(string, array) {
                var closestMatch = array[0];
                var closestMatchDistance = stringDistance(string, array[0]);
                for (var i = 1; i < array.length; i++) {
                    var distance = stringDistance(string, array[i]);
                    if (distance < closestMatchDistance) {
                        closestMatch = array[i];
                        closestMatchDistance = distance;
                    }
                }
                return closestMatch;
            }

            function stringDistance(string1, string2) {
                var distance = 0;
                var length = Math.max(string1.length, string2.length);
                for (var i = 0; i < length; i++) {
                    if (string1[i] != string2[i]) {
                        distance++;
                    }
                }
                return distance;
            }
        </script>
        <script async defer src="https://apis.google.com/js/api.js" onload="gapiLoaded()"></script>
        <script async defer src="https://accounts.google.com/gsi/client" onload="gisLoaded()"></script>
</body>

</html>