function onSuccess(resp) {
    if (resp.ok === false) {
        console.log("Problem with the request");
        throw resp.json();
    }
    else {
        return resp.json();
    }
}

function onError(error) {
    console.log('Error: ' + error);
}

function hide(element) {
    element.classList.add("hidden");
}

function show(element) {
    element.classList.remove("hidden");
}