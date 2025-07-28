let mapSpace;
let currentMarkers = L.featureGroup();
let defaultIcon;
let largeIcon;
var modal = document.getElementById("myModal");
let eventTitle = document.getElementById("eventTitle");
let eventImg = document.getElementById("eventImg");
let eventText = document.getElementById("eventText");
var closeButton = document.getElementsByClassName("close-button")[0];
var linkList = document.getElementById("linkList");

function openEvent(selectedEvent) {
const imageBasePath = '/upload/';
  linkList.innerHTML = "";
//   console.log(selectedEvent);
  eventTitle.innerHTML = selectedEvent.title;
  eventImg.src = imageBasePath + selectedEvent.eventPicture;
  eventText.innerHTML = selectedEvent.eventText;
  modal.style.display = "flex";

//   selectedEvent.links.forEach((link) => {
//     console.log(link);
//     const li = document.createElement("li");
//     li.classList.add("mt-2")
//     const a = document.createElement("a");
//     a.href = link;
//     a.innerHTML = ` ${link}`;
//     a.classList.add("text-blue-700")
//     li.appendChild(a);
//     linkList.appendChild(li);
//     linkList.classList.add("mt-4")
//   });

  closeButton.onclick = function () {
    modal.style.display = "none";
  };
  window.onclick = function (event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  };
}

export function map(){
const inputBttn = document.getElementById("inputBttn")
const resultDiv = document.getElementById("resultDiv")
const yearInput = document.getElementById("yearInput")
    if (!mapSpace) {
        mapSpace = L.map("map").setView([48.46, 0.06], 5);

        L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution:
                '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(mapSpace);

        currentMarkers.addTo(mapSpace);
                defaultIcon = L.icon({
    iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png',
    iconRetinaUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon-2x.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
        });

        largeIcon = L.icon({
       iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png',
    iconRetinaUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon-2x.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
    iconSize: [35, 57],
    iconAnchor: [17, 57],
    popupAnchor: [1, -40],
    shadowSize: [57, 57]
        });
    }

yearInput.addEventListener("input", () => {
  currentYearDisplay.textContent = yearInput.value;
});

inputBttn.addEventListener("click", () => {
    let selectedYear = yearInput.value
    if (selectedYear) {
            fetch('/filter-events-by-year', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ year: selectedYear })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau ou réponse du serveur');
                }
                return response.json();
            })
            .then(events => {
            displayEventsAndMarkers(events);
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des données:', error);
                resultDiv.innerHTML = '<p class="text-danger">Une erreur est survenue lors du chargement des données.</p>';
            });
        } else {
            resultDiv.innerHTML = '<p>Veuillez sélectionner une année.</p>';
        }
    });
}

/**
 * Affiche les événements dans la liste et sur la carte
 * @param {Array} events -
 */
function displayEventsAndMarkers(events) {

    currentMarkers.clearLayers();
    resultDiv.innerHTML = '';

    const ul = document.createElement("ul");
    if (events.length === 0) {
        resultDiv.innerHTML = '<p class="alert alert-info">Aucun événement trouvé pour cette année.</p>';
        return;
    }

    events.forEach(event => {
        const li = document.createElement("li");
        li.classList.add("flex", "flex-col", "items-center", "mt-4", "bg-white", "p-4", "border", "rounded");

        const h = document.createElement("h2");
        h.innerText = event.title;
        h.classList.add("text-xl", "font-bold");

        const p = document.createElement("p");
        p.innerText = event.shortText;

        li.appendChild(h);
        li.appendChild(p);
        ul.appendChild(li);


        if (event.x && event.y && typeof event.x === 'number' && typeof event.y === 'number') {
            const marker = L.marker([event.x, event.y], { icon: defaultIcon }).addTo(currentMarkers);


            const popupContent = event.title;
            marker.bindPopup(popupContent, { closeOnClick: false, autoClose: false, closeButton: false }).openPopup();


            li.addEventListener("mouseover", () => {
                marker.setIcon(largeIcon);
                li.classList.remove("bg-white");
                li.classList.add("bg-gray-200");
            });

            li.addEventListener("mouseout", () => {
                marker.setIcon(defaultIcon);
                li.classList.remove("bg-gray-200");
                li.classList.add("bg-white");
            });

            li.addEventListener("click", () => {
                openEvent(event);
                mapSpace.flyTo([event.x, event.y], 10);
            });

            marker.addEventListener("click", () => {
                openEvent(event);
            });
        } else {
            console.warn(`Coordonnées invalides pour l'événement: ${event.title}`, event);
        }
    });

    resultDiv.appendChild(ul);

    // if (currentMarkers.getLayers().length > 0) {
    //     mapSpace.fitBounds(currentMarkers.getBounds());
    // }
}



