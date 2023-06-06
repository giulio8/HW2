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
    askConfirmModal("Sei sicuro di voler eliminare questa destinazione?").then(() => {
        const titolo = event.target.dataset.title;
        formTitolo = new FormData();
        formTitolo.append("titolo", titolo);
        deleteImageRequest(formTitolo).then(onSuccess, onError).then(json => {
            console.log(json);
            loadPage();
        });
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
    list.innerHTML = "";
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
        }).then(resp => { return resp.text() }, onError).then(text => {
            list.innerHTML += text;
            deleteButtons = document.querySelectorAll(".elimina");
            for (const button of deleteButtons) {
                const message = "Sei sicuro di voler eliminare la destinazione " + button.dataset.title + "?";
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

    showLoader();
    hide(modalAddDest);

    function onErrorImReq(errorResp) {
        errorResp.then(errors => {
            displayErrors(errors);
        });
    }

    postImageRequest(formdata).then(onSuccess, onError).then(json => {
        console.log(json);
        loadPage();
    }).catch(onErrorImReq)
    .finally(hideLoader);

}

function showModalAddDest(event) {
    const form = document.forms["postImage"];
    form.reset();
    show(modalAddDest);
}

function loadPage() {
    showLoader();
    destRequest().then(onSuccess, onError).then(onAlbumReturned)
    .finally(hideLoader);
}

loadPage();

const postButton = document.querySelector("#post-button");
// prevent button from submitting form
postButton.addEventListener("click", (event) => event.preventDefault());
postButton.addEventListener("mouseup", postImage);

const modalAddDest = document.querySelector("#modal-add-dest");

window.addEventListener("scroll", revealSection);

const addDestButton = document.querySelector("#aggiungi");
addDestButton.addEventListener("click", showModalAddDest);

const closeButton = document.querySelector("#close-button");
closeButton.addEventListener("click", e => {
    e.preventDefault(); 
    hide(modalAddDest);
});


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