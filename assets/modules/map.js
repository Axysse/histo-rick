let mapSpace;
let currentMarkers = L.featureGroup();
let defaultIcon;
let largeIcon;
var modal = document.getElementById("myModal");
let eventTitle = document.getElementById("eventTitle");
let eventImg = document.getElementById("eventImg");
let imgDesc = document.getElementById("imgDesc");
let eventText = document.getElementById("eventText");
let eventthemes = document.getElementById("eventThemes");
let eventYear = document.getElementById("eventYear");
var closeButton = document.getElementsByClassName("close-button")[0];
var linkList = document.getElementById("linkList");
let screenWidth = window.innerWidth;
let politicalEntitiesLayer = null;
let resultDiv;
let resultDivResponsive;
let paginationContainer;
let yearInput;
let yearInput2;
let typeInput;
let periodInput;
let themeInput;
let zoneInput;

let currentPage = 1;

let countriesLayer = null;
let selectedLayer = null;

function highlightFeature(e) {
    const layer = e.target;

    layer.setStyle({
        weight: 2,
        color: "#666",
        fillOpacity: 0.2,
    });

    layer.bringToFront();
}

function resetHighlight(e) {
    if (selectedLayer !== e.target) {
        countriesLayer.resetStyle(e.target);
    }
}

function loadCountriesLayer() {
    fetch("/geojson/world.geojson")
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            countriesLayer = L.geoJSON(data, {
                interactive: true,
                style: function () {
                    return {
                        color: "#444",
                        weight: 0,
                        fillOpacity: 0,
                    };
                },

                onEachFeature: function (feature, layer) {
                    const countryName = feature.properties.name;
                    const countryCode = feature.properties["ISO3166-1-Alpha-2"];

                    layer.bindTooltip(countryName, {
                        sticky: true,
                    });

                    layer.on("mouseover", function (e) {
                        highlightFeature(e);
                        mapSpace.getContainer().style.cursor = "pointer";
                    });
                    layer.on("mouseout", function (e) {
                        resetHighlight(e);
                        mapSpace.getContainer().style.cursor = "";
                    });

                    layer.on("click", function (e) {
                        L.DomEvent.stopPropagation(e);

                        if (selectedLayer === layer) {
                            countriesLayer.resetStyle(layer);
                            selectedLayer = null;
                            zoneInput.value = "";
                            console.log("Désélection du pays");
                        } else {
                            if (selectedLayer) {
                                countriesLayer.resetStyle(selectedLayer);
                            }

                            selectedLayer = layer;

                            layer.setStyle({
                                fillOpacity: 0.4,
                                fillColor: "#ff0000",
                                color: "#ff0000",
                                weight: 2,
                            });

                            mapSpace.fitBounds(layer.getBounds());
                            selectCountry(
                                feature.properties.name,
                                feature.properties["ISO3166-1-Alpha-2"],
                            );
                        }
                    });
                },
            }).addTo(mapSpace);
        });
}

function selectCountry(name, code) {
    zoneInput.value = name;

    console.log("Pays sélectionné :", name);
}

function openEvent(selectedEvent) {
    const imageBasePath = "https://tempusmundi.s3.fr-par.scw.cloud/";
    linkList.innerHTML = "";
    eventthemes.innerHTML = "";
    eventTitle.innerHTML = selectedEvent.title;
    eventYear.innerHTML = selectedEvent.year;
    eventImg.src = imageBasePath + selectedEvent.eventPicture;
    imgDesc.innerHTML = selectedEvent.pictureDesc;
    eventText.innerHTML = selectedEvent.eventText;
    console.log(selectedEvent.eventThemes);
    if (selectedEvent.eventThemes && selectedEvent.eventThemes.length > 0) {
        selectedEvent.eventThemes.forEach((theme) => {
            const span = document.createElement("span");
            span.classList.add("italic", "text-sm", "gap-2");
            span.textContent = theme;
            eventthemes.appendChild(span);
        });
    } else {
        eventthemes.textContent = "";
    }
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
    if (document.getElementById("map")._leaflet_id) {
        document.getElementById("map").remove();
    }

    const mapContainer = document.getElementById("map-container");
    if (mapContainer) {
        mapContainer.innerHTML = '<div id="map" style="height: 100%;"></div>';
    }

    const inputBttn = document.getElementById("inputBttn");
    resultDiv = document.getElementById("resultDiv");
    resultDivResponsive = document.getElementById("resultDivResponsive");
    paginationContainer = document.getElementById("paginationContainer");
    yearInput = document.getElementById("yearInput");
    yearInput2 = document.getElementById("yearInput2");
    typeInput = document.getElementById("typeInput");
    periodInput = document.getElementById("periodInput");
    themeInput = document.getElementById("themeInput");
    zoneInput = document.getElementById("zoneInput");

    mapSpace = L.map("map").setView([48.46, 0.06], 5);
    mapSpace.on("click", function () {
        if (selectedLayer) {
            countriesLayer.resetStyle(selectedLayer);
            selectedLayer = null;
            zoneInput.value = "";
        }
    });

    L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution:
            '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(mapSpace);

    currentMarkers.addTo(mapSpace);

    loadCountriesLayer();
    defaultIcon = L.icon({
        iconUrl: "https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png",
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
        iconUrl: "https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png",
        iconRetinaUrl:
            "https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon-2x.png",
        shadowUrl:
            "https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png",
        iconSize: [35, 57],
        iconAnchor: [17, 57],
        popupAnchor: [1, -40],
        shadowSize: [57, 57],
    });

    const min = parseInt(yearInput.min);
    const max = parseInt(yearInput.max);
    const rangeTrack = document.getElementById("rangeTrack");

    function updateDisplays() {
        currentYearDisplay.textContent = yearInput.value;
        currentYearDisplay2.textContent = yearInput2.value;
    }

    function syncRanges() {
        let minVal = parseInt(yearInput.value);
        let maxVal = parseInt(yearInput2.value);

        if (maxVal < minVal) {
            [minVal, maxVal] = [maxVal, minVal];
            yearInput.value = minVal;
            yearInput2.value = maxVal;
        }

        updateDisplays();
        updateTrack(minVal, maxVal);
    }

    function updateTrack(minVal, maxVal) {
        const sliderWidth = yearInput.offsetWidth;
        const thumbWidth = 30;
        const range = max - min;

        const usableWidth = sliderWidth - thumbWidth;

        const minPos = ((minVal - min) / range) * usableWidth + thumbWidth / 2;
        const maxPos = ((maxVal - min) / range) * usableWidth + thumbWidth / 2;

        rangeTrack.style.left = `${minPos}px`;
        rangeTrack.style.width = `${maxPos - minPos}px`;
    }

    yearInput.addEventListener("input", syncRanges);
    yearInput2.addEventListener("input", syncRanges);

    syncRanges();
    updateDisplays();

    inputBttn.addEventListener("click", () => {
        fetchEvents(1);
    });
}

