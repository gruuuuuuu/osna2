<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:20 GMT -->
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>Add Patient</title>
		
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/opd.png">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
		
		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
		
		<!-- Feathericon CSS -->
        <link rel="stylesheet" href="assets/css/feathericon.min.css">
		
		<link rel="stylesheet" href="assets/plugins/morris/morris.css">
		
		<!-- Main CSS -->
        <link rel="stylesheet" href="assets/css/style.css">
		
		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->

		<?php
  session_start();
  include 'db.php'; 
  ?>

		
		  
<?php
		if (!isset($_SESSION['username'])) {
			
			header("Location: login.php");
			exit();
		}
		
		$username = $_SESSION['username'];
		
					
			// Fetch clerk information
$username = $_SESSION['username'];
$sql = "SELECT clerk_name, clerk_image FROM clerk_log WHERE username = '$username'";
$result = mysqli_query($con, $sql);

if ($result) {
    $clerk = mysqli_fetch_assoc($result);
    if ($clerk) {
        $name = $clerk['clerk_name'];
        $image = $clerk['clerk_image'];
    } else {
        $name = "Unknown";
        $image = "default.png"; // Fallback image
    }
} else {
    $name = "Unknown";
    $image = "default.png"; // Fallback image
}
?>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['step1'])) {
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['password'] = $_POST['password'];
        $_SESSION['step'] = 2;
    } elseif (isset($_POST['step2'])) {
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['guardian'] = $_POST['guardian'];
        $_SESSION['address'] = $_POST['address'];
        $_SESSION['contactnum'] = $_POST['contactnum'];
        $_SESSION['age'] = $_POST['age'];
        $_SESSION['sex'] = $_POST['sex'];
        $_SESSION['civil_status'] = $_POST['civil_status'];
        $_SESSION['dob'] = $_POST['dob'];
        $_SESSION['step'] = 3;
    } elseif (isset($_POST['step3'])) {
        $_SESSION['weight'] = $_POST['weight'];
        $_SESSION['height'] = $_POST['height'];
        $_SESSION['bloodpressure'] = $_POST['bloodpressure'];
        $_SESSION['heartrate'] = $_POST['heartrate'];
        $_SESSION['step'] = 4;
    } elseif (isset($_POST['submit'])) {
        // Database connection
        $conn = new mysqli('localhost', 'root', '', 'opdmsis');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $username = $_SESSION['username'];
        $password = $_SESSION['password'];
        $name = $_SESSION['name'];
        $guardian = $_SESSION['guardian'];
        $address = $_SESSION['address'];
        $contactnum = $_SESSION['contactnum'];
        $age = $_SESSION['age'];
        $sex = $_SESSION['sex'];
        $civil_status = $_SESSION['civil_status'];
        $dob = $_SESSION['dob'];
        $weight = $_SESSION['weight'];
        $height = $_SESSION['height'];
        $bloodpressure = $_SESSION['bloodpressure'];
        $heartrate = $_SESSION['heartrate'];

        // Check if the username already exists
        $stmt = $conn->prepare("SELECT username FROM patient_info WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Username already exists
            echo "<script>alert('Username already exists. Please choose a different username.');</script>";
        } else {
            // Insert new record
            $sql = $conn->prepare("INSERT INTO patient_info (username, password, name, guardian, address, contactnum, age, sex, civil_status, dob, weight, height, bloodpressure, heartrate)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $sql->bind_param("ssssssisssssss", $username, $password, $name, $guardian, $address, $contactnum, $age, $sex, $civil_status, $dob, $weight, $height, $bloodpressure, $heartrate);

            if ($sql->execute()) {
                echo "<script>alert('New Patient added successfully');</script>";
                // Optionally, redirect to another page
                // header('Location: success_page.php');
            } else {
                echo "<script>alert('Error adding patient. Please try again.');</script>";
            }
            $sql->close();
        }

        $stmt->close();
        $conn->close();
    }
} else {
    $_SESSION['step'] = 1;
}
?>


    </head>
    <body>
	
		<!-- Main Wrapper -->
        <div class="main-wrapper">
		
			<!-- Header -->
            <div class="header">
			
				<!-- Logo -->
                <div class="header-left">
                    <a href="" class="logo">
						<img src="assets/img/opd.png" alt="Logo">
					</a>
					<a href="" class="logo logo-small">
						<img src="assets/img/opd.png" alt="Logo" width="30" height="30">
					</a>
                </div>
				<!-- /Logo -->
				
				<a href="javascript:void(0);" id="toggle_btn">
					<i class="fe fe-text-align-left"></i>
				</a>
				
				<div class="top-nav-search">
					
				</div>
				
				<!-- Mobile Menu Toggle -->
				<a class="mobile_btn" id="mobile_btn">
					<i class="fa fa-bars"></i>
				</a>
				<!-- /Mobile Menu Toggle -->
				
				<!-- Header Right Menu -->
				<ul class="nav user-menu">

				
					
										<!-- User Menu -->
                                        <li class="nav-item dropdown has-arrow">
						<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
							<span class="user-img"><img class="rounded-circle" src="../clerk/Images/<?php echo htmlspecialchars($image); ?>" width="31" alt="admin"></span>
						</a>
						<div class="dropdown-menu">
							<div class="user-header">
								<div class="avatar avatar-sm">
                                <span class="user-img"><img class="rounded-circle" src="../clerk/Images/<?php echo htmlspecialchars($image); ?>" width="31" alt="admin"></span>
								</div>
								<div class="user-text">
								<h6><?php echo $username; ?></h6>
									<p class="text-muted mb-0"><?php echo $name; ?></p>
								</div>
							</div>
							<a class="dropdown-item" href="profile.php">My Profile</a>
							<a class="dropdown-item" href="settings.php">Settings</a>
							<a class="dropdown-item" href="../clerk/login.php">Logout</a>
						</div>
					</li>
					<!-- /User Menu -->
					
				</ul>
				<!-- /Header Right Menu -->
				
            </div>
			<!-- /Header -->
			
			<!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <div class="sidebar-inner slimscroll">
					<div id="sidebar-menu" class="sidebar-menu">
						<ul>
							<li class="menu-title"> 
								<span>Main</span>
							</li>
							<li class="active"> 
								<a href="clerk_dash.php"><i class="fe fe-home"></i> <span>Dashboard</span></a>
							</li>
							<li class="submenu">
								<a href="#"><i class="fa fa-wheelchair"></i> <span>Manage Patient</span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
									
									<li><a href="add_patient.php">Add Patient</a></li>
									<li><a href="prediction.php">Predict Sickness</a></li>
									<li><a href="view_patient.php">View Patient</a></li>
								</ul>
								<li class="submenu">
								<a href="#"><i class="fa fa-user-md"></i> <span>Doctor</span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
									
									<li><a href="pending-report.php">Pending Reports</a></li>
									
								</ul>
	
							<li> 
								<a href="settings.php"><i class="fe fe-vector"></i> <span>Settings</span></a>
							</li>
							<li class="submenu">
								<a href="#"><i class="fe fe-document"></i> <span> Reports</span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
									<li><a href="pending-report.php">Pending Reports</a></li>
								</ul>
							</li>
							<li class="menu-title"> 
								<span>Pages</span>
							</li>
							<li> 
								<a href="profile.php"><i class="fe fe-user-plus"></i> <span>Profile</span></a>
							</li>
					</div>
                </div>
            </div>
			<!-- /Sidebar -->
			
			<!-- Page Wrapper -->
            <div class="page-wrapper">
			
                <div class="content container-fluid">
					
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-sm-12">
								<h3 class="page-title">Welcome, <?php echo $name; ?></h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item active">Dashboard</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /Page Header -->
                    <div class="container">
    <div class="steps">
        <div class="step <?php if ($_SESSION['step'] == 1) echo 'active'; ?>">
            <span class="number">1</span>
            <span class="check-icon">&#10004;</span>
        </div>---------
        <div class="step <?php if ($_SESSION['step'] == 2) echo 'active'; ?>">
            <span class="number">2</span>
            <span class="check-icon">&#10004;</span>
        </div>---------
        <div class="step <?php if ($_SESSION['step'] == 3) echo 'active'; ?>">
            <span class="number">3</span>
            <span class="check-icon">&#10004;</span>
        </div>---------
        <div class="step <?php if ($_SESSION['step'] == 4) echo 'active'; ?>">
            <span class="number">4</span>
            <span class="check-icon">&#10004;</span>
        </div>
    </div>

    <?php if ($_SESSION['step'] == 1): ?>
        <h5 style="text-align: center; font-weight: bold;">Patient username and Password</h5><br><br>
        <form action="" method="post" id="step1-form">
            <div class="form-group row ">
                <label for="username">USERNAME &nbsp; &nbsp;</label>
                <input type="text" class="form-control form-control-small" id="username" name="username" placeholder="Patient Username" style="align-items: center;" required>
            </div>
            <div class="form-group row ">
                <label for="password">PASSWORD &nbsp; &nbsp;</label>
                <input type="password" class="form-control form-control-small" id="password" name="password" placeholder="Patient Password"  required>
            </div>
            <button type="submit" name="step1" class="btn btn-primary" onclick="completeStep(1)">Next</button>
        </form>
        <div class="note mt-3 text-center">
                        <p><strong style="color: red; font-weight: bold;">Note: </strong>The Clerk need to add patient username and password to access patient data!</p>
                    </div>
    <?php elseif ($_SESSION['step'] == 2): ?>
        <h2 style="text-align: center; font-weight: bold;">Patient Personal Information</h2><br><br>
        <form action="" method="post" id="step2-form">
            <div class="form-group row">
            <div class="form-group col-md-3">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group col-md-3">
                <label for="guardian">Guardian:</label>
                <input type="text" class="form-control" id="guardian" name="guardian" required>
            </div>
            <div class="form-group col-md-3">
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="form-group col-md-3">
                <label for="contactnum">Contact Number:</label>
                <input type="text" class="form-control" id="contactnum" name="contactnum" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="age">Age:</label>
                <input type="number" class="form-control" id="age" name="age" required>
            </div>
            <div class="form-group col-md-3">
                <label for="sex">Sex:</label>
                <select class="form-control" id="sex" name="sex" required>
                    <option value="">Select Sex</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="civil_status">Civil Status</label>
                <select class="form-control" id="sex" name="civil_status" required>
                    <option value="">Select Status</option>
                    <option value="single">Single</option>
                    <option value="married">Married</option>
                    <option value="divorced">Divorced</option>
                    <option value="widowed">Widowed</option>

                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="dob">Date of Birth:</label>
                <input type="date" class="form-control" id="dob" name="dob" required>
            </div>
            <a href="?step=1" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
            <button type="submit" name="step2" class="btn btn-primary" onclick="completeStep(2)">Next</button>
            
        </form>
    <?php elseif ($_SESSION['step'] == 3): ?>
        <h2 style="text-align: center; font-weight: bold;">Patient Health Information</h2><br><br>
        <form action="" method="post" id="step3-form">
        <div class="form-group row">
            <div class="form-group col-md-3">
                <label for="weightt">Weight</label>
                <input type="text" class="form-control" id="name" name="weight" required>
            </div>
            <div class="form-group col-md-3">
                <label for="height">Height</label>
                <input type="text" class="form-control" id="guardian" name="height" required>
            </div>
            <div class="form-group col-md-3">
                <label for="bloodpressure">Blood Pressure</label>
                <input type="text" class="form-control" id="address" name="bloodpressure" required>
            </div>
            <div class="form-group col-md-3">
                <label for="heartrate">Heart Rate</label>
                <input type="text" class="form-control" id="contactnum" name="heartrate" required>
            </div>
        </div>
            <a href="?step=2" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
            <button type="submit" name="step3" class="btn btn-primary" onclick="completeStep(3)">Next</button>
            
        </form>
    <?php elseif ($_SESSION['step'] == 4): ?>
        <h2 style="text-align: center; font-weight: bold;">Review Patient Data</h2><br><br>
    <form action="" method="post" id="step4-form">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Name</th>
                                    <th>Guardian</th>
                                    <th>Address</th>
                                    <th>Contact Number</th>
                                    <th>Age</th>
                                    <th>Sex</th>
                                    <th>Civil Status</th>
                                    <th>Date of Birth</th>
                                    <th>Weight</th>
                                    <th>Height</th>
                                    <th>Blood Pressure</th>
                                    <th>Heart Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $_SESSION['username']; ?></td>
                                    <td><?php echo $_SESSION['password']; ?></td>
                                    <td><?php echo $_SESSION['name']; ?></td>
                                    <td><?php echo $_SESSION['guardian']; ?></td>
                                    <td><?php echo $_SESSION['address']; ?></td>
                                    <td><?php echo $_SESSION['contactnum']; ?></td>
                                    <td><?php echo $_SESSION['age']; ?></td>
                                    <td><?php echo $_SESSION['sex']; ?></td>
                                    <td><?php echo $_SESSION['civil_status']; ?></td>
                                    <td><?php echo $_SESSION['dob']; ?></td>
                                    <td><?php echo $_SESSION['weight']; ?></td>
                                    <td><?php echo $_SESSION['height']; ?></td>
                                    <td><?php echo $_SESSION['bloodpressure']; ?></td>
                                    <td><?php echo $_SESSION['heartrate']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
            <a href="?step=3" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
            <button type="submit" name="submit" class="btn btn-success" onclick="completeStep(4)">Submit</button>
            
        </form>
    <?php endif; ?>
