<?php include('header.php'); ?>

<?php
if (isset($_GET['registration_success']) && $_GET['registration_success'] == 1) {
    $patient_id = $_GET['patient_id'];
    echo '<div class="alert alert-success text-center" role="alert">Registration successful! Your Patient ID is: ' . htmlspecialchars($patient_id) . '</div>';
}
?>

<?php
if (isset($_GET['appointment_success']) && $_GET['appointment_success'] == 1) {
    echo '<div class="alert alert-success text-center" role="alert">Appointment booked successfully!</div>';
}
?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Hospital Management System</h1>
        <p class="lead">Efficient management of appointments, medical records, and more.</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <!-- Welcome Section -->
        <div class="col-md-12 text-center mb-4">
            <?php if (isset($_SESSION['user_id'])): ?>
                <h2 class="display-4">Welcome, <?php echo htmlspecialchars($_SESSION['role']); ?>!</h2>
                <p class="lead">Manage your consultations and view relevant information.</p>
            <?php else: ?>
                <h2 class="display-4">Welcome to Our Hospital Management System</h2>
                <p class="lead">Streamlining healthcare management for better patient care.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
      <!-- Appointment Scheduling -->
<div class="col-md-4 mb-4">
    <div class="card shadow-lg border-light">
        <div class="card-header bg-success text-white d-flex align-items-center">
            <img src="appointment.jpg" class="card-header-img mr-3" alt="Book an Appointment">
            <h5 class="card-title mb-0">Book an Appointment</h5>
        </div>
        <div class="card-body">
            <p class="card-text">Schedule your appointments with our doctors quickly and easily.</p>
            <a href="book_appointment.php" class="btn btn-success">Book Now</a>
        </div>
    </div>
</div>


   <!-- Patient Registration -->
<div class="col-md-4 mb-4">
    <div class="card shadow-lg border-light">
        <div class="card-header bg-warning text-dark d-flex align-items-center">
            <img src="register.jpg" class="card-header-img mr-3" alt="Patient Registration">
            <h5 class="card-title mb-0">Patient Registration</h5>
        </div>
        <div class="card-body">
            <p class="card-text">Register as a new patient and manage your health records.</p>
            <a href="patient_signup.php" class="btn btn-warning">Register Now</a>
        </div>
    </div>
</div>

     
<!-- Doctor Information -->
<div class="col-md-4 mb-4">
    <div class="card shadow-lg border-light">
        <div class="card-header bg-info text-white d-flex align-items-center">
            <img src="doctors.jpg" class="card-header-img mr-3" alt="Our Doctors">
            <h5 class="card-title mb-0">Our Doctors</h5>
        </div>
        <div class="card-body">
            <p class="card-text">Explore information about our experienced doctors.</p>
            <a href="doctors.php" class="btn btn-info">View Doctors</a>
        </div>
    </div>
</div>
        <!-- Doctor Schedules -->
<div class="col-md-4 mb-4">
    <div class="card shadow-lg border-light">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <img src="schedule.jpg" class="img-fluid mr-3" alt="Doctor Schedules">
            <h5 class="card-title mb-0">Doctor Schedules</h5>
        </div>
        <div class="card-body">
            <p class="card-text">Check the availability of our doctors.</p>
            <a href="doctor_schedule.php" class="btn btn-primary">View Schedules</a>
        </div>
    </div>
</div>



       <!-- Online Consultation -->
<div class="col-md-4 mb-4">
    <div class="card shadow-lg border-light">
        <div class="card-header bg-secondary text-white d-flex align-items-center">
            <img src="consultation.jpg" class="card-header-img mr-3" alt="Online Consultation">
            <h5 class="card-title mb-0">Online Consultation</h5>
        </div>
        <div class="card-body">
            <p class="card-text">Request an online consultation with one of our doctors.</p>
            <a href="online_consultation.php" class="btn btn-secondary">Request Consultation</a>
        </div>
    </div>
</div>


<!-- Admit Patient
<div class="col-md-4 mb-4">
    <div class="card shadow-lg border-light">
        <div class="card-header bg-danger text-white d-flex align-items-center">
            <img src="admit_patient.jpg" class="card-header-img mr-3" alt="Admit Patient">
            <h5 class="card-title mb-0">Admit Patient</h5>
        </div>
        <div class="card-body">
            <p class="card-text">Quickly admit new patients and manage their hospital records.</p>
            <a href="admit_patient.php" class="btn btn-danger">Admit Now</a>
        </div>
    </div>
</div> -->
</main>

<?php include('footer.php'); ?>
