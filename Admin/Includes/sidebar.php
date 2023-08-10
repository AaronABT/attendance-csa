 <ul class="navbar-nav sidebar sidebar-light accordion " id="accordionSidebar">
      <a class="sidebar-brand d-flex align-items-center bg-gradient-primary  justify-content-center" href="index.php">
        <div class="sidebar-brand-icon" >
          <img src="img/logo/csa logo.png">
        </div>
        <div class="sidebar-brand-text mx-3">CSA</div>
      </a>
      <hr class="sidebar-divider my-0">
      <li class="nav-item active">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      
  <hr class="sidebar-divider">
    <div class="sidebar-heading">
      Search
    </div>
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapcon8"
      aria-expanded="true" aria-controls="collapseBootstrapcon3">
      <i class="fa fa-calendar-alt"></i>
      <span>Search Data</span>
    </a>
    <div id="collapseBootstrapcon8" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Search & View Data</h6>
        <a class="collapse-item" href="viewAttendance.php">View Event Attendance</a>
        <a class="collapse-item" href="viewStudents.php">View Students</a>
        <a class="collapse-item" href="viewStudentAttendance.php">View Student Attendance</a>
        <a class="collapse-item" href="viewStudentsHours.php">View Student Hours</a>
        <!-- <a class="collapse-item" href="takeAttendance.php">Add Candidates</a>
            <a class="collapse-item" href="addMemberToContLevel.php ">Add Member to Level</a> -->
      </div>
    </div>
  </li>

      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Class and Divisions
      </div>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap"
          aria-expanded="true" aria-controls="collapseBootstrap">
          <i class="fas fa-chalkboard"></i>
          <span>Manage Classes</span>
        </a>
        <div id="collapseBootstrap" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage Classes</h6>
            <a class="collapse-item" href="createClass.php">Create Class</a>
          </div>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapusers"
          aria-expanded="true" aria-controls="collapseBootstrapusers">
          <i class="fas fa-code-branch"></i>
          <span>Manage Divisions</span>
        </a>
        <div id="collapseBootstrapusers" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage Divisions</h6>
            <a class="collapse-item" href="createDivisions.php">Create Divisions</a>
            <!-- <a class="collapse-item" href="usersList.php">User List</a> -->
          </div>
        </div>
      </li>
       <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Wing Heads
      </div>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapassests"
          aria-expanded="true" aria-controls="collapseBootstrapassests">
          <i class="fas fa-chalkboard-teacher"></i>
          <span>Manage Wing Heads</span>
        </a>
        <div id="collapseBootstrapassests" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage Wing Heads</h6>
             <a class="collapse-item" href="createWingHead.php">Create Wing Heads</a>
          </div>
        </div>
      </li>
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Students
      </div>
      </li>
       <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap2"
          aria-expanded="true" aria-controls="collapseBootstrap2">
          <i class="fas fa-user-graduate"></i>
          <span>Manage Students</span>
        </a>
        <div id="collapseBootstrap2" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage Students</h6>
            <a class="collapse-item" href="createStudents.php">Create Students</a>
            <!-- <a class="collapse-item" href="#">Assets Type</a> -->
          </div>
        </div>
      </li>

      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Events
      </div>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapevent"
          aria-expanded="true" aria-controls="collapseBootstrapevent">
          <i class="fas fa-chalkboard-teacher"></i>
          <span>Manage Events</span>
        </a>
        <div id="collapseBootstrapevent" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage Event Details</h6>
             <a class="collapse-item" href="createEvents.php">Create Events</a>
          </div>
        </div>
      </li>
      
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
       Year & Semester
      </div>
      </li>
       <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapcon"
          aria-expanded="true" aria-controls="collapseBootstrapcon">
          <i class="fa fa-calendar-alt"></i>
          <span>Manage Year & Semester</span>
        </a>
        <div id="collapseBootstrapcon" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Contribution</h6>
            <a class="collapse-item" href="createSessionTerm.php">Create Year and Semester</a>
          </div>
        </div>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" href="forms.html">
          <i class="fab fa-fw fa-wpforms"></i>
          <span>Forms</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTable" aria-expanded="true"
          aria-controls="collapseTable">
          <i class="fas fa-fw fa-table"></i>
          <span>Tables</span>
        </a>
        <div id="collapseTable" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Tables</h6>
            <a class="collapse-item" href="simple-tables.html">Simple Tables</a>
            <a class="collapse-item" href="datatables.html">DataTables</a>
          </div>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="ui-colors.html">
          <i class="fas fa-fw fa-palette"></i>
          <span>UI Colors</span>
        </a>
      </li>
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Examples
      </div>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePage" aria-expanded="true"
          aria-controls="collapsePage">
          <i class="fas fa-fw fa-columns"></i>
          <span>Pages</span>
        </a>
        <div id="collapsePage" class="collapse" aria-labelledby="headingPage" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Example Pages</h6>
            <a class="collapse-item" href="login.html">Login</a>
            <a class="collapse-item" href="register.html">Register</a>
            <a class="collapse-item" href="404.html">404 Page</a>
            <a class="collapse-item" href="blank.html">Blank Page</a>
          </div>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="charts.html">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Charts</span>
        </a>
      </li> -->
      <hr class="sidebar-divider">
      <div class="version" id="version-ruangadmin"></div>
    </ul>