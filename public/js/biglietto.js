function onShownFlightDetails(event) {
    const showDetailsButton = event.currentTarget.parentElement.querySelector(".show-details");
    showDetailsButton.querySelector(".text").textContent = "Nascondi dettagli";
    showDetailsButton.querySelector(".arrow-details").src = "/assets/caret-up.png"; 
}

function onHiddenFlightDetails(event) {
    const showDetailsButton = event.currentTarget.parentElement.querySelector(".show-details");
    showDetailsButton.querySelector(".text").textContent = "Mostra dettagli";
    showDetailsButton.querySelector(".arrow-details").src = "/assets/caret-down.png";
}