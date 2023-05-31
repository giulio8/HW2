const navbar = document.querySelector('#navbar');
const plane = document.querySelector('#plane');
const box = navbar.querySelector(".navbar-links-block");
const navbarRect = box.getBoundingClientRect();
const selectedLink = document.querySelector('.selected');
const linkWidth = selectedLink.getBoundingClientRect().width;
const planeWidth = plane.getBoundingClientRect().width;

function followMouse(event) {
    const mouseX = event.clientX - navbarRect.left;

    // Calculate the new X position for the plane
    const newX = mouseX - planeWidth / 2;

    // Limit the plane's movement within the navbar
    const minX = 0;
    const maxX = navbarRect.width - planeWidth;
    const clampedX = Math.max(minX, Math.min(newX, maxX));

    // Update the plane's position
    plane.style.transform = `translateX(${clampedX}px)`;

}

function goToPosition() {
    let linkOffsetLeft = selectedLink.getBoundingClientRect().left;

    let basePosition = linkOffsetLeft + linkWidth / 2;
    const newX = basePosition - planeWidth / 2 - navbarRect.left;
    plane.style.transform = `translateX(${newX}px)`;
}

navbar.addEventListener('mousemove', followMouse);
navbar.addEventListener('mouseleave', goToPosition);
window.addEventListener('resize', goToPosition);

goToPosition();

const menuToggle = document.querySelector('.menu-toggle');
const menu = document.querySelector('#links');

menuToggle.addEventListener('click', function () {
    document.body.classList.toggle('fix-body-scroll');
    menu.classList.toggle('active');
    menuToggle.classList.toggle('active');
});