function prenotazioniRequest() {
    return fetch("/api/prenotazioni/getPrenotazioni.php");
}

function cancellaPrenotazioneRequest(id) {
    formData = new FormData();
    formData.append("id", id);
    return fetch("/api/prenotazioni/eliminaPrenotazione.php", {
        method: "POST",
        body: formData
    });
}

function getTicketElement(flight) {
    formData = new FormData();
    formData.append("flight", JSON.stringify(flight));
    return fetch("/app/biglietto/biglietto.php", {
        method: "POST",
        body: formData
    });
}

//let flightsMap = {};

function cancellaPrenotazione(event) {
    const id = event.currentTarget.dataset.flightId;
    cancellaPrenotazioneRequest(id).then(onSuccess, onError).then(json => {
        console.log(json);
        alert("Prenotazione cancellata con successo!");
        location.reload();
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
            const buttonCancella = document.createElement("button");
            buttonCancella.classList.add("app-dismiss-button", "cancella");
            buttonCancella.textContent = "Cancella prenotazione";
            buttonCancella.dataset.flightId = flight.id;
            buttonCancella.addEventListener("click", cancellaPrenotazione);
            container.appendChild(buttonCancella);
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
    });
}

showLoader();
prenotazioniRequest().then(onSuccess, onError).then(json => {
    createTickets(json);
    hideLoader();
}).catch(onErrorFlReq);


const result = document.querySelector("#result");