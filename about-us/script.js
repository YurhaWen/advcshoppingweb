document.addEventListener("DOMContentLoaded", function () {
  function isElementInViewport(el) {
    const rect = el.getBoundingClientRect();
    return (
      rect.top >= 0 &&
      rect.left >= 0 &&
      rect.bottom <=
        (window.innerHeight || document.documentElement.clientHeight) &&
      rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
  }

  function fadeInOnScroll() {
    const elements = document.querySelectorAll(
      ".about-us-2, .vision-mission, .gambar-3"
    );
    elements.forEach(function (el) {
      if (isElementInViewport(el)) {
        el.classList.add("fade-in");
      }
    });
  }

  window.addEventListener("scroll", fadeInOnScroll);
  fadeInOnScroll();
});
