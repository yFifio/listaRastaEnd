$(document).ready(function () {
  const audioPlayer = $("#audio-player")[0];
  const playPauseBtn = $("#play-pause-btn");
  const playIcon = "fa-play";
  const pauseIcon = "fa-pause";
  const icon = playPauseBtn.find("i");

  const progressBar = $(".progress-bar");
  const progressContainer = $(".progress-container");
  const currentTimeEl = $("#current-time");
  const totalTimeEl = $("#total-time");

  function formatTime(seconds) {
    if (isNaN(seconds)) {
      return "0:00";
    }
    const minutes = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${minutes}:${secs < 10 ? "0" : ""}${secs}`;
  }

  playPauseBtn.on("click", function () {
    if (audioPlayer.paused) {
      audioPlayer.play();
      icon.removeClass(playIcon).addClass(pauseIcon);
    } else {
      audioPlayer.pause();
      icon.removeClass(pauseIcon).addClass(playIcon);
    }
  });

  $(audioPlayer).on("timeupdate", function () {
    const { currentTime, duration } = audioPlayer;
    if (duration) {
      const progressPercent = (currentTime / duration) * 100;
      progressBar.css("width", `${progressPercent}%`);
      currentTimeEl.text(formatTime(currentTime));
    }
  });

  $(audioPlayer).on("loadedmetadata", function () {
    totalTimeEl.text(formatTime(audioPlayer.duration));
  });

  progressContainer.on("click", function (e) {
    const width = $(this).width();
    const clickX = e.offsetX;
    const duration = audioPlayer.duration;

    if (duration) {
      audioPlayer.currentTime = (clickX / width) * duration;
    }
  });

  $(audioPlayer).on("ended", function () {
    icon.removeClass(pauseIcon).addClass(playIcon);
  });
});
