<?php require APPROOT . '/views/inc/header3.php'; ?> 
<?php require APPROOT . '/views/inc/components/topnavbar_member.php'; ?>
   

<body>
<div class = "hp_homepage-container">
    <section class="hp_hero">
        <div class="hp_hero-content">
            <h2>Discover Live Music Events</h2>
            <p>Find and book tickets for the best concerts and festivals</p>
            <div class="hp_search-bar">
                <input type="text" placeholder="Search events, artists, or venues...">
                <button class="hp_search-btn"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </section>

    <section class="hp_events-section">
        <div class="hp_section-header">
            <h3>Featured Events</h3>
        </div>
        <div class="hp_events-container">
            <div class="hp_event-card">
                <img src="" alt="Summer Vibes Festival">
                <div class="hp_event-info">
                    <h4>Summer Vibes Festival</h4>
                    <p>July 10, 2025 · Central Park</p>
                    <div class="hp_event-bottom">
                        <span>$49.00</span>
                        <button>Book Now</button>
                    </div>
                </div>
            </div>
            <div class="hp_event-card">
                <img src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80" alt="Electric Dreams">
                <div class="hp_event-info">
                    <h4>Electric Dreams</h4>
                    <p>August 5, 2025 · Neon Arena</p>
                    <div class="hp_event-bottom">
                        <span>$65.00</span>
                        <button>Book Now</button>
                    </div>
                </div>
            </div>
            <div class="hp_event-card">
                <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=400&q=80" alt="Jazz & Blues Evening">
                <div class="hp_event-info">
                    <h4>Jazz & Blues Evening</h4>
                    <p>September 20, 2025 · Blue Note</p>
                    <div class="hp_event-bottom">
                        <span>$48.00</span>
                        <button>Book Now</button>
                    </div>
                </div>
            </div>
            <div class="hp_event-card">
                <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80" alt="Summer Vibes Festival">
                <div class="hp_event-info">
                    <h4>Summer Vibes Festival</h4>
                    <p>July 10, 2025 · Central Park</p>
                    <div class="hp_event-bottom">
                        <span>$49.00</span>
                        <button>Book Now</button>
                    </div>
                </div>
            </div>
            <a class="hp_see-more-btn" href="<?php echo URLROOT; ?>/events">See More</a>
        </div>
    </section>

    <section class="hp_section">
        <h3>Popular Merchandise</h3>
        <div class="hp_merch-list">
            <div class="hp_merch-card">
                <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=200&q=80" alt="Festival T-shirt">
                <p>Festival T-shirt</p>
                <span>$29.00</span>
                <button>Add to Cart</button>
            </div>
            <div class="hp_merch-card">
                <img src="https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=200&q=80" alt="Music Inside">
                <p>Music Inside</p>
                <span>$49.00</span>
                <button>Add to Cart</button>
            </div>
            <div class="hp_merch-card">
                <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=200&q=80" alt="Festival Cap">
                <p>Festival Cap</p>
                <span>$24.00</span>
                <button>Add to Cart</button>
            </div>
            <div class="hp_merch-card">
                <img src="https://images.unsplash.com/photo-1526178613658-3f1622045544?auto=format&fit=crop&w=200&q=80" alt="Event Tote Bag">
                <p>Event Tote Bag</p>
                <span>$19.00</span>
                <button>Add to Cart</button>
            </div>
            <div class="hp_merch-card">
                <img src="https://images.unsplash.com/photo-1526178613658-3f1622045544?auto=format&fit=crop&w=200&q=80" alt="Event Tote Bag">
                <p>Event Tote Bag</p>
                <span>$19.00</span>
                <button>Add to Cart</button>
            </div>
        </div>
        <a class="hp_explore-btn" href="<?php echo URLROOT; ?>/Merchandise">Explore</a>
    </section>

    <section class="hp_section">
        <h3>Trending Songs</h3>
        <div class="hp_songs-list">
            <div class="hp_song-card">
                <div class="hp_song-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1465101178521-c1a9136a3f5e?auto=format&fit=crop&w=200&q=80" alt="Summer Nights">
                    <button class="hp_play-btn"><i class="fa fa-play"></i></button>
                </div>
                <div class="hp_song-info">
                    <div class="hp_song-title">Summer Nights</div>
                    <div class="hp_song-artist">DJ Aurora</div>
                </div>
                </div>
                <div class="hp_song-card">
                <div class="hp_song-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=200&q=80" alt="Midnight Drive">
                    <button class="hp_play-btn"><i class="fa fa-play"></i></button>
                </div>
                <div class="hp_song-info">
                    <div class="hp_song-title">Summer Nights</div>
                    <div class="hp_song-artist">DJ Aurora</div>
                </div>
                </div>
                <div class="hp_song-card">
                <div class="hp_song-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=200&q=80" alt="Dancing Stars">
                    <button class="hp_play-btn"><i class="fa fa-play"></i></button>
                </div>
                <div class="hp_song-info">
                    <div class="hp_song-title">Summer Nights</div>
                    <div class="hp_song-artist">DJ Aurora</div>
                </div>
                </div>
                <div class="hp_song-card">
                <div class="hp_song-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1465101178521-c1a9136a3f5e?auto=format&fit=crop&w=200&q=80" alt="Summer Nights">
                    <button class="hp_play-btn"><i class="fa fa-play"></i></button>
                </div>
                <div class="hp_song-info">
                    <div class="hp_song-title">Summer Nights</div>
                    <div class="hp_song-artist">DJ Aurora</div>
                </div>
                </div>
        </div>
        <a class="hp_explore-btn" href="<?php echo URLROOT; ?>/Merchandise">Explore</a>
    </section>

    <script>
        // Dropdown toggle for profile icon
        function toggleDropdown() {
            document.getElementById("dropdownMenu").classList.toggle("show");
        }
        window.onclick = function(event) {
            if (!event.target.closest('.profile-dropdown')) {
                var dropdowns = document.getElementsByClassName("dropdown-menu");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</div>
</body>
<?php require APPROOT . '/views/inc/footer.php'; ?> 






