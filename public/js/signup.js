function jsonCheckUsername(json) {
    const input = form["username"];
    const usernameBox = document.querySelector('#username-input');
    // Controllo il campo exists ritornato dal JSON
    if (!json.exists) {
        errors.delete("Nome utente già utilizzato");
        usernameBox.classList.remove('error');
    } else {
        errors.add("Nome utente già utilizzato");
        usernameBox.classList.add('error');
    }
    setErrorsDisplay(errors);

}

function jsonCheckEmail(json) {
    const input = form["email"];
    const emailBox = document.querySelector('#email-input');
    // Controllo il campo exists ritornato dal JSON
    if (!json.exists) {
        errors.delete("Email già utilizzata");
        emailBox.classList.remove('error');
    } else {
        errors.add("Email già utilizzato");
        emailBox.classList.add('error');
    }
    setErrorsDisplay(errors);

}

function fetchResponse(response) {
    if (!response.ok) return null;
    return response.json();
}

function setErrorsDisplay(errors) {
    if (errors.size > 0) {
        displayErrors(Array.from(errors));
    } else {
        hideMessages();
    }
}

function checkUsername(event) {
    const input = form["username"];
    const usernameBox = document.querySelector('#username-input');

    if(!/^[a-zA-Z0-9_]{1,15}$/.test(input.value)) {
        errors.add("Sono ammesse lettere, numeri e underscore. Max. 15");
        usernameBox.classList.add('error');

    } else {
        errors.delete("Sono ammesse lettere, numeri e underscore. Max. 15");
        usernameBox.classList.remove('error');
        fetch("/api/users/username_exists.php?username="+encodeURIComponent(input.value)).then(fetchResponse).then(jsonCheckUsername);
    }    
    setErrorsDisplay(errors);
}

function checkEmail(event) {
    const emailInput = form["email"];
    const emailBox = document.querySelector('#email-input');
    if(!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(String(emailInput.value).toLowerCase())) {
        errors.add("Email non valida");
        emailBox.classList.add('error');

    } else {
        errors.delete("Email non valida");
        fetch("/api/users/email_exists.php?q="+encodeURIComponent(String(emailInput.value).toLowerCase())).then(fetchResponse).then(jsonCheckEmail);
    }
    setErrorsDisplay(errors);
}

function checkPassword(event) {
    const passwordInput = form["password"];
    const passwordBox = document.querySelector('#password-input');
    if (passwordInput.value.length >= 8) {
        errors.delete("La password deve essere lunga almeno 8 caratteri");
        passwordBox.classList.remove('error');
    } else {
        errors.add("La password deve essere lunga almeno 8 caratteri");
        passwordBox.classList.add('error');
    }
    setErrorsDisplay(errors);

}

function checkConfirmPassword(event) {
    const confirmPasswordInput = form["confirm-password"];
    const confirmPasswordBox = document.querySelector('#confirm-password-input');
    if (confirmPasswordInput.value === form["password"].value) {
        errors.delete("Le password non coincidono");
        confirmPasswordBox.classList.remove('error');
    } else {
        errors.add("Le password non coincidono");
        confirmPasswordBox.classList.add('error');
    }
    setErrorsDisplay(errors);
}


const form = document.querySelector('#signup-form');
form["username"].addEventListener('blur', checkUsername);
form["password"].addEventListener('blur', checkPassword);
form["confirm-password"].addEventListener('blur', checkConfirmPassword);

//let errors = new Set();
setErrorsDisplay(errors);
