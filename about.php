<?php include('header.php'); ?>

<!-- Header Section with Hero Image -->
<header class="bg-primary text-white text-center py-5" style="background-image: url('hospital-hero.jpg'); background-size: cover; background-position: center; color: #fff;">
    <div class="container">
        <h1 class="display-4 font-weight-bold">About Us</h1>
        <p class="lead">Learn more about our hospital and its services.</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <!-- Our Mission Section -->
        <div class="col-md-12 mb-5">
            <h2 class="text-center">Our Mission</h2>
            <p class="lead text-center">At our hospital, we are committed to providing top-quality medical care with a focus on patient-centered services. Our team of dedicated professionals is here to ensure that every patient receives the best possible care in a compassionate and supportive environment.</p>
        </div>

        <!-- Our History Section with Image -->
        <div class="col-md-6 mb-4">
            <h3 class="text-primary">Our History</h3>
            <p>Founded in [Year], our hospital has grown from a small community clinic to a leading healthcare provider in the region. Over the years, we have expanded our services and facilities to meet the evolving needs of our patients.</p>
            <img src="history-image.jpg" alt="Our History" class="img-fluid rounded shadow-sm fixed-size-img">
        </div>

        <!-- Our Team Section with Image -->
        <div class="col-md-6 mb-4">
            <h3 class="text-primary">Our Team</h3>
            <p>Our hospital is proud to have a diverse and skilled team of healthcare professionals, including doctors, nurses, and support staff, who are dedicated to delivering excellent care. Each member of our team is trained to provide personalized and effective treatment tailored to each patient’s needs.</p>
            <img src="team-image.jpg" alt="Our Team" class="img-fluid rounded shadow-sm fixed-size-img">
        </div>

        <!-- Facilities Section -->
        <div class="col-md-12 mb-4">
            <h3 class="text-center text-primary">Facilities</h3>
            <p>We offer state-of-the-art facilities and equipment to ensure that our patients receive the best possible care. From our advanced diagnostic tools to our comfortable patient rooms, every aspect of our hospital is designed with patient well-being in mind.</p>
            <div class="row text-center">
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <img src="facility1.jpg" class="card-img-top fixed-size-img" alt="Facility 1">
                        <div class="card-body">
                            <h5 class="card-title">Advanced Diagnostics</h5>
                            <p class="card-text">Our hospital is equipped with the latest diagnostic tools for accurate and timely diagnosis.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <img src="facility2.jpg" class="card-img-top fixed-size-img" alt="Facility 2">
                        <div class="card-body">
                            <h5 class="card-title">Comfortable Patient Rooms</h5>
                            <p class="card-text">We offer comfortable and private patient rooms designed for recovery and relaxation.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <img src="facility3.jpg" class="card-img-top fixed-size-img" alt="Facility 3">
                        <div class="card-body">
                            <h5 class="card-title">Modern Operating Theaters</h5>
                            <p class="card-text">Our operating theaters are equipped with the latest technology for safe and effective procedures.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>

<style>
    body {
        font-family: 'Roboto', sans-serif;
    }

    h2, h3 {
        font-weight: bold;
        margin-bottom: 20px;
    }

    .lead {
        font-size: 1.2rem;
    }

    .container h2 {
        color: #007bff;
    }

    .card {
        transition: transform 0.2s ease;
    }

    .card:hover {
        transform: scale(1.05);
    }

    .card-title {
        color: #007bff;
        font-weight: bold;
    }

    .card-text {
        font-size: 0.9rem;
    }

    /* Fixed size for images */
    .fixed-size-img {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }
</style>
