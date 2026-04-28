console.log("le site fonctionne et c'est bien");

setTimeout(() => {
    const msg = document.getElementById("success-message");
    if (msg) {
        msg.style.display = "none";
    }
}, 5000);