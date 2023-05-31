function displayErrors(errors) {
    var errorDiv = document.querySelector("#error div");
    errorDiv.innerHTML = "";
    for (let i in errors) {
        let p = document.createElement("p");
        p.textContent = errors[i];
        errorDiv.appendChild(p);

        errorDiv.parentElement.classList.remove("hidden");
    }
}

function displayMessage(message) {
    var successDiv = document.querySelector("#success div");
    successDiv.innerHTML = "";
    let p = document.createElement("p");
    p.textContent = message;
    successDiv.appendChild(p);

    successDiv.parentElement.classList.remove("hidden");
}

function hideMessages() {
    const messages = document.querySelectorAll("#error, #success");
    for (const message of messages) {
        message.classList.add("hidden");
        message.querySelector("div").innerHTML = "";
    }
}

function hideMessage(event) {
    console.log(event.currentTarget);
    const message = event.currentTarget.parentElement;
    hide(message);
}

const dismissIcons = document.querySelectorAll("#error .icon, #success .icon");
for (const dismissIcon of dismissIcons) {
    dismissIcon.addEventListener("click", event => {
        event.currentTarget.parentElement.classList.add("hidden");
        event.currentTarget.parentElement.querySelector("div").innerHTML = "";
    });
}

const closeMessageButtons = document.querySelectorAll(".close-message");
for (const closeMessageButton of closeMessageButtons)
    closeMessageButton.addEventListener("click", hideMessage);