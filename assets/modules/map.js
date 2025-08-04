let mapSpace;
let currentMarkers = L.featureGroup();
let defaultIcon;
let largeIcon;
var modal = document.getElementById("myModal");
let eventTitle = document.getElementById("eventTitle");
let eventImg = document.getElementById("eventImg");
let eventText = document.getElementById("eventText");
let eventYear = document.getElementById("eventYear");
var closeButton = document.getElementsByClassName("close-button")[0];
var linkList = document.getElementById("linkList");
let screenWidth = window.innerWidth;

function openEvent(selectedEvent) {
    const imageBasePath = "/upload/";
    linkList.innerHTML = "";
    eventTitle.innerHTML = selectedEvent.title;
    eventYear.innerHTML = selectedEvent.year;
    eventImg.src = imageBasePath + selectedEvent.eventPicture;
    eventText.innerHTML = selectedEvent.eventText;
    modal.style.display = "flex";

    console.log(selectedEvent.link);
    const li = document.createElement("li");
    li.classList.add("mt-2");
    const a = document.createElement("a");
    a.href = selectedEvent.link;
    a.innerHTML = ` ${selectedEvent.link}`;
    a.classList.add("text-blue-700");
    li.appendChild(a);
    linkList.appendChild(li);
    linkList.classList.add("mt-4");

    closeButton.onclick = function () {
        modal.style.display = "none";
    };
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
}

export function map() {
    const inputBttn = document.getElementById("inputBttn");
    const resultDiv = document.getElementById("resultDiv");
    const resultDivResponsive = document.getElementById("resultDivResponsive");
    const yearInput = document.getElementById("yearInput");
    const yearInput2 = document.getElementById("yearInput2");
    const typeInput = document.getElementById("typeInput");
    const periodInput = document.getElementById("periodInput");
    const themeInput = document.getElementById("themeInput");

    if (!mapSpace) {
        mapSpace = L.map("map").setView([48.46, 0.06], 5);

        L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution:
                '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(mapSpace);

        currentMarkers.addTo(mapSpace);
        defaultIcon = L.icon({
            iconUrl:
                "https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png",
            iconRetinaUrl:
                "https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon-2x.png",
            shadowUrl:
                "https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png",
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41],
        });

        largeIcon = L.icon({
            iconUrl:
                "https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png",
            iconRetinaUrl:
                "https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon-2x.png",
            shadowUrl:
                "https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png",
            iconSize: [35, 57],
            iconAnchor: [17, 57],
            popupAnchor: [1, -40],
            shadowSize: [57, 57],
        });
    }

    function updateDisplays() {
        currentYearDisplay.textContent = yearInput.value;
        currentYearDisplay2.textContent = yearInput2.value;
    }

    yearInput2.min = yearInput.value;
    updateDisplays();

    yearInput.addEventListener("input", function () {
        yearInput2.min = this.value;
        if (parseInt(yearInput2.value) < parseInt(this.value)) {
            yearInput2.value = this.value;
        }
        updateDisplays();
    });

    yearInput2.addEventListener("input", function () {
        updateDisplays();
    });

    inputBttn.addEventListener("mouseover", () => {
        inputBttn.classList.add("cursor-pointer");
    });

    inputBttn.addEventListener("mouseout", () => {
        inputBttn.classList.remove("cursor-pointer");
    });

    inputBttn.addEventListener("click", () => {
        let selectedYear = yearInput.value;
        let selectedYear2 = yearInput2.value;
        let selectedType = typeInput.value;
        let selectedTheme = themeInput.value;
        let selectedPeriod = periodInput.value;
        if (selectedYear && selectedYear2) {
            fetch("/filter-events", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: JSON.stringify({
                    year: selectedYear,
                    year2: selectedYear2,
                    type: selectedType,
                    period: selectedPeriod,
                    theme: selectedTheme,
                }),
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Erreur réseau ou réponse du serveur");
                    }
                    return response.json();
                })
                .then((events) => {
                    displayEventsAndMarkers(events);
                })
                .catch((error) => {
                    console.error(
                        "Erreur lors de la récupération des données:",
                        error
                    );
                    resultDiv.innerHTML =
                        '<p class="text-danger">Une erreur est survenue lors du chargement des données.</p>';
                });
        } else {
            resultDiv.innerHTML = "<p>Veuillez sélectionner une année.</p>";
        }
    });
}
/**
 * Affiche les événements dans la liste et sur la carte
 * @param {Array} events -
 */
