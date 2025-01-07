/* Função de login */
function logar() {
    const login = document.getElementById('login').value.trim(); // Remove espaços em branco
    const senha = document.getElementById('senha').value.trim();

    // Simulação de usuários válidos (aqui você pode conectar a uma API ou banco de dados)
    const usuariosValidos = {
        admin: 'admin', // Exemplo de credenciais
        // Adicione mais usuários conforme necessário
    };

    // Valida o login
    if (usuariosValidos[login] && usuariosValidos[login] === senha) {
        location.href = "./home.html"; // Redireciona para a página home
    } else {
        // Exibe uma mensagem de erro
        exibirMensagemErro('Usuário ou senha incorreta. Tente novamente.');
        limparCampos(); // Limpa os campos de entrada
    }
}

// Função para exibir mensagens de erro
function exibirMensagemErro(mensagem) {
    const errorMessageElement = document.getElementById('errorMessage');
    errorMessageElement.textContent = mensagem; // Define o texto do elemento de erro
    errorMessageElement.style.display = 'block'; // Exibe o elemento
}

// Função para limpar os campos de entrada
function limparCampos() {
    document.getElementById('login').value = "";
    document.getElementById('senha').value = "";
    document.getElementById('login').focus(); // Foco no campo login
}
