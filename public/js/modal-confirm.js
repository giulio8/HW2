const modalConfirm = document.querySelector("#modal-confirm");
const confirmButtonModalConf = modalConfirm.querySelector(".confirm-button");
const dismissButtonModalConf = modalConfirm.querySelector(".dismiss-button");

/* function askConfirmModal(event, message, onConfirm, onDismiss = () => {}) {
    setConfirmModalMessage(message);
    show(modalConfirm);
    confirmButtonModalConf.addEventListener("click", onConfirm(event));
    dismissButtonModalConf.addEventListener("click", () => {
        hide(modalConfirm);
        onDismiss(event);
    });
} */

// better approach with promises
function askConfirmModal(message) {
    setConfirmModalMessage(message);
    show(modalConfirm);
    return new Promise((resolve, reject) => {
        confirmButtonModalConf.addEventListener("click", () => {
            hide(modalConfirm);
            resolve();
        });
        dismissButtonModalConf.addEventListener("click", () => {
            hide(modalConfirm);
            reject();
        });
    });
}

function dismissConfirmModal() {
    hide(modalConfirm);
    const modalContent = modalConfirm.querySelector(".modal-content");
    modalContent.innerHTML = "";
}

function setConfirmModalMessage(message) {
    const modalContent = modalConfirm.querySelector(".modal-content");
    modalContent.querySelector('.message').textContent = message;
}