function displayRoutes(routesData) {
    if (politicalEntitiesLayer) {
        politicalEntitiesLayer.clearLayers();
    } else {
        politicalEntitiesLayer = L.featureGroup().addTo(mapSpace);
    }
    routesData.forEach((route) => {
        try {
            const geojsonLayer = L.geoJSON(route.geojson, {
                style: {
                    color: route.color || "#3388ff",
                    weight: 2,
                    opacity: 0.8,
                    fillOpacity: 0.3,
                },
                onEachFeature: function (feature, layer) {
                    layer.bindPopup(`<strong>${route.name}</strong>`);
                },
            });

            geojsonLayer.eachLayer(function (layer) {
                politicalEntitiesLayer.addLayer(layer);
            });
        } catch (error) {
            console.error("Erreur dans le GeoJSON pour", route.name, error);
        }
    });
    if (countriesLayer) {
        countriesLayer.bringToFront();
    }
}

function fetchEvents(page) {
    currentPage = page;
    const payload = {
        year: yearInput.value,
        year2: yearInput2.value,
        type: typeInput.value,
        period: periodInput.value,
        theme: themeInput.value,
        zone: zoneInput.value,
        page: page,
    };

    if (payload.year && payload.year2) {
        resultDiv.innerHTML = "<p>Recherche en cours....</p>";
        fetch("/filter-events", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify(payload),
        })
            .then((response) => response.json())
            .then((data) => {
                displayEventsAndMarkers(data.events);
                displayRoutes(data.routes);
                updatePaginationUI(data.totalPages, data.currentPage);
            })
            .catch((error) => {
                console.error("Erreur:", error);
                resultDiv.innerHTML =
                    '<p class="text-danger">Une erreur est survenue.</p>';
            });
    } else {
        resultDiv.innerHTML = "<p>Veuillez sélectionner une année.</p>";
    }
}

function updatePaginationUI(totalPages, currentPage) {
    if (!paginationContainer) return;
    paginationContainer.innerHTML = "";

    if (totalPages > 1) {
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement("button");
            btn.innerText = i;
            btn.className =
                i === currentPage
                    ? "bg-blue-500 text-white p-2"
                    : "bg-gray-200 p-2";
            btn.onclick = () => fetchEvents(i);
            paginationContainer.appendChild(btn);
        }
    }
}

/**
 * Affiche les événements
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
        "p-2",
    );
    if (events.length === 0) {
        resultDiv.innerHTML =
            '<p class="alert alert-info">Aucun événement trouvé pour cette sélection.</p>';
        return;
    }

    events.forEach((event) => {
        const imageBasePath = "https://tempusmundi.s3.fr-par.scw.cloud/";
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
            "text-center",
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
                    currentZoom,
                );
                mapSpace.flyTo(newCenterLatLng, currentZoom, {
                    duration: 0.75,
                    easeLinearity: 0.5,
                });
            });
            marker.addEventListener("click", () => {
                openEvent(event);
            });
        } else {
            console.warn(
                `Coordonnées invalides pour l'événement: ${event.title}`,
                event,
            );
        }
    });

    if (screenWidth > 1024) {
        resultDiv.appendChild(ul);
    } else {
        resultDivResponsive.innerHTML = "";
        resultDivResponsive.appendChild(ul);
    }
}
