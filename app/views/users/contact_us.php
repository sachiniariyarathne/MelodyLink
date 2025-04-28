<?php require APPROOT . '/views/inc/header7.php'; ?> 
<?php require APPROOT . '/views/inc/components/topnavbar_artist.php'; ?>

<head>
    <meta charset="UTF-8">
    <title>Contact Us | MelodyLink</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="contact-container">
        <div class="contact-header">
            <i class="fas fa-envelope-open-text"></i>
            <h1>Contact Us</h1>
            <p>
                We'd love to hear from you! Whether you have a question, feedback, or partnership inquiry, our team is ready to help.
            </p>
        </div>
        <div class="contact-content">
            <form class="contact-form" method="post" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name"><i class="fas fa-user"></i> Name</label>
                        <input type="text" id="name" name="name" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" id="email" name="email" placeholder="your@email.com" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="subject"><i class="fas fa-tag"></i> Subject</label>
                        <input type="text" id="subject" name="subject" placeholder="Subject" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="message"><i class="fas fa-comment-dots"></i> Message</label>
                        <textarea id="message" name="message" placeholder="Write your message here..." required></textarea>
                    </div>
                </div>
                <button type="submit" class="contact-submit">
                    <i class="fas fa-paper-plane"></i> Send Message
                </button>
            </form>
            <div class="contact-info">
                <h2>Contact Details</h2>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i> 123 Melody Street, Colombo, Sri Lanka</li>
                    <li><i class="fas fa-envelope"></i> support@melodylink.com</li>
                    <li><i class="fas fa-phone"></i> +94 77 123 4567</li>
                </ul>
                <div class="contact-social">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </div>
</body>

<?php require APPROOT . '/views/inc/footer.php'; ?> 