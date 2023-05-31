
function showLoader() {
    const loader = document.querySelector("#loader");
    loader.classList.remove("hidden");
}

function hideLoader() {
    const loader = document.querySelector("#loader");
    loader.classList.add("hidden");
}