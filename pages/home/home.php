<?php
include '../../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />

    <link rel="stylesheet" href="../../assets/css/home.css">

    <style>
        .cart-container {
            position: relative;
            display: inline-block;
        }

        .cart-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: red;
            color: white;
            font-size: 14px;
            padding: 5px 10px;
            border-radius: 50%;
            font-weight: bold;
            display: <?php echo $total_items > 0 ? 'inline' : 'none'; ?>;
        }

        .profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .profile-icon {
            color: black;
            text-decoration: none;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 34px;
            right: 0;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 10px;
            border-radius: 8px;
            z-index: 10;
            width: 150px;
        }

        .dropdown-menu a,
        .dropdown-menu button {
            display: block;
            text-decoration: none;
            color: black;
            padding: 10px;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-size: 16px;
        }

        .dropdown-menu a:hover,
        .dropdown-menu button:hover {
            background-color: #f0f0f0;
        }

        .profile-dropdown:hover .dropdown-menu {
            display: block;
        }
    </style>
</head>

<body>
    <!-- header section -->
    <header>

        <img src="../../assets/images/Logo.png" alt="AgriMarket Logo" style="width: 80px; height: 80px;">

        <nav>
            <a class="link" href="home.php  ">Home</a>
            <a class="link" href="../home/farm.php">Farm</a>
            <a class="link" href="../customer/dashboard.php">Market</a>

            <div class="menu-icon">
                <a href="#">
                    <i class="ri-menu-line" style="font-size: 33px; overflow: hidden;"></i>
                </a>
            </div>
            <div class="cart-container">
                <a href="../cart/cart.php">
                    <i class="fa fa-shopping-cart" style="font-size: 33px"></i>
                    <!-- Notification badge for total cart items -->
                    <span class="cart-badge"><?php echo $total_items > 0 ? $total_items : ''; ?></span>
                </a>
            </div>
            <a href="../contact/contact_us.php">
                <button>Get in Touch</button>
            </a>
            <div class="profile-dropdown">
                <a href="#" class="profile-icon">
                    <i class="fa fa-user-circle" style="font-size: 33px;"></i>
                </a>
                <div class="dropdown-menu">
                    <a href="profile.php">View Profile</a>
                    <button class="logout-btn" onclick="confirmLogout()">Log Out</button>
                </div>
            </div>
        </nav>
    </header>

    <div class="menu-container">
        <span class="close-icon">&times;</span>
        <div class="menu-links">
            <a href="#" class="menu-item"><i class="fa fa-home"></i> Home</a>
            <a href="../home/farm.php" class="menu-item"><i class="fa fa-tractor"></i> Farm</a>
            <a href="../customer/dashboard.php" class="menu-item"><i class="fa fa-store"></i> Market</a>
        </div>
    </div>

    <script>
        // Open the menu
        document.querySelector(".ri-menu-line").addEventListener("click", function() {
            document.querySelector(".menu-container").classList.add("show");
        });

        // Close the menu
        document.querySelector(".close-icon").addEventListener("click", function() {
            document.querySelector(".menu-container").classList.remove("show");
        });

        // JavaScript function to confirm logout
        function confirmLogout() {
            const userConfirmation = confirm("Are you sure you want to log out?");
            if (userConfirmation) {
                // If confirmed, log out and redirect to login page
                window.location.href = '../../includes/logout.php'; // Change this URL to your logout script
            }
        }
    </script>

    <!-- hero section -->
    <section class="hero-container">
        <div class="wrapper-hero">
            <div class="blurd-box">
                <div class="transparent-box">
                    <h2>Get All Farm Produce <span>Fresh</span></h2>
                    <p>Explore a wide variety of fresh, organic, and sustainable farm products, thoughtfully grown and
                        harvested directly by dedicated farmers, ensuring quality, health, and a meaningful connection
                        to the source of your food.</p>
                    <a href="../home/farm.php">
                        <button>Learn More</button>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- additional information section -->
    <section class="information-container">
        <div class="wrapper-information">
            <div class="box">
                <h2>Farm</h2>
                <p>A farm is a piece of land dedicated to agricultural production, where crops are grown, and animals
                    are raised for food, raw materials, or other purposes. Farms can vary in size and type, from small
                    family-run operations to large industrial enterprises. Common types of farming include crop farming,
                    livestock farming, dairy farming, and mixed farming. Essential elements of a farm often include
                    fields, barns, irrigation systems, machinery, and pastures. Farms play a crucial role in providing
                    food and resources, supporting local economies, and contributing to the global agricultural
                    industry.</p>
                <button>Read More</button>
            </div>
            <div class="box">
                <h2>Market</h2>
                <p>A market is a place or platform where goods and services are bought and sold, facilitating trade
                    between buyers and sellers. Markets can take various forms, such as physical locations like farmers'
                    markets, supermarkets, or bazaars, as well as digital spaces like online marketplaces. They play a
                    key role in the economy by enabling the exchange of products, setting prices based on supply and
                    demand, and fostering competition. Markets can specialize in specific items, such as fresh produce,
                    artisan goods, or financial assets, and often serve as hubs for community interaction and economic
                    activity.</p>
                <button>Read More</button>
            </div>
            <div class="box">
                <h2>Support</h2>
                <p>Support for farmers includes providing resources, training, and financial assistance to help them
                    enhance productivity, adopt modern and sustainable farming practices, and overcome challenges such
                    as climate change or market access. This support often comes from governments, organizations, and
                    agricultural cooperatives, offering tools like subsidies, loans, equipment, and technical guidance.
                    By empowering farmers, such support ensures agricultural growth, community well-being, and long-term
                    food security.</p>
                <button>Read More</button>
            </div>
        </div>
    </section>

    <!-- offer section -->
    <div class="offer-container">
        <h2>What <span>We Offer</span></h2>
        <div style="width: 5%; margin: 15px auto 20px; border-top: 3px solid #005129; border-radius: 10px;"></div>
        <p>We provide a wide range of high-quality agricultural products and services tailored to meet the needs of
            farmers, consumers, and businesses alike.</p>
        <div class="wrapper-offers">
            <div class="offer">
                <img src="../../assets/images/offer1.jpg" alt="Offer 1">
                <p>Discover premium seeds and crop solutions to ensure your farm produces the best yield, season after
                    season.</p>
                <button>Check it Out</button>
            </div>
            <div class="offer">
                <img src="../../assets/images/offer1.jpg" alt="Offer 2">
                <p>Access fresh and organically grown produce directly from trusted farmers, delivered straight to your
                    doorstep.</p>
                <button>Check it Out</button>
            </div>
            <div class="offer">
                <img src="../../assets/images/offer1.jpg" alt="Offer 3">
                <p>Benefit from innovative tools and technology to optimize your farming process and boost productivity.
                </p>
                <button>Check it Out</button>
            </div>
        </div>
    </div>

    <!-- about us section -->
    <section class="about-container">
        <div class="wrapper">
            <div class="wrapper-about">
                <div class="about-heading">
                    <h2><span>Why</span> Choose Us?</h2>
                </div>
                <div class="about-boxes">
                    <div class="box about">
                        <div class="icon">
                            <i class="fa-solid fa-plant-wilt"></i>
                        </div>
                        <div class="content">
                            <h3>Crop Farming Description</h3>
                            <p>Enhance your agricultural practices with our guidance on advanced crop farming
                                techniques, ensuring sustainable growth and quality yields.</p>
                        </div>
                    </div>
                    <div class="box about">
                        <div class="icon">
                            <i class="fa-solid fa-plant-wilt"></i>
                        </div>
                        <div class="content">
                            <h3>Livestock Farming Description</h3>
                            <p>
                                Explore modern livestock farming methods to improve animal health and productivity while
                                maintaining ethical practices.</p>
                        </div>
                    </div>
                    <div class="box about">
                        <div class="icon">
                            <i class="fa-solid fa-plant-wilt"></i>
                        </div>
                        <div class="content">
                            <h3>Community Support Description</h3>
                            <p>Partner with us to create stronger farming communities through education, support
                                programs, and sustainable solutions.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- partner section -->
    <section class="partner-container">
        <div class="partner-wrapper">
            <h2><span style="color: #00B85E;">Our Happy</span> Partners</h2>
            <div class="img-container">
                <img src="../../assets/images/partner1.png" alt="partner1">
                <img src="../../assets/images/partner2.png" alt="partner2">
                <img src="../../assets/images/partner3.png" alt="partner3">
            </div>
        </div>
    </section>

    <!-- join us section -->
    <section class="join-us-container">
        <div class="join-us-content">
            <h3>Join Our Mailing List</h3>
            <p>Be the first to know about our products and services. We will deliver<br> it directly to your mailbox.
            </p>
            <form class="join-us-form">
                <input type="text" placeholder="Enter your first name">
                <input type="text" placeholder="Enter your last name">
                <input type="email" placeholder="Enter your email">
                <button type="submit">Submit</button>
            </form>
        </div>
    </section>

    <!-- footer -->
    <footer class="footer-container">
        <div class="footer-content">
            <div class="footer-logo">
                <div class="logo-background">
                    <img src="../../assets/images/logo.png" alt="logo-footer" style="width: 4rem;">
                </div>

                <p>Our commitment lies in promoting sustainable farming practices and bridging the gap between farmers
                    and markets, empowering communities for a better tomorrow.</p>
            </div>

            <div class="footer-links">
                <h4>Quick Links</h4>
                <a href="home.php   ">Home</a>
                <a href="../home/farm.php">Farm</a>
                <a href="../customer/dashboard.php">Market</a>
            </div>

            <div class="footer-contacts">
                <h4>Contacts</h4>
                <p><strong>Address:</strong> Plot 5, Idu Industrial Estate, Abuja</p>
                <p><strong>Phone Numbers:</strong> 2348012345678, 23470123456789</p>
                <p><strong>Email:</strong> hello@agromarket.com</p>
            </div>
        </div>
    </footer>
</body>

</html>
