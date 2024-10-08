<?php  
error_reporting(E_ALL);
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
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">

  <style>
    .excel-table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        font-family: Arial, sans-serif;
    }

    .excel-table th, .excel-table td {
        border: 1px solid #4d4d4d;
        padding: 8px;
        text-align: left;
    }

    .excel-table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    .excel-table tr:nth-child(even) {
        background-color: #e6ffe6; /* Light green for alternate rows */
    }

    .excel-table tr:hover {
        background-color: #d6f5d6; /* Slight hover effect */
    }

    .excel-table td {
        background-color: #ffffff;
    }

    .excel-table th, .excel-table td {
        font-size: 14px;
    }
</style>


</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include "Includes/sidebar.php";?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include "Includes/topbar.php";?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">View Class Attendance</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">View Class Attendance</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">View Class Attendance</h6>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Date<span class="text-danger ml-2">*</span></label>
                        <input type="date" class="form-control" name="dateTaken" required>
                      </div>
                    </div>
                    <button type="submit" name="view" class="btn btn-primary">View Attendance</button>
                  </form>
                </div>
              </div>

              <!-- Attendance Table -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">Class Attendance</h6>
                    </div>
                    <div class="table-responsive p-3">
                    <table class="excel-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Class</th>
                            <th>Session</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php 
                            if (isset($_POST['view'])) { 
                                $dateTaken = $_POST['dateTaken'];

                                // Query to fetch attendance records based on the selected date
                                $query = "SELECT tblattendance.Id, tblattendance.status, tblattendance.date, 
                                        tblclass.className, tblclassarms.classArmName, 
                                        tblstudents.firstName, tblstudents.lastName
                                        FROM tblattendance 
                                        INNER JOIN tblstudents ON tblstudents.Id = tblattendance.studentId 
                                        INNER JOIN tblclassarms ON tblclassarms.Id = tblattendance.classArmId
                                        INNER JOIN tblclass ON tblclass.Id = tblclassarms.classId
                                        WHERE tblattendance.date = '$dateTaken'";

                                $rs = $conn->query($query);
                                $sn = 0;

                                if ($rs && $rs->num_rows > 0) { 
                                    while ($rows = $rs->fetch_assoc()) {
                                        $status = ($rows['status'] == 'present') ? "Present" : "Absent";
                                        $sn++;

                                        echo "
                                            <tr>
                                                <td>".$sn."</td>
                                                <td>".$rows['firstName']."</td>
                                                <td>".$rows['lastName']."</td>
                                                <td>".$rows['className']."</td>
                                                <td>".$rows['classArmName']."</td>
                                                <td>".$status."</td>
                                                <td>".$rows['date']."</td>
                                            </tr>";
                                    }
                                } else {
                                    echo "<div class='alert alert-danger' role='alert'>No Record Found!</div>";
                                }
                            }
                        ?>
                        </tbody>
                    </table>
                    </div>
                  </div>

                  <!-- Download Button -->
                  <?php if (isset($rs) && $rs->num_rows > 0) { ?>
                    <div class="text-center">
                        <form method="post" action="download.php">
                        <input type="hidden" name="dateTaken" value="<?php echo $dateTaken; ?>">
                        <button type="submit" class="btn btn-success">Download Attendance as Excel</button>
                        </form>
                    </div> 
                    <?php } ?> </br></br> 
                </div>
              </div>
            </div>
          </div>
          <!--Row-->
        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
      <?php include "Includes/footer.php";?>
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
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>
</html>
