const rgpd = document.getElementById("rgpd");
const rgpd_modal = document.getElementById("rgpd_modal");
const closeButton = rgpd_modal.querySelector(".close-button");


rgpd.addEventListener("click", () => {
        rgpd_modal.style.display = "flex";
});


closeButton.onclick = function () {
    rgpd_modal.style.display = "none";
};


window.onclick = function (event) {
    if (event.target == rgpd_modal) {
        rgpd_modal.style.display = "none";
    }
};
console.log("oui oui oui ")
