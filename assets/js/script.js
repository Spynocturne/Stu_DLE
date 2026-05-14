console.log("le site fonctionne et c'est bien");

/*Message de connexion suprimé au bout de 5s*/
setTimeout(() => {
    const msg = document.getElementById("success-message");
    if (msg) {
        msg.style.display = "none";
    }
}, 5000);

/*Focus le texte directement sur test sans cliquer dessus */
document.addEventListener("DOMContentLoaded", () => {
    const input = document.querySelector("input[name='prenom']");
    if (input) {
        input.focus();
    }
});

/*désactive le bouton de test du jeu si vide*/
const form = document.querySelector("form");
const input = document.querySelector("input[name='prenom']");
const button = document.querySelector("button[name='guess']");

if (form && input && button) {
    button.disabled = true;

    input.addEventListener("input", () => {
        button.disabled = input.value.trim() === "";
    });
}
