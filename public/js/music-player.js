document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const audioPlayer = document.getElementById('audio-player');
    const playPauseBtn = document.getElementById('play-pause-btn');
    const prevTrackBtn = document.getElementById('prev-track-btn');
    const nextTrackBtn = document.getElementById('next-track-btn');
    const shuffleBtn = document.getElementById('shuffle-btn');
    const repeatBtn = document.getElementById('repeat-btn');
    const songProgress = document.getElementById('song-progress');
    const progressBar = document.querySelector('.progress-bar');
    const currentTimeEl = document.getElementById('current-time');
    const totalTimeEl = document.getElementById('total-time');
    const nowPlayingCover = document.getElementById('now-playing-cover');
    const nowPlayingTitle = document.getElementById('now-playing-title');
    const nowPlayingArtist = document.getElementById('now-playing-artist');
    const volumeBtn = document.getElementById('volume-btn');
    const volumeLevel = document.getElementById('volume-level');
    const volumeBar = document.querySelector('.volume-bar');
    
    // Album cards play buttons
    const albumPlayBtns = document.querySelectorAll('.album-card .play-btn');
    
    // Rating stars
    const ratingStars = document.querySelectorAll('.rating i');
    
    // Favorite buttons
    const favoriteBtns = document.querySelectorAll('.favorite');
    
    // State
    let isPlaying = false;
    let currentTrack = null;
    
    // Sample tracks (in a real app, this would come from the backend)
    const sampleTracks = [
        {
            id: 1,
            title: 'Summer Vibes',
            artist: 'Chill Wave',
            cover: 'album1.jpg',
            file: 'track1.mp3'
        },
        {
            id: 2,
            title: 'Midnight Dreams',
            artist: 'Synth Master',
            cover: 'album2.jpg',
            file: 'track2.mp3'
        }
    ];
    
    // Initialize player with a default track
    function initPlayer() {
        audioPlayer.volume = 0.7;
        updateVolumeDisplay();
        
        // Set up audio event listeners
        audioPlayer.addEventListener('timeupdate', updateProgress);
        audioPlayer.addEventListener('ended', handleTrackEnd);
        audioPlayer.addEventListener('loadedmetadata', updateTotalTime);
        
        // Set up UI event listeners
        progressBar.addEventListener('click', seek);
        volumeBar.addEventListener('click', changeVolume);
        
        playPauseBtn.addEventListener('click', togglePlayPause);
        prevTrackBtn.addEventListener('click', playPreviousTrack);
        nextTrackBtn.addEventListener('click', playNextTrack);
        shuffleBtn.addEventListener('click', toggleShuffle);
        repeatBtn.addEventListener('click', toggleRepeat);
        volumeBtn.addEventListener('click', toggleMute);
    }
    
    // Play a specific track
    function playTrack(track) {
        currentTrack = track;
        
        // Update audio source
        audioPlayer.src = `${URLROOT}/public/media/${track.file}`;
        
        // Update player UI
        nowPlayingCover.src = `${URLROOT}/public/uploads/${track.cover}`;
        nowPlayingTitle.textContent = track.title;
        nowPlayingArtist.textContent = track.artist;
        
        // Play the audio
        audioPlayer.play()
            .then(() => {
                isPlaying = true;
                updatePlayPauseIcon();
            })
            .catch(error => {
                console.error('Error playing track:', error);
            });
    }
    
    // Toggle play/pause
    function togglePlayPause() {
        if (!currentTrack) {
            // If no track is selected, play the first one
            if (sampleTracks.length > 0) {
                playTrack(sampleTracks[0]);
            }
            return;
        }
        
        if (isPlaying) {
            audioPlayer.pause();
        } else {
            audioPlayer.play();
        }
        
        isPlaying = !isPlaying;
        updatePlayPauseIcon();
    }
    
    // Update play/pause icon
    function updatePlayPauseIcon() {
        const icon = playPauseBtn.querySelector('i');
        if (isPlaying) {
            icon.classList.remove('fa-play');
            icon.classList.add('fa-pause');
        } else {
            icon.classList.remove('fa-pause');
            icon.classList.add('fa-play');
        }
    }
    
    // Update progress bar
    function updateProgress() {
        const duration = audioPlayer.duration;
        const currentTime = audioPlayer.currentTime;
        
        // Update progress bar width
        const progressPercent = (currentTime / duration) * 100;
        songProgress.style.width = `${progressPercent}%`;
        
        // Update current time display
        currentTimeEl.textContent = formatTime(currentTime);
    }
    
    // Format time in MM:SS
    function formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
    }
    
    // Update total time display
    function updateTotalTime() {
        totalTimeEl.textContent = formatTime(audioPlayer.duration);
    }
    
    // Seek to a specific position
    function seek(e) {
        const width = this.clientWidth;
        const clickX = e.offsetX;
        const duration = audioPlayer.duration;
        
        audioPlayer.currentTime = (clickX / width) * duration;
    }
    
    // Handle track end
    function handleTrackEnd() {
        // Implement logic for what happens when track ends
        // (e.g., play next track, repeat, etc.)
        playNextTrack();
    }
    
    // Play previous track
    function playPreviousTrack() {
        if (!currentTrack) return;
        
        const currentIndex = sampleTracks.findIndex(track => track.id === currentTrack.id);
        const prevIndex = (currentIndex - 1 + sampleTracks.length) % sampleTracks.length;
        playTrack(sampleTracks[prevIndex]);
    }
    
    // Play next track
    function playNextTrack() {
        if (!currentTrack) return;
        
        const currentIndex = sampleTracks.findIndex(track => track.id === currentTrack.id);
        const nextIndex = (currentIndex + 1) % sampleTracks.length;
        playTrack(sampleTracks[nextIndex]);
    }
    
    // Toggle shuffle
    function toggleShuffle() {
        this.classList.toggle('active');
        // Implement shuffle logic
    }
    
    // Toggle repeat
    function toggleRepeat() {
        this.classList.toggle('active');
        // Implement repeat logic
    }
    
    // Change volume
    function changeVolume(e) {
        const width = this.clientWidth;
        const clickX = e.offsetX;
        const volume = clickX / width;
        
        audioPlayer.volume = volume;
        updateVolumeDisplay();
    }
    
    // Update volume display
    function updateVolumeDisplay() {
        volumeLevel.style.width = `${audioPlayer.volume * 100}%`;
        
        // Update volume icon
        const icon = volumeBtn.querySelector('i');
        icon.classList.remove('fa-volume-up', 'fa-volume-down', 'fa-volume-mute');
        
        if (audioPlayer.volume > 0.5) {
            icon.classList.add('fa-volume-up');
        } else if (audioPlayer.volume > 0) {
            icon.classList.add('fa-volume-down');
        } else {
            icon.classList.add('fa-volume-mute');
        }
    }
    
    // Toggle mute
    function toggleMute() {
        audioPlayer.muted = !audioPlayer.muted;
        
        // Update volume icon
        const icon = volumeBtn.querySelector('i');
        if (audioPlayer.muted) {
            icon.classList.remove('fa-volume-up', 'fa-volume-down');
            icon.classList.add('fa-volume-mute');
        } else {
            updateVolumeDisplay();
        }
    }
    
    // Handle album play button clicks
    albumPlayBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get album card element
            const albumCard = this.closest('.album-card');
            const albumId = albumCard.dataset.albumId;
            
            // Get sample track associated with this album (in real app, fetch from server)
            const track = sampleTracks.find(t => t.id.toString() === albumId) || sampleTracks[0];
            
            // Toggle play/pause for this specific album
            if (currentTrack && currentTrack.id === track.id && isPlaying) {
                togglePlayPause();
            } else {
                playTrack(track);
                
                // Update all album play buttons
                albumPlayBtns.forEach(otherBtn => {
                    const icon = otherBtn.querySelector('i');
                    if (otherBtn === this) {
                        icon.classList.remove('fa-play');
                        icon.classList.add('fa-pause');
                    } else {
                        icon.classList.remove('fa-pause');
                        icon.classList.add('fa-play');
                    }
                });
            }
        });
    });
    
    // Handle rating stars
    ratingStars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            const ratingContainer = this.closest('.rating');
            
            // Reset all stars
            ratingContainer.querySelectorAll('i').forEach(s => {
                s.classList.remove('fas', 'active');
                s.classList.add('far');
            });
            
            // Fill stars up to the selected rating
            ratingContainer.querySelectorAll(`i[data-rating="${rating}"], i[data-rating="${rating}"] ~ i`).forEach(s => {
                if (s.dataset.rating <= rating) {
                    s.classList.remove('far');
                    s.classList.add('fas', 'active');
                }
            });
            
            // Here you would typically send the rating to the server
            console.log(`Rated: ${rating} stars`);
        });
    });
    
    // Handle favorite buttons
    favoriteBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            
            if (icon.classList.contains('far')) {
                // Add to favorites
                icon.classList.remove('far');
                icon.classList.add('fas');
                this.setAttribute('title', 'Remove from Favorites');
                console.log('Added to favorites');
            } else {
                // Remove from favorites
                icon.classList.remove('fas');
                icon.classList.add('far');
                this.setAttribute('title', 'Add to Favorites');
                console.log('Removed from favorites');
            }
        });
    });
    
    // Initialize the player
    initPlayer();
});
