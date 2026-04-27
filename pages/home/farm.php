<?php
include '../../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Header</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />

    <link rel="stylesheet" href="../../assets/css/farm.css">

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
            <a class="link" href="../home/home.php">Home</a>
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
            <a href="../home/home.php" class="menu-item"><i class="fa fa-home"></i> Home</a>
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

    <!-- home section -->
    <section class="home">
        <div class="container_home">
            <div class="home-image-wrapper">
                <img src="../../assets/images/white_house.png" alt="White House" class="home-image">
                <a class="circle" href="farm_details.php">
                    <button>Press Here to Enter</button>
                </a>
            </div>
        </div>
    </section>

</body>

</html>
