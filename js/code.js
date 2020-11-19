function goBrrr() {
    console.log("my chaisaw go brrrr");
}

// Evita el renvio de formularios al refrescar la pagina
if (window.history.replaceState) { 
    window.history.replaceState(null, null, window.location.href);
}

function w3_open() {
    document.getElementById("mySidebar").style.display = "block";
}

function w3_close() {
    document.getElementById("mySidebar").style.display = "none";
}


