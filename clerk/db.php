<?php
$con = mysqli_connect("localhost", "root", "", "opdmsis");


// Check connection
if ($con == false ) {
  die("Connection failed: " . mysqli_connect_error());
}




if(isset($_GET['id'])) {
  $patientId = $_GET['id'];
  
  
  $query = mysqli_query($con, "SELECT * FROM patient_info WHERE id = $patientId");
  
  if(mysqli_num_rows($query) > 0) {
      $row = mysqli_fetch_assoc($query);
      echo json_encode($row); 
  } else {
      echo json_encode(['error' => 'No patient found']); 
  }
} else {

}





?>






