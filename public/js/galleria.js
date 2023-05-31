// image API /// ------------------------------

function destRequest() {
    return fetch("/api/destinazioni/getDestinazioni.php");
}

function postImageRequest(formData) {
    return fetch("/api/destinazioni/caricaDestinazione.php", {
        method: "POST",
        body: formData
    });
}

function deleteImageRequest(formTitolo) {
    return fetch("/api/destinazioni/eliminaDestinazione.php", {
        method: "POST",
        body: formTitolo
    });
}

// --------------------------------------------
// coordinates api

function requestCoordinates(queryString) {
    return fetch("/api/openweather/coordinates.php?q=" + encodeURIComponent(queryString));
}
// --------------------------------------------
// flights api

function airportRequest(lat, lon) {
    return fetch("/api/voli/airport_by_coordinates.php?lat=" + lat + "&lon=" + lon);
}

// --------------------------------------------

function onImageClick(event) {
    showLoader();

    // we requst the coordinates of the place
    let place = event.target.dataset.title;
    requestCoordinates(place).then(onSuccess, onError).then(json => {
        console.log(json);
        let lat = json.lat;
        let lon = json.lon;

        airportRequest(lat, lon).then(onSuccess, onError).then(json => {
            console.log(json);
            let airport = json.iataCode;
            console.log(airport);
            // get tomorrow's date
            let tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            day_after = new Date();
            day_after.setDate(day_after.getDate() + 2);
            // we request the flights for the day after today
            getFlights("CTA", airport, tomorrow.toISOString().split("T")[0], day_after.toISOString().split("T")[0]);
        }).catch(onErrorFlReq);
    });
}

function onEliminaDestinazione(event) {
    const titolo = event.currentTarget.dataset.title;
    formTitolo = new FormData();
    formTitolo.append("titolo", titolo);
    deleteImageRequest(formTitolo).then(onSuccess, onError).then(json => {
        console.log(json);
        window.location.reload();
    });
}

function onTrovaVoliDestinazione(event) {
    const titolo_destinazione = event.currentTarget.dataset.title;
    console.log(titolo_destinazione);
    // We navigate to the flight offers page
    window.location.href = "/app/offerte/offerte.php?luogo=" + titolo_destinazione;
}


function onAlbumReturned(json) {
    console.log(json)
    const list = document.querySelector("#gallery");
    //const directions = ["up", "down", "left", "right"];
    for (const i in json.data) {
        const img = json.data[i];
        fetch("destinazione.php?title="+img.titolo+"&description="+img.descrizione+"&image="+img.immagine).then(resp => {return resp.text()}, onError).then(text => {
            list.innerHTML += text;
            deleteButtons = document.querySelectorAll(".elimina");
            for (const button of deleteButtons) {
                button.addEventListener("click", onEliminaDestinazione);
            }
            voliButtons = document.querySelectorAll(".trova-voli");
            for (const button of voliButtons) {
                button.addEventListener("click", onTrovaVoliDestinazione);
            }
        });
    }

}


function postImage(event) {
    let form = document.forms["postImage"];
    let formdata = new FormData(form);

    // we show the loading animation and hide all the form, then scroll to bottom
    showLoader();
    hide(modalAddDest);
    form.classList.add("hidden");


    // show the error message if the image is not uploaded
    // and hide the loading animation
    function onErrorImReq(errorResp) {
        errorResp.then(errors => {
            displayErrors(errors);
            hideLoader();
            form.classList.remove("hidden");
            window.scrollTo(0, document.body.scrollHeight);
        });
    }

    postImageRequest(formdata).then(onSuccess, onError).then(json => {
        console.log(json);
        location.reload();
    }).catch(onErrorImReq);

}

function showModalAddDest(event) {
    show(modalAddDest);
}

destRequest().then(onSuccess, onError).then(onAlbumReturned);

const postButton = document.querySelector("#post-button");
// prevent button from submitting form
postButton.addEventListener("click", (event) => event.preventDefault());
postButton.addEventListener("mouseup", postImage);

const modalAddDest = document.querySelector("#modal-add-dest");

window.addEventListener("scroll", revealSection);

const addDestButton = document.querySelector("#aggiungi");
addDestButton.addEventListener("click", showModalAddDest);

const closeButton = document.querySelector("#close-button");
closeButton.addEventListener("click", () => hide(modalAddDest));


function revealSection() {
    const reveals = document.querySelectorAll(".reveal");
    for (const reveal of reveals) {
        const windowHeight = window.innerHeight;
        const elementTop = reveal.getBoundingClientRect().top;
        let elementVisible = 150;
        if (reveal.classList.contains("descrizione"))
            elementVisible = -400;
        if (elementTop < windowHeight - elementVisible) {
            reveal.classList.add("active");
          } else {
            reveal.classList.remove("active");
          }
      }
}