</div>

					
					
		
        </div>
		<!-- /Main Wrapper -->
		
		<!-- jQuery -->
        <script src="assets/js/jquery-3.2.1.min.js"></script>
		
		<!-- Bootstrap Core JS -->
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
		
		<!-- Slimscroll JS -->
        <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		
		<script src="assets/plugins/raphael/raphael.min.js"></script>    
		<script src="assets/plugins/morris/morris.min.js"></script>  
		<script src="assets/js/chart.morris.js"></script>
		
		<!-- Custom JS -->
		<script  src="assets/js/script.js"></script>
		<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to mark the step as complete and display the check icon
    function completeStep(step) {
        const stepElement = document.querySelector(`.step:nth-child(${step})`);
        stepElement.classList.add('completed');
        stepElement.querySelector('.check-icon').style.display = 'inline';

        if (step === 4) {
            stepElement.querySelector('.step-text').textContent = 'Step 4 Complete';
        }
    }

    // Check which step is active and mark the previous steps as complete
    const steps = document.querySelectorAll('.step');
    steps.forEach((stepElement, index) => {
        if (stepElement.classList.contains('active')) {
            for (let i = 0; i < index; i++) {
                completeStep(i + 1);
            }
        }
    });

    // Event listeners for form submissions
    document.querySelector('#step1-form').addEventListener('submit', function(event) {
        completeStep(1);
    });

    document.querySelector('#step2-form').addEventListener('submit', function(event) {
        completeStep(2);
    });

    document.querySelector('#step3-form').addEventListener('submit', function(event) {
        completeStep(3);
    });

    document.querySelector('#step4-form').addEventListener('submit', function(event) {
        completeStep(4);
    });
});





        </script>
    </body>

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:34 GMT -->
</html>