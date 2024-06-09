<?php
session_start();
?>

<style>
/* General styles for the header */
header {
    background-color: #f8f9fa;

}

.row1 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
}

.nav-bar {
    list-style: none;
    display: flex;
    gap: 10px;
    margin: 0;
    padding: 0;
}

.nav-bar li {
    display: inline;
}

.nav-bar a {
    text-decoration: none;
    color: #000;
    font-weight: bold;
}

.nav-right {
    display: flex;
    align-items: center;
    gap: 10px;
}

.cart-icon {
    display: flex;
    align-items: center;
}

.cart-icon img {
    width: 30px;
    height: 30px;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropbtn {
    background-color: #f8f9fa;
    color: #000;
    font-weight: bold;
    border: none;
    cursor: pointer;
    padding: 10px;
}

.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: #f1f1f1;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color: #ddd;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown:hover .dropbtn {
    background-color: #ddd;
}


.hamburger {
    display: none;
    flex-direction: column;
    cursor: pointer;
}

.hamburger div {
    width: 25px;
    height: 2px;
    background-color: #000;
    border-radius: 5px;
    margin: 4px;
}

@media (max-width: 768px) {
    .nav-bar {
        display: none;
        flex-direction: column;
        width: 100%;
        background-color: #f8f9fa;
        position: absolute;
        top: 60px;
        left: 0;
        padding: 10px 0;
    }

    .nav-bar.show {
        display: flex;
    }

    .nav-bar li {
        display: block;
        text-align: center;
        padding: 10px 0;
    }

    .nav-right {
        display: none;
    }

    .hamburger {
        display: flex;
    }
}
</style>

<script>
function toggleMenu() {
    var navBar = document.querySelector('.nav-bar');
    navBar.classList.toggle('show');
}
</script>

<header>
    <nav>
        <div class="row1">
            <a href="Landingpage.php"><img src="Image/lighthouselogo.png" alt="Coffeeshop" class="logo"></a>
            <div class="hamburger" onclick="toggleMenu()">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <ul class="nav-bar">
                <li><a href="Landingpage.php">Home</a></li>
                <li><a href="Menu.php">Menu</a></li>
                <li><a href="About Us.php">About Cafe</a></li>
                <li><a href="Contact Us.php">Contact Us</a></li>
                <li><a href="Feedback Form.php">Reviews</a></li>
                <?php if (!isset($_SESSION['userID'])): ?>
                    <li><a href="Signup-Login.php">Login/Signup</a></li>
                <?php endif; ?>
            </ul>
            <div class="nav-right">
                <?php if (isset($_SESSION['userID'])): ?>
                    <div class="dropdown">
                        <button class="dropbtn"><?php echo ucwords(strtolower($_SESSION['fullname'])); ?></button>
                        <div class="dropdown-content">
                            <a href="logout.php">Logout</a>
                        </div>
                    </div>
                <?php endif; ?>
                <a href="ATC.php" class="cart-icon">
                    <img src="Image/finalcart.png" alt="Cart Logo">
                </a>
            </div>
        </div>
    </nav>
</header>
