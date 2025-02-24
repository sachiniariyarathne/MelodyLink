<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Page</title>
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
<?php require APPROOT . '/views/inc/header2.php';?>
    <!-- Header -->
    <header>
        <div class="logo">MelodyLink</div>
        <nav>
            <ul>
                <li><a href="#latest-media">Home</a></li>
                <li><a href="#latest-events">Events</a></li>
                <li><a href="#merchandise">Merchandise</a></li>
                <li><a href="#equipment">Music</a></li>
                <li><a href="#reviews">Profile</a></li>
            </ul>
        </nav>
        <div class="header-buttons">
            <a href="#" class="login-btn">LOGIN</a>
            <a href="#" class="signup-btn">SIGN UP</a>
        </div>
    </header>

    <section class="search-bar">
        <form id="searchForm" onsubmit="searchMusic(event)">
            <input type="text" id="searchInput" placeholder="Search for Albums, Artist, and Genres">
            <button type="submit"><img src="../public/img/search-icon.png" alt="Search"></button>
        </form>
    </section>

    <!-- Music Section -->
    <section class="music-section">
        <div class="music-cards">
            <!-- Card 1 -->
            <div class="music-card">
                <img src="../public/img/sansarini.jpeg" alt="Song 1">
                <h3>Sansarini</h3>
                <p>Yasas Medagedara</p>
                <button onclick="window.open('https://www.youtube.com/embed/heKksPAwfeE?autoplay=1', '_blank')">Listen Now</button>
            </div>
            <!-- Card 2 -->
            <div class="music-card">
                <img src="../public/img/Sandanari.jpg" alt="Song 2">
                <h3>Sandanaari</h3>
                <p>Harsha Withanage</p>
                <button onclick="window.open('https://www.youtube.com/watch?v=4TtdZIYR1S4?autoplay=1', '_blank')">Listen Now</button>
            </div>
            <!-- Card 3 -->
            <div class="music-card">
                <img src="../public/img/sandaganawa.jpg" alt="Song 3">
                <h3>Sandaganawa</h3>
                <p>Dhanith Sri</p>
                <button onclick="window.open('https://www.youtube.com/watch?v=Fy1fZGsZnPg&autoplay=1', '_blank')">Listen Now</button>
            </div>
            <!-- Card 4 -->
            <div class="music-card">
                <img src="../public/img/kaviya.jpg" alt="Song 4">
                <h3>Kawiya</h3>
                <p>Vidula Ravishara</p>
                <button onclick="window.open('https://www.youtube.com/watch?v=zx3wYHSqqiI&autoplay=1', '_blank')">Listen Now</button>
            </div>
            <!-- Card 5 -->
            <div class="music-card">
                <img src="../public/img/mathakayan.jpg" alt="Song 5">
                <h3>Mathakayan</h3>
                <p>Nadeemal Perera</p>
                <button onclick="window.open('https://www.youtube.com/watch?v=gv1JXEKN-Jc&autoplay=1', '_blank')">Listen Now</button>
            </div>
            <!-- Card 6 -->
            <div class="music-card">
                <img src="../public/img/unuhuma.jpg" alt="Song 6">
                <h3>Unuhuma 2</h3>
                <p>Tehan Perera</p>
                <button onclick="window.open('https://www.youtube.com/watch?v=vPXfklYG-9Q&autoplay=1', '_blank')">Listen Now</button>
            </div>
            <!-- Card 7 -->
            <div class="music-card">
                <img src="../public/img/Dawasak evi.jpg" alt="Song 7">
                <h3>Dawasak Evi</h3>
                <p>Piyath Rajapakse</p>
                <button onclick="window.open('https://www.youtube.com/watch?v=4ZPmLECW6ec&autoplay=1', '_blank')">Listen Now</button>
            </div>
            <!-- Card 8 -->
            <div class="music-card">
                <img src="../public/img/malonchilla.jpg" alt="Song 8">
                <h3>Mal Onchilla</h3>
                <p>Hana Shafa</p>
                <button onclick="window.open('https://www.youtube.com/watch?v=YAPrucBCFWE&autoplay=1', '_blank')">Listen Now</button>
            </div>
            <!-- Card 9 -->
            <div class="music-card">
                <img src="../public/img/aradhana.jpg" alt="Song 9">
                <h3>Aradhana</h3>
                <p>Daddy</p>
                <button onclick="window.open('https://www.youtube.com/watch?v=8IqGkX7jlxg&autoplay=1', '_blank')">Listen Now</button>
            </div>
            <!-- Card 10 -->
            <div class="music-card">
                <img src="../public/img/danena2.jpg" alt="Song 10">
                <h3>Danena Thuru Maa</h3>
                <p>Dinesh Gamage ft Kanchana Anuradhi</p>
                <button onclick="window.open('https://www.youtube.com/watch?v=5EzBFudsqRY&autoplay=1', '_blank')">Listen Now</button>
            </div>
            <!-- Card 11 -->
            <div class="music-card">
                <img src="../public/img/mayam kalawe.jpg" alt="Song 11">
                <h3>Mayam Kalawe</h3>
                <p>Nadeemal Perera</p>
                <button onclick="window.open('https://www.youtube.com/watch?v=LF6yledNE74&autoplay=1', '_blank')">Listen Now</button>
            </div>
            <!-- Card 12 -->
            <div class="music-card">
                <img src="../public/img/udurawee.jpg" alt="Song 12">
                <h3>Udurawee</h3>
                <p>Kanchana Anuradhi</p>
                <button onclick="window.open('https://www.youtube.com/watch?v=JvywnNza2R8&autoplay=1', '_blank')">Listen Now</button>
            </div>
            <!-- Card 13 -->
            <div class="music-card">
                <img src="../public/img/massina.jpg" alt="Song 13">
                <h3>Massina</h3>
                <p>Daddy</p>
                <button onclick="window.open('https://www.youtube.com/watch?v=b6mimgzVBig&autoplay=1', '_blank')">Listen Now</button>
            </div>
            <!-- Card 14 -->
            <div class="music-card">
                <img src="../public/img/sobana.jpg" alt="Song 14">
                <h3>Sobana</h3>
                <p>Ridma Weerawardhana</p>
                <button onclick="window.open('https://www.youtube.com/watch?v=kFm0J1V27Ug&autoplay=1', '_blank')">Listen Now</button>
            </div>
            <!-- Card 15 -->
            <div class="music-card">
                <img src="../public/img/aikale.jpg" alt="Song 15">
                <h3>Ai Kale</h3>
                <p>Daddy</p>
                <button onclick="window.open('https://www.youtube.com/watch?v=zcNkZv_XG-c&autoplay=1', '_blank')">Listen Now</button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-about">
                <h4>About MelodyLink</h4>
                <p>MelodyLink is an innovative web application designed to revolutionize the way we engage with music by offering a comprehensive, all-in-one platform for artists, fans, event organizers, merchandise vendors, and event equipment renters.</p>
                <img src="../public/img/logo.png" alt="MelodyLink Logo">
            </div>
            
            <div class="footer-links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#artists">Music</a></li>
                    <li><a href="#artists">Artists</a></li>
                    <li><a href="#albums">Events</a></li>
                    <li><a href="#genres">Store</a></li>
                    <li><a href="#artists">Communities</a></li>
                    <li><a href="#contact">Contact Us</a></li>
                </ul>
            </div>
            
            <div class="footer-social">
                <h4>Follow Us</h4>
                <div class="social-icons">
                    <a href="#"><img src="../public/img/fb.png" alt="Facebook"></a>
                    <a href="#"><img src="../public/img/tw.png" alt="Twitter"></a>
                    <a href="#"><img src="../public/img/inst.png" alt="Instagram"></a>
                    <a href="#"><img src="../public/img/yt.png" alt="YouTube"></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 MelodyLink. All Rights Reserved.</p>
        </div>
    </footer> 
</body>
</html>
