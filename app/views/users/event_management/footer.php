    </div><!-- End of main-content -->

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-copyright">
                &copy; <?php echo date('Y'); ?> <?php echo SITENAME; ?>. All rights reserved.
            </div>
        </div>
    </footer>

    <style>
        .footer {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem 0;
            margin-top: 2rem;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .footer-copyright {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .footer-content {
                padding: 0 1rem;
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>

    <!-- JavaScript for User Menu -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userButton = document.querySelector('.user-button');
            if (userButton) {
                userButton.addEventListener('click', function() {
                    // Add your user menu dropdown logic here
                });
            }
        });
    </script>
</body>
</html> 