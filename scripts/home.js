//image slider/carousel
var images = [
  "../img/Factory-Tour383.png",
  "../img/Factory-Tour297.png",
  "../img/factory-outside-img.jpg",
];

var imageIndex = 0;

function changeImage(change) {
  imageIndex += change;
  if (imageIndex < 0) imageIndex = images.length - 1;
  if (imageIndex >= images.length) imageIndex = 0;
  document.getElementById("slider-img").src = images[imageIndex];
}

setInterval(function () {
  changeImage(1);
}, 5000);

//scroll to top button
let calcScrollValue = () => {
  let scrollProgress = document.getElementById("progress");
  let progressValue = document.getElementById("progress-value");
  let pos = document.documentElement.scrollTop;
  let calcHeight =
    document.documentElement.scrollHeight -
    document.documentElement.clientHeight;
  let scrollValue = Math.round((pos * 100) / calcHeight);
  if (pos > 100) {
    scrollProgress.style.display = "grid";
  } else {
    scrollProgress.style.display = "none";
  }
  scrollProgress.addEventListener("click", () => {
    document.documentElement.scrollTop = 0;
  });
  scrollProgress.style.background = `conic-gradient(#FF812E ${scrollValue}%, #d7d7d7 ${scrollValue}%)`;
};

window.onscroll = calcScrollValue;
window.onload = calcScrollValue;
