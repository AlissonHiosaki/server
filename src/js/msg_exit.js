//funcao de proibir de editar url

function preventUrlEdit() {
    window.onbeforeunload = function() {
        return 'Você está prestes a sair da página. Confirme se deseja sair.';
    };
}

preventUrlEdit();