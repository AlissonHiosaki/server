document.addEventListener("DOMContentLoaded", () => {
    const loadingScreen = document.getElementById("loading-screen");
    const loginScreen = document.getElementById("login-screen");

    // Simula um tempo de carregamento
    setTimeout(() => {
        loadingScreen.classList.add("hidden"); // Esconde a tela de loading
        loginScreen.classList.remove("hidden"); // Mostra a tela de login
    }, 1000); // Ajuste o tempo conforme necessário (em milissegundos)
});
