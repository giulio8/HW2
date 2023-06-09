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

let csrf_token = null;
if (document.head.querySelector('meta[name="csrf-token"]') !== null) {
    csrf_token = document.head.querySelector('meta[name="csrf-token"]').content;
}