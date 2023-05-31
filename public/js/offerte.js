
function flightsRequest(origin, destination, departureDate, returnDate) {
    origin = encodeURIComponent(origin);
    destination = encodeURIComponent(destination);
    departureDate = encodeURIComponent(departureDate);
    returnDate = encodeURIComponent(returnDate);
    return fetch("/api/voli/getFlightOffers.php?origin=" + origin + "&destination=" + destination + "&departureDate=" + departureDate + "&returnDate=" + returnDate);
}

function getTicketElement(flight) {
    formData = new FormData();
    formData.append("flight", JSON.stringify(flight));
    return fetch("/app/biglietto/biglietto.php", {
        method: "POST",
        body: formData
    });
}

function bookFlightRequest(flight) {
    var formData = new FormData();
    formData.append('flight', JSON.stringify(flight));
    return fetch("/api/prenotazioni/bookFlight.php", {
        method: "POST",
        body: formData
    });
}

function prenotaVolo(event) {
    const id = event.currentTarget.dataset.flightId;
    const flight = flightsMap[id];
    console.log("flight: ", flight);
    bookFlightRequest(flight).then(onSuccess, onError).then(json => {
        console.log(json);
        alert("Prenotazione effettuata con successo!");
        window.location.href = "/app/prenotazioni/prenotazioni.php";
    }).catch(onErrorFlReq);
}

function onShownFlightDetails(event) {
    const showDetailsButton = event.currentTarget.parentElement.querySelector(".show-details");
    showDetailsButton.querySelector(".text").textContent = "Nascondi dettagli";
    showDetailsButton.querySelector(".arrow-details").src = "/app/assets/caret-up.png"; 
}

function onHiddenFlightDetails(event) {
    const showDetailsButton = event.currentTarget.parentElement.querySelector(".show-details");
    showDetailsButton.querySelector(".text").textContent = "Mostra dettagli";
    showDetailsButton.querySelector(".arrow-details").src = "/app/assets/caret-down.png";
}

let flightsMap = {};

function createTickets(flights) {
    const content = result.querySelector(".result-content");
    flightsMap = {};
    content.innerHTML = "";
    for (let flight of flights) {
        flightsMap[flight.id] = flight;
        getTicketElement(flight).then(resp => resp.text(), onError).then(html => {
            const container = document.createElement("div");
            container.classList.add("container");
            container.innerHTML = html;
            const buttonPrenota = document.createElement("button");
            buttonPrenota.classList.add("app-button", "prenota");
            buttonPrenota.textContent = "Prenota volo";
            buttonPrenota.dataset.flightId = flight.id;
            buttonPrenota.addEventListener("click", prenotaVolo);
            container.appendChild(buttonPrenota);
            content.appendChild(container);

            const details = container.querySelector(".details");
            details.addEventListener("hidden.bs.collapse", onHiddenFlightDetails);
            details.addEventListener("shown.bs.collapse", onShownFlightDetails);
        });
    }
    show(result);
}


function onErrorFlReq(errorResp) {
    console.log(errorResp);
    errorResp.then(errors => {
        displayErrors(errors);
        hideLoader();
        form.classList.remove("hidden");
        window.scrollTo(0, document.body.scrollHeight);
    });
}

function search(event) {
    event.preventDefault();
    const formData = new FormData(form);

    const origin = formData.get("origin");
    const destination = formData.get("destination");
    const departureDate = formData.get("departureDate");
    const returnDate = formData.get("returnDate");

    hideMessages();
    showLoader();
    flightsRequest(origin, destination, departureDate, returnDate).then(onSuccess, onError).then(json => {
        console.log(json);
        let flights = json;
        // we create the ticket element
        createTickets(flights);
        hide(form);
        show(backButton);
        hideLoader();
    }).catch(onErrorFlReq);

}

function back(event) {
    form.reset();
    show(form);
    hide(backButton);
    hide(result);
}

const form = document.querySelector("#search-form");
form.addEventListener("submit", e => e.preventDefault());
const searchButton = document.querySelector("#search-button");
searchButton.addEventListener("click", search);
const backButton = document.querySelector("#back-button");
backButton.addEventListener("click", back);

const result = document.querySelector("#result");