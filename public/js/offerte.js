
function flightsRequest(origin, destination, departureDate, returnDate) {
    origin = encodeURIComponent(origin);
    destination = encodeURIComponent(destination);
    departureDate = encodeURIComponent(departureDate);
    returnDate = encodeURIComponent(returnDate);
    return fetch("/api/voli/getFlightOffers?origin=" + origin + "&destination=" + destination + "&departureDate=" + departureDate + "&returnDate=" + returnDate);
}

function getTicketElement(flight) {
    formData = new FormData();
    formData.append("flight", JSON.stringify(flight));
    return fetch("/biglietto", {
        method: "POST",
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrf_token
        }
    });
}

function bookFlightRequest(flight) {
    var formData = new FormData();
    formData.append('flight', JSON.stringify(flight));
    return fetch("/api/prenotazioni/bookFlight", {
        method: "POST",
        body: formData
    });
}

function prenotaVolo(event) {
    const id = event.target.dataset.flightId;
    const flight = flightsMap[id];
    console.log("flight: ", flight);
    bookFlightRequest(flight).then(onSuccess, onError).then(json => {
        console.log(json);
        alert("Prenotazione effettuata con successo!");
        window.location.href = "/prenotazioni";
    }).catch(onErrorBookingReq);
}

function showModalBooking(event) {
    show(modalBooking);
    const confirmButton = modalBooking.querySelector("#confirm-button");
    const closeButton = modalBooking.querySelector("#close-button");
    confirmButton.dataset.flightId = event.target.dataset.flightId;
    flightsMap[confirmButton.dataset.flightId].bagaglio = modalBooking.querySelector("#bagaglio").value;
    confirmButton.addEventListener("click", e => e.preventDefault());
    confirmButton.addEventListener("click", prenotaVolo);
    closeButton.addEventListener("click", e => e.preventDefault());
    closeButton.addEventListener("click", () => hide(modalBooking));
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
            buttonPrenota.addEventListener("click", showModalBooking);
            container.appendChild(buttonPrenota);
            content.appendChild(container);

            const details = container.querySelector(".details");
            details.addEventListener("hidden.bs.collapse", onHiddenFlightDetails);
            details.addEventListener("shown.bs.collapse", onShownFlightDetails);
        });
    }
    show(result);
}

function onErrorBookingReq(errorResp) {
    console.log(errorResp);
    errorResp.then(errors => {
        const errorDiv = document.querySelector("#booking-error");
        errorDiv.innerHTML = "";
        for (const error of errors) {
            p = document.createElement("p");
            p.textContent = error;
            errorDiv.appendChild(p);
            show(errorDiv);
        }
    });
}


function onErrorFlReq(errorResp) {
    console.log(errorResp);
    errorResp.then(errors => {
        displayErrors(errors);
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
    }).catch(onErrorFlReq)
        .finally(hideLoader);

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

const modalBooking = document.querySelector("#modal-booking");