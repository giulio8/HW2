const ticket = document.createElement("div");
ticket.classList.add("ticket");
const h2 = document.createElement("h2");
h2.textContent = "Biglietto aereo";
ticket.appendChild(h2);
const h3 = document.createElement("h3");
h3.textContent = "Tratte:";
ticket.appendChild(h3);
const segmentsBox = document.createElement("div");
segmentsBox.classList.add("segments-box");
ticket.appendChild(segmentsBox);
for (let i = 0; i < flight.itineraries.length; i++) {
    const itinerary = flight.itineraries[i];
    const itineraryBox = document.createElement("div");
    const h2 = document.createElement("h2");
    if (i == 0)
        h2.textContent = "Andata";
    else
        h2.textContent = "Ritorno";
    itineraryBox.appendChild(h2);
    for (let i = 0; i < itinerary.segments.length; i++) {
        const segment = itinerary.segments[i];
        const segmentBox = document.createElement("div");
        segmentBox.classList.add("segment");
        const h4 = document.createElement("h4");
        h4.textContent = "Tratta " + (i + 1) + " " + segment.departure.iataCode + "-" + segment.arrival.iataCode;
        segmentBox.appendChild(h4);
        const departure = document.createElement("div");
        departure.classList.add("departure");
        const h3Departure = document.createElement("h3");
        h3Departure.textContent = "Partenza";
        departure.appendChild(h3Departure);
        city = document.createElement("span");
        city.textContent = segment.departure.city + " (" + segment.departure.iataCode + ")";
        departure.appendChild(city);
        const p2 = document.createElement("p");
        p2.textContent = "Orario della partenza: " + new Date(segment.departure.at).toLocaleString("it-IT");
        departure.appendChild(p2);
        segmentBox.appendChild(departure);
        const arrival = document.createElement("div");
        arrival.classList.add("arrival");
        const h3Arrival = document.createElement("h3");
        h3Arrival.textContent = "Arrivo";
        arrival.appendChild(h3Arrival);
        city = document.createElement("span");
        city.textContent = segment.arrival.city + " (" + segment.arrival.iataCode + ")";
        arrival.appendChild(city);
        const p4 = document.createElement("p");
        p4.textContent = "Orario di arrivo: " + new Date(segment.arrival.at).toLocaleString("it-IT");
        arrival.appendChild(p4);
        segmentBox.appendChild(arrival);
        segmentsBox.appendChild(segmentBox);
        itineraryBox.appendChild(segmentBox);
    }
    ticket.appendChild(itineraryBox);
}
const price = document.createElement("div");
price.classList.add("price");
price.textContent = "Prezzo complessivo: " + flight.price.total + " " + flight.price.currency;
ticket.appendChild(price);
const result = document.querySelector("#result");
result.appendChild(ticket);
result.classList.remove("hidden");