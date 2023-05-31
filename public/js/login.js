function setErrorsDisplay(errors) {
    if (errors.size > 0) {
        displayErrors(Array.from(errors));
    } else {
        hideMessages();
    }
}

setErrorsDisplay(errors);