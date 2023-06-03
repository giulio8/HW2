// image API /// ------------------------------

function destRequest() {
    return fetch("/api/destinazioni");
}

function postImageRequest(formData) {
    return fetch("/api/destinazioni/caricaDestinazione", {
        method: "POST",
        body: formData
    });
}

function deleteImageRequest(formTitolo) {
    return fetch("/api/destinazioni/eliminaDestinazione", {
        method: "POST",
        body: formTitolo
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
    window.location.href = "offerte/" + encodeURIComponent(titolo_destinazione);
}


function onAlbumReturned(json) {
    console.log(json)
    const list = document.querySelector("#gallery");
    //const directions = ["up", "down", "left", "right"];
    for (const i in json.data) {
        const img = json.data[i];
        formData = new FormData();
        formData.append("title", img.titolo);
        formData.append("description", img.descrizione);
        formData.append("image", img.immagine);
        fetch("api/destinazione", {
            method: "POST",
            body: formData
        }).then(resp => {return resp.text()}, onError).then(text => {
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