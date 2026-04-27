<?php
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Screen</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />

    <link rel="stylesheet" href="farm_details.css">

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
            <button onclick="window.location.href = '../contact/contact_us.php';">Get in Touch</button>
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

    <!-- fruit section -->
    <section class="fruit_container" style="padding-top: 12em;">
        <div class="fruit_text">
            <div class="fruit_header">
                <img src="../../assets/images/fruit.png" alt="fruit" style="width: 49px;">
                <h2>Fruit Farm</h2>
            </div>
            <p>Fruit farms are essential for cultivating a variety of delicious and nutritious fruits that enrich our
                diets and provide key vitamins. From the vibrant orange orchards to lush mango groves, each farm is a
                testament to sustainable and dedicated farming practices.</p>
        </div>
        <div class="fruit_image_wrapper">
            <button class="arrow left" onclick="scrollLeft()">&#10094;</button>
            <div class="fruit_image">
                <div class="image_wrapper">
                    <img src="../../assets/images/market_fruit1.jpg" alt="fruit1">
                    <button class="fruit_button">Orange Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/market_fruit2.jpg" alt="fruit2">
                    <button class="fruit_button">Apple Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/market_fruit3.jpg" alt="fruit3">
                    <button class="fruit_button">Mango Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/market_fruit1.jpg" alt="fruit1">
                    <button class="fruit_button">Orange Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/market_fruit2.jpg" alt="fruit2">
                    <button class="fruit_button">Apple Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/market_fruit3.jpg" alt="fruit3">
                    <button class="fruit_button">Mango Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/market_fruit1.jpg" alt="fruit1">
                    <button class="fruit_button">Orange Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/market_fruit2.jpg" alt="fruit2">
                    <button class="fruit_button">Apple Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/market_fruit3.jpg" alt="fruit3">
                    <button class="fruit_button">Mango Farm</button>
                </div>
            </div>
            <button class="arrow right" onclick="scrollRight()">&#10095;</button>
        </div>
    </section>

    <!-- crops section -->
    <section class="crops_container">
        <div class="crops_text">
            <div class="crops_header">
                <img src="../../assets/images/crops.png" alt="crops" style="width: 50px;">
                <h2>Food Crops</h2>
            </div>
            <p>Food crops form the backbone of agriculture, supplying staple grains and legumes crucial to global diets.
                Explore the diversity of farms producing everything from rice to maize.</p>
        </div>
        <div class="crops_image_wrapper">
            <button class="arrow left" onclick="scrollLeft()">&#10094;</button>
            <div class="crops_image">
                <div class="image_wrapper">
                    <img src="../../assets/images/crops1.png" alt="crops1">
                    <button class="fruit_button">Maize Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/crops2.png" alt="crops2">
                    <button class="fruit_button">Rice Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/crops3.png" alt="crops3">
                    <button class="fruit_button">Beans Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/crops1.png" alt="crops1">
                    <button class="fruit_button">Maize Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/crops2.png" alt="crops2">
                    <button class="fruit_button">Rice Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/crops3.png" alt="crops3">
                    <button class="fruit_button">Beans Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/crops1.png" alt="crops1">
                    <button class="fruit_button">Maize Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/crops2.png" alt="crops2">
                    <button class="fruit_button">Rice Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/crops3.png" alt="crops3">
                    <button class="fruit_button">Beans Farm</button>
                </div>
            </div>
            <button class="arrow right" onclick="scrollRight()">&#10095;</button>
        </div>
    </section>

    <!-- vegetable section -->
    <section class="vegetable_container">
        <div class="vegetable_text">
            <div class="vegetable_header">
                <img src="../../assets/images/vegetables.png" alt="vegetable" style="width: 50px;">
                <h2>Vegetable Farm</h2>
            </div>
            <p>Vegetable farming focuses on providing fresh and nutritious produce essential for a balanced diet. These
                farms deliver a range of greens and other vegetables grown under ideal conditions.</p>
        </div>
        <div class="vegetable_image_wrapper">
            <button class="arrow left" onclick="scrollLeft()">&#10094;</button>
            <div class="vegetable_image">
                <div class="image_wrapper">
                    <img src="../../assets/images/vegetable1.png" alt="vegetable1">
                    <button class="vegetable_button">Lettuce Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/vegetable2.png" alt="vegetable2">
                    <button class="vegetable_button">Melon Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/vegetable3.png" alt="vegetable3">
                    <button class="vegetable_button">Tomatoes Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/vegetable1.png" alt="vegetable1">
                    <button class="vegetable_button">Lettuce Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/vegetable2.png" alt="vegetable2">
                    <button class="vegetable_button">Melon Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/vegetable3.png" alt="vegetable3">
                    <button class="vegetable_button">Tomatoes Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/vegetable1.png" alt="vegetable1">
                    <button class="vegetable_button">Lettuce Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/vegetable2.png" alt="vegetable2">
                    <button class="vegetable_button">Melon Farm</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/vegetable3.png" alt="vegetable3">
                    <button class="vegetable_button">Tomatoes Farm</button>
                </div>
            </div>
            <button class="arrow right" onclick="scrollRight()">&#10095;</button>
        </div>
    </section>

    <!-- poultry section -->
    <section class="poultry_container">
        <div class="poultry_text">
            <div class="poultry_header">
                <img src="../../assets/images/poultry.png" alt="poultry" style="width: 50px;">
                <h2>Poultry Farm</h2>
            </div>
            <p>Poultry farming provides a sustainable source of meat and eggs, playing a critical role in global food
                security.</p>
        </div>
        <div class="poultry_image_wrapper">
            <button class="arrow left" onclick="scrollLeft()">&#10094;</button>
            <div class="poultry_image">
                <div class="image_wrapper">
                    <img src="../../assets/images/poultry1.png" alt="poultry1">
                    <button class="poultry_button">White Foul Poultry</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/poultry2.png" alt="poultry2">
                    <button class="poultry_button">Chicks Poultry</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/poultry3.png" alt="poultry3">
                    <button class="poultry_button">Layer Poultry</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/poultry1.png" alt="poultry1">
                    <button class="poultry_button">White Foul Poultry</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/poultry2.png" alt="poultry2">
                    <button class="poultry_button">Chicks Poultry</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/poultry3.png" alt="poultry3">
                    <button class="poultry_button">Layer Poultry</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/poultry1.png" alt="poultry1">
                    <button class="poultry_button">White Foul Poultry</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/poultry2.png" alt="poultry2">
                    <button class="poultry_button">Chicks Poultry</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/poultry3.png" alt="poultry3">
                    <button class="poultry_button">Layer Poultry</button>
                </div>
            </div>
            <button class="arrow right" onclick="scrollRight()">&#10095;</button>
        </div>
    </section>

    <!-- piscicultture section -->
    <section class="pisciculture_container">
        <div class="pisciculture_text">
            <div class="pisciculture_header">
                <img src="../../assets/images/pisciculture.png" alt="pisciculture" style="width: 50px;">
                <h2>Pisciculture Farm</h2>
            </div>
            <p>Pond-based aquaculture or pisciculture emphasizes sustainable practices in fish farming, ensuring
                high-quality seafood.</p>
        </div>
        <div class="pisciculture_image_wrapper">
            <button class="arrow left" onclick="scrollLeft()">&#10094;</button>
            <div class="pisciculture_image">
                <div class="image_wrapper">
                    <img src="../../assets/images/pisciculture1.png" alt="pisciculture1">
                    <button class="pisciculture_button">Beuty Fish Pond</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/pisciculture2.png" alt="pisciculture2">
                    <button class="pisciculture_button">Duck Pond</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/pisciculture3.png" alt="pisciculture3">
                    <button class="pisciculture_button">Large Fish Pond</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/pisciculture1.png" alt="pisciculture1">
                    <button class="pisciculture_button">Beuty Fish Pond</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/pisciculture2.png" alt="pisciculture2">
                    <button class="pisciculture_button">Duck Pond</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/pisciculture3.png" alt="pisciculture3">
                    <button class="pisciculture_button">Large Fish Pond</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/pisciculture1.png" alt="pisciculture1">
                    <button class="pisciculture_button">Beuty Fish Pond</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/pisciculture2.png" alt="pisciculture2">
                    <button class="pisciculture_button">Duck Pond</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/pisciculture3.png" alt="pisciculture3">
                    <button class="pisciculture_button">Large Fish Pond</button>
                </div>
            </div>
            <button class="arrow right" onclick="scrollRight()">&#10095;</button>
        </div>
    </section>

    <!-- animal husbandry seection -->
    <section class="husbandry_container">
        <div class="husbandry_text">
            <div class="husbandry_header">
                <img src="../../assets/images/husbandry.png" alt="husbandry" style="width: 50px;">
                <h2>Animal Husbandry</h2>
            </div>
            <p>Animal husbandry focuses on raising livestock for food, clothing, and other resources, ensuring humane
                and sustainable practices.</p>
        </div>
        <div class="husbandry_image_wrapper">
            <button class="arrow left" onclick="scrollLeft()">&#10094;</button>
            <div class="husbandry_image">
                <div class="image_wrapper">
                    <img src="../../assets/images/husbandry1.png" alt="husbandry1">
                    <button class="husbandry_button">Black Cow Ranch</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/husbandry2.png" alt="husbandry2">
                    <button class="husbandry_button">Brown Cow Ranch</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/husbandry3.png" alt="husbandry3">
                    <button class="husbandry_button">White Cow Ranch</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/husbandry1.png" alt="husbandry1">
                    <button class="husbandry_button">Black Cow Ranch</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/husbandry2.png" alt="husbandry2">
                    <button class="husbandry_button">Brown Cow Ranch</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/husbandry3.png" alt="husbandry3">
                    <button class="husbandry_button">White Cow Ranch</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/husbandry1.png" alt="husbandry1">
                    <button class="husbandry_button">Black Cow Ranch</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/husbandry2.png" alt="husbandry2">
                    <button class="husbandry_button">Brown Cow Ranch</button>
                </div>
                <div class="image_wrapper">
                    <img src="../../assets/images/husbandry3.png" alt="husbandry3">
                    <button class="husbandry_button">White Cow Ranch</button>
                </div>
            </div>
            <button class="arrow right" onclick="scrollRight()">&#10095;</button>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const scrollAmount = 270;

            // Select all image containers
            const containers = document.querySelectorAll('.fruit_image, .vegetable_image, .pisciculture_image, .crops_image, .poultry_image, .husbandry_image');

            containers.forEach(container => {
                const images = container.children;
                const totalImages = images.length;
                const visibleImages = Math.floor(container.parentElement.offsetWidth / scrollAmount);
                const maxScroll = (totalImages - visibleImages) * scrollAmount;

                let scrollPosition = 0;

                container.style.width = `${totalImages * scrollAmount}px`;
                container.style.transform = `translateX(0px)`;

                function resetToFirstImage() {
                    scrollPosition = 0;
                    container.style.transform = `translateX(0px)`;
                }

                function scrollLeft() {
                    scrollPosition -= scrollAmount;
                    if (scrollPosition < 0) {
                        scrollPosition = 0;
                    }
                    container.style.transform = `translateX(-${scrollPosition}px)`;
                }

                function scrollRight() {
                    scrollPosition += scrollAmount;
                    if (scrollPosition > maxScroll) {
                        scrollPosition = maxScroll;
                    }
                    container.style.transform = `translateX(-${scrollPosition}px)`;
                }

                const parentSection = container.closest(
                    '.fruit_container, .vegetable_container, .pisciculture_container, .crops_container, .poultry_container, .husbandry_container'
                );

                if (parentSection) {
                    parentSection.querySelector('.arrow.left').addEventListener('click', scrollLeft);
                    parentSection.querySelector('.arrow.right').addEventListener('click', scrollRight);
                } else {
                    console.warn(`Parent section not found for container: ${container.className}`);
                }

                resetToFirstImage();

                console.log(`Container: ${container.className}`);
                console.log(`Total images: ${totalImages}`);
                console.log(`Visible images: ${visibleImages}`);
                console.log(`Max scroll: ${maxScroll}`);
            });
        });
    </script>

    <!-- footer -->
    <footer class="footer-container">
        <div class="footer-content">
            <div class="footer-logo">
                <div class="logo-background">
                    <img src="../../assets/images/logo.png" alt="logo-farm" style="width: 4rem;">
                </div>
                <p>Lorem ipsum dolor sit amet consectetur. Tortor viverra elementum mauris suscipit porttitor interdum
                    mauris egestas. Et consectetur nunc proin vitae congue odio proin purus. Nisi tristique tincidunt
                    diam et. Tellus leo eu felis odio fusce massa nisl sit integer. Vel gravida lacus nec.</p>
            </div>

            <div class="footer-links">
                <h4>Quick Links</h4>
                <a href="../home/home.php">Home</a>
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
