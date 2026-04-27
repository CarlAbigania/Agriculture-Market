<?php
include '../../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriMarket Online Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/customer_dashboard.css">
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
            <a class="link" href="dashboard.php">Market</a>
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
                    <a href="../home/profile.php">View Profile</a>
                    <button class="logout-btn" onclick="confirmLogout()">Log Out</button>
                </div>
            </div>
        </nav>
    </header>

    <div class="hero">
        <h1>- WELCOME TO<br><br><span style="font-size: 50px;">AGRIMARKET ONLINE SHOP</span><br><br></h1>
        <p style="font-style: italic;">"AgriMarket: Bridging Farmers and Communities with Fresh, Local Goodness!"</p>
    </div>

    <div class="quote">
        <h1>Enjoy seamless online shopping experience with fresh produce and quality products.</h1>
        <p>Every purchase supports hardworking communities and promotes a healthier, more eco-conscious lifestyle. Join us in making a difference, one product at a time.</p>
    </div>

    <div class="categories">
        <a href="dashboard.php?category_id=1" class="<?php echo ($categoryId == 1) ? 'active' : ''; ?>">FRUITS</a>
        <a href="dashboard.php?category_id=2" class="<?php echo ($categoryId == 2) ? 'active' : ''; ?>">FOOD</a>
        <a href="dashboard.php?category_id=3" class="<?php echo ($categoryId == 3) ? 'active' : ''; ?>">VEGETABLES</a>
        <a href="dashboard.php?category_id=4" class="<?php echo ($categoryId == 4) ? 'active' : ''; ?>">POULTRY</a>
        <a href="dashboard.php?category_id=5" class="<?php echo ($categoryId == 5) ? 'active' : ''; ?>">FISH</a>
        <a href="dashboard.php?category_id=6" class="<?php echo ($categoryId == 6) ? 'active' : ''; ?>">MEATS</a>
    </div>

    <h2>
        <?php
        if (empty($products)) {
            echo "NO PRODUCTS CURRENTLY AVAILABLE";
        } else {
            echo strtoupper(htmlspecialchars($categoryName)) . " AVAILABLE FOR SALE";
        }
        ?>
    </h2>

    <div class="products">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <form method="POST" action="add_to_cart.php">
                    <div class="product-card">
                        <img
                            src="<?php echo htmlspecialchars($product['image_url']); ?>"
                            alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                            onerror="this.src='../../assets/images/foods/cheese.png';">
                        <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <p>₱<?php echo htmlspecialchars($product['price']); ?></p>
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>"> <!-- Added product_id -->
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>">
                        <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['price']); ?>">
                        <input type="hidden" id="popup-quantity-hidden" name="quantity" value="1"> <!-- Quantity input for form submission -->
                        <button type="submit">Add to Cart</button>
                    </div>
                </form>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products available</p>
        <?php endif; ?>
    </div>

    <!-- footer -->
    <footer class="footer-container">
        <div class="footer-content">
            <div class="footer-logo">
                <div class="logo-background"></div>
                <p>Lorem ipsum dolor sit amet consectetur. Tortor viverra elementum mauris suscipit porttitor interdum
                    mauris egestas. Et consectetur nunc proin vitae congue odio proin purus. Nisi tristique tincidunt
                    diam et. Tellus leo eu felis odio fusce massa nisl sit integer. Vel gravida lacus nec.</p>
            </div>

            <div class="footer-links">
                <h4>Quick Links</h4>
                <a href="../home/home.php">Home</a>
                <a href="../home/farm.php">Farm</a>
                <a href="dashboard.php">Market</a>
            </div>

            <div class="footer-contacts">
                <h4>Contacts</h4>
                <p><strong>Address:</strong> Plot 5, Idu Industrial Estate, Abuja</p>
                <p><strong>Phone Numbers:</strong> 2348012345678, 23470123456789</p>
                <p><strong>Email:</strong> hello@agromarket.com</p>
            </div>
        </div>
    </footer>

    <div class="menu-container">
        <span class="close-icon">&times;</span>
        <div class="menu-links">
            <a href="../home/home.php" class="menu-item"><i class="fa fa-home"></i> Home</a>
            <a href="../home/farm.php" class="menu-item"><i class="fa fa-tractor"></i> Farm</a>
            <a href="dashboard.php" class="menu-item"><i class="fa fa-store"></i> Market</a>
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
</body>

</html>
