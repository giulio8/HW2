function getUserInfoRequest() {
    return fetch("/api/users/getUserInfo.php");
}

function updateUserInfoRequest(formData) {
    return fetch("/api/users/updateUserInfo.php", {
        method: "POST",
        body: formData
    });
}

function onSave(event) {
    const formData = new FormData(form);
    updateUserInfoRequest(formData).then(onSuccess, onError).then(json => {
        displayMessage(json.message);
        onCancel(event);
    }).catch(onErrorUsrReq);
}

function onCancel(event) {
    const id = event.target.parentElement.dataset.id;
    const input = form[id];
    input.disabled = true;
    const saveButton = getButton("save", id);
    saveButton.removeEventListener("click", onSave); // in alternativa rimuoverlo dal DOM, altrimenti l'utente potrebbe lo stesso inviare il form 
    const editButton = getButton("edit", id);
    const cancelButton = getButton("cancel", id);
    editButton.addEventListener("click", onEdit);
    cancelButton.removeEventListener("click", onCancel);

    hide(cancelButton);
    hide(saveButton);
    show(editButton);
    hide(saveButton);
    loadContent();
}

function onEdit(event) {
    const id = event.currentTarget.parentElement.dataset.id;
    const saveButton = getButton("save", id);
    const editButton = getButton("edit", id);
    const cancelButton = getButton("cancel", id);
    const input = form[id];
    input.disabled = false;
    input.focus();
    input.addEventListener("keypress", e => {if (e.key === "Enter") onSave(event);});
    saveButton.addEventListener("click", onSave);
    editButton.removeEventListener("click", onEdit);
    cancelButton.addEventListener("click", onCancel);

    show(cancelButton);
    hide(editButton);
    show(saveButton);
}

function fillForm(json) {
    console.log(json);
    form["username"].value = json.username;
    form["firstname"].value = json.firstname;
    form["lastname"].value = json.lastname;
    form["email"].value = json.email;
    form["birthdate"].value = json.birthdate;
    hideLoader();
}

function onErrorUsrReq(errorResp) {
    console.log(errorResp)
    errorResp.then(errors => {
        displayErrors(errors);
        hideLoader();
    });
}

function getButton(type, id) {
    //console.log(type, id);
    let buttonsDiv = document.querySelector("#"+id+"-input .buttons");
    return buttonsDiv.querySelector("." + type);
}

function showButtons(event) {
    const buttonsDiv = event.currentTarget.querySelector(".buttons");
    if (buttonsDiv !== null)
        show(buttonsDiv);
}

function hideButtons(event) {
    const buttonsDiv = event.currentTarget.querySelector(".buttons");
    if (buttonsDiv !== null)
        hide(buttonsDiv);
}

let inputDivs = document.querySelectorAll("div .input");
for (const inputDiv of inputDivs) {
    inputDiv.addEventListener("mouseover", showButtons);
    inputDiv.addEventListener("mouseout", hideButtons);
}

let editButtons = document.querySelectorAll(".edit");
for (const editButton of editButtons)
    editButton.addEventListener("click", onEdit);

function loadContent() {
    showLoader();
    getUserInfoRequest().then(onSuccess, onError).then(fillForm).catch(onErrorUsrReq);
}

function logout() {
    location.href = "/app/logout.php";
}

function hideLogoutModal() {
    hide(modalLogout);

    const closeButton = modalLogout.querySelector("#close-button");
    closeButton.removeEventListener("click", hideLogoutModal);
    const confirmButton = modalLogout.querySelector("#confirm-button");
    confirmButton.removeEventListener("click", logout);
}

function showLogoutModal() {
    show(modalLogout);
    const closeButton = modalLogout.querySelector("#close-button");
    closeButton.addEventListener("click", hideLogoutModal);
    const confirmButton = modalLogout.querySelector("#confirm-button");
    confirmButton.addEventListener("click", logout);
}

const form = document.forms["user"];
form.addEventListener("submit", e => e.preventDefault());

const logoutButton = document.querySelector("#logout");
logoutButton.addEventListener("click", showLogoutModal);

const modalLogout = document.querySelector("#modal-logout");

loadContent();