function displayEventsAndMarkers(events) {
    currentMarkers.clearLayers();
    resultDiv.innerHTML = "";

    const ul = document.createElement("ul");
    ul.classList.add(
        "flex",
        "flex-col",
        "gap-4",
        "overflow-y-scroll",
        "max-h-[65vh]",
        "p-2"
    );
    if (events.length === 0) {
        resultDiv.innerHTML =
            '<p class="alert alert-info">Aucun événement trouvé pour cette sélection.</p>';
        return;
    }

    events.forEach((event) => {
        const imageBasePath = "/upload/";
        const eventImageUrl = imageBasePath + event.eventPicture;
        const li = document.createElement("li");
        li.classList.add(
            "flex",
            "flex-col",
            "items-center",
            "justify-center",
            "mt-2",
            "text-white",
            "p-4",
            "border",
            "rounded",
            "w-full",
            "h-34",
            "flex-shrink-0",
            "flex-grow-0",
            "relative",
            "overflow-hidden",
            "text-center"
        );
        li.style.backgroundImage = `url('${eventImageUrl}')`;
        li.style.backgroundSize = "cover";
        li.style.backgroundPosition = "center";
        li.style.backgroundRepeat = "no-repeat";
        li.style.textShadow = "1px 1px 2px black";

        li.style.position = "relative";
        li.style.overflow = "hidden";

        const overlayDiv = document.createElement("div");
        overlayDiv.style.position = "absolute";
        overlayDiv.style.top = "0";
        overlayDiv.style.left = "0";
        overlayDiv.style.right = "0";
        overlayDiv.style.bottom = "0";
        overlayDiv.style.backgroundColor = "rgba(0, 0, 0, 0.3)";
        overlayDiv.style.zIndex = "0";

        li.appendChild(overlayDiv);

        const h = document.createElement("h2");
        h.innerText = event.title;
        h.classList.add("text-xl", "font-bold", "text-white");
        h.style.zIndex = "1";

        const p = document.createElement("p");
        p.innerText = event.shortText;
        p.classList.add("text-white");
        p.style.zIndex = "1";

        li.appendChild(h);
        li.appendChild(p);
        ul.appendChild(li);

        if (
            event.x &&
            event.y &&
            typeof event.x === "number" &&
            typeof event.y === "number"
        ) {
            const marker = L.marker([event.x, event.y], {
                icon: defaultIcon,
            }).addTo(currentMarkers);

            const initialPopupContent = `<b>${event.title}</b>`;
            marker
                .bindPopup(initialPopupContent, {
                    closeOnClick: false,
                    autoClose: false,
                    closeButton: false,
                })
                .openPopup();

            const hoverPopupContent = `
                <div style="text-align: center;" class="flex flex-col items-center">
                    <b>${event.title}</b><br>
                    <span>${event.year}</span><br>
                    <img src="${eventImageUrl}" alt="${event.title}" style="width: 100px; height: auto; margin-top: 5px;">
                </div>
            `;

            marker.on("mouseover", function () {
                this.closePopup();
                this.bindPopup(hoverPopupContent, {
                    closeOnClick: false,
                    autoClose: false,
                    closeButton: false,
                }).openPopup();
            });

            marker.on("mouseout", function () {
                this.closePopup();
                this.bindPopup(initialPopupContent, {
                    closeOnClick: false,
                    autoClose: false,
                    closeButton: false,
                }).openPopup();
            });

            li.addEventListener("mouseover", () => {
                marker.closePopup();
                marker.setIcon(largeIcon);
                marker
                    .bindPopup(hoverPopupContent, {
                        closeOnClick: false,
                        autoClose: false,
                        closeButton: false,
                    })
                    .openPopup();
                li.classList.remove("bg-white");
                li.classList.add("bg-gray-200", "cursor-pointer");
            });

            li.addEventListener("mouseout", () => {
                marker.closePopup();
                marker.setIcon(defaultIcon);
                marker
                    .bindPopup(initialPopupContent, {
                        closeOnClick: false,
                        autoClose: false,
                        closeButton: false,
                    })
                    .openPopup();
                li.classList.remove("bg-gray-200", "cursor-pointer");
                li.classList.add("bg-white");
            });

            li.addEventListener("click", () => {
                openEvent(event);
                const targetLatLng = L.latLng(event.x, event.y);
                const currentZoom = 7;
                const targetPoint = mapSpace.project(targetLatLng, currentZoom);
                const offsetPoint = L.point(targetPoint.x - 200, targetPoint.y);
                const newCenterLatLng = mapSpace.unproject(
                    offsetPoint,
                    currentZoom
                );
                mapSpace.flyTo(newCenterLatLng, currentZoom, {
                    duration: 0.75,
                    easeLinearity: 0.5, //
                });
            });
            marker.addEventListener("click", () => {
                openEvent(event);
            });
        } else {
            console.warn(
                `Coordonnées invalides pour l'événement: ${event.title}`,
                event
            );
        }
    });

    if (screenWidth > 1024) {
        resultDiv.appendChild(ul);
    } else {
        resultDivResponsive.appendChild(ul);
    }

    // if (currentMarkers.getLayers().length > 0) {
    //     mapSpace.fitBounds(currentMarkers.getBounds());
    // }
}
