<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ======================================
// LOGIN
// ======================================
if (!isset($_SESSION['usuario'])) {

    header('Location: /login');
    exit;

}

// ======================================
// API
// ======================================
$apiBase = 'https://api.hiosaki.com.br';

$token = 'HIOSAKI_2026_TOKEN';

$erro = '';
$sucesso = '';

// ======================================
// LISTAR USUÁRIOS
// ======================================
$usuarios = [];

$ch = curl_init($apiBase . '/users.php');

curl_setopt_array($ch, [

    CURLOPT_RETURNTRANSFER => true,

    CURLOPT_HTTPHEADER => [

        'Authorization: Bearer ' . $token

    ]

]);

$response = curl_exec($ch);

curl_close($ch);

$resultado = json_decode($response, true);

if (
    isset($resultado['success']) &&
    $resultado['success']
) {

    $usuarios = $resultado['users'];

}

// ======================================
// EDITAR USUÁRIO
// ======================================
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['action'] ?? '') === 'edit'
) {

    $id = (int)($_POST['id'] ?? 0);

    $login = trim($_POST['login'] ?? '');

    $nome = trim($_POST['nome'] ?? '');

    $ativo = isset($_POST['ativo']);

    $senha = $_POST['senha'] ?? '';

    $dados = [

        'id' => $id,

        'login' => $login,

        'nome' => $nome,

        'ativo' => $ativo,

        'senha' => $senha

    ];

    $ch = curl_init($apiBase . '/update-user.php');

    curl_setopt_array($ch, [

        CURLOPT_RETURNTRANSFER => true,

        CURLOPT_POST => true,

        CURLOPT_HTTPHEADER => [

            'Content-Type: application/json',

            'Authorization: Bearer ' . $token

        ],

        CURLOPT_POSTFIELDS =>
            json_encode($dados)

    ]);

    $response = curl_exec($ch);

    curl_close($ch);

    $result = json_decode($response, true);

    if (
        isset($result['success']) &&
        $result['success']
    ) {

        header('Location: ?success=1');
        exit;

    }

    $erro = $result['message'] ?? 'Erro ao editar usuário';

}

if (isset($_GET['success'])) {
    $sucesso = 'Usuário atualizado com sucesso';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">
<link rel="icon"
href="https://cdn.hiosaki.com.br/logos/rasp.svg" />
<title>Dashboard Usuários</title>

<script src="https://cdn.tailwindcss.com"></script>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<link rel="preconnect"
href="https://fonts.googleapis.com">

<link rel="stylesheet"
href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">

<style>

body{
    font-family:'Inter',sans-serif;
    background:#020617;
    color:white;
    min-height:100vh;
}

.glass{
    background:rgba(255,255,255,.05);
    border:1px solid rgba(255,255,255,.08);
    backdrop-filter:blur(20px);
}

</style>

</head>
<body>

<nav class="sticky top-0 z-50
bg-slate-900/80
border-b border-slate-800
backdrop-blur-xl">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col lg:flex-row
        lg:items-center
        lg:justify-between
        gap-5
        py-4">

                <!-- ESQUERDA -->
                <div class="flex items-center gap-4">

                    <!-- LOGO -->
                    <div class="relative">

                        <div class="absolute inset-0
                    rounded-full
                    bg-[#1fd560]/20
                    blur-xl animate-pulse"></div>

                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-black shadow-lg shadow-blue-600/30 font-bold text-xl tracking-tighter animate-pulse">

                            GM

                        </div>

                    </div>

                    <!-- TEXTO -->
                    <div>

                        <div class="flex items-center gap-2 flex-wrap">

                            <h1 class="text-xl sm:text-2xl
                        font-black
                        tracking-wide
                        text-white">

                                .AROMATIZANTE

                            </h1>

                            <span class="px-2 py-1
                        rounded-full
                        text-[10px]
                        uppercase
                        font-bold
                        bg-[#1fd560]/10
                        border border-[#1fd560]/20
                        text-[#1fd560]">

                                Painel

                            </span>

                        </div>

                        <p class="text-sm
                    text-slate-400
                    mt-1">

                            Bem-vindo,
                            <span class="text-white font-semibold">

                                <?= htmlspecialchars($_SESSION['usuario']['nome'] ?? 'Usuário') ?>

                            </span>

                        </p>

                    </div>

                </div>

                

                </div>

            </div>

        </div>

    </nav>

<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3 mt-6">

    <?php foreach ($usuarios as $u): ?>

        <div class="group relative overflow-hidden rounded-[2rem]
        bg-gradient-to-br from-slate-900/90 to-slate-950/90
        border border-slate-800/80
        shadow-2xl shadow-black/30
        hover:border-blue-500/30
        hover:shadow-blue-500/10
        transition-all duration-500">

            <!-- Glow -->
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-700">

                <div class="absolute -top-20 -right-20 w-60 h-60
                bg-blue-500/10 blur-3xl rounded-full"></div>

                <div class="absolute -bottom-20 -left-20 w-60 h-60
                bg-cyan-500/10 blur-3xl rounded-full"></div>

            </div>

            <div class="relative z-10 p-6">

                <!-- TOPO -->
                <div class="flex items-start justify-between mb-6">

                    <div class="flex items-center gap-4">

                        <!-- Avatar -->
                        <div class="relative">

                            <div class="absolute inset-0
                            rounded-full
                            bg-blue-500/30
                            blur-xl animate-pulse"></div>

                            <div class="relative
                            w-14 h-14
                            rounded-2xl
                            bg-gradient-to-br
                            from-blue-500
                            to-cyan-400
                            flex items-center justify-center
                            text-white text-xl font-black
                            shadow-lg shadow-blue-500/20">

                                <?= strtoupper(substr($u['nome'], 0, 1)) ?>

                            </div>

                        </div>

                        <!-- Infos -->
                        <div>

                            <h2 class="text-lg font-bold text-white leading-tight">

                                <?= htmlspecialchars($u['nome']) ?>

                            </h2>

                            <p class="text-sm text-slate-400 mt-1">

                                @<?= htmlspecialchars($u['login']) ?>

                            </p>

                        </div>

                    </div>

                    <!-- STATUS -->
                    <?php if ($u['ativo']): ?>

                        <span class="inline-flex items-center gap-2
                        bg-emerald-500/10
                        border border-emerald-500/20
                        text-emerald-300
                        px-3 py-1.5
                        rounded-full
                        text-xs font-bold">

                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>

                            Ativo

                        </span>

                    <?php else: ?>

                        <span class="inline-flex items-center gap-2
                        bg-red-500/10
                        border border-red-500/20
                        text-red-300
                        px-3 py-1.5
                        rounded-full
                        text-xs font-bold">

                            <span class="w-2 h-2 rounded-full bg-red-400"></span>

                            Inativo

                        </span>

                    <?php endif; ?>

                </div>

                <!-- FORM -->
                <form method="post" class="space-y-5">

                    <input type="hidden" name="action" value="edit">

                    <input
                    type="hidden"
                    name="id"
                    value="<?= (int)$u['id'] ?>">

                    <!-- LOGIN -->
                    <div>

                        <label class="block text-xs uppercase tracking-wider
                        text-slate-500 mb-2 font-bold">

                            Login

                        </label>

                        <div class="relative">

                            <i class="bi bi-person-badge
                            absolute left-4 top-1/2 -translate-y-1/2
                            text-slate-500"></i>

                            <input
                            type="text"
                            name="login"
                            required
                            value="<?= htmlspecialchars($u['login']) ?>"
                            class="w-full
                            rounded-2xl
                            border border-slate-800
                            bg-slate-950/70
                            pl-12 pr-4 py-3.5
                            text-white
                            outline-none
                            focus:border-blue-500
                            focus:ring-4 focus:ring-blue-500/10
                            transition">

                        </div>

                    </div>

                    <!-- NOME -->
                    <div>

                        <label class="block text-xs uppercase tracking-wider
                        text-slate-500 mb-2 font-bold">

                            Nome

                        </label>

                        <div class="relative">

                            <i class="bi bi-person
                            absolute left-4 top-1/2 -translate-y-1/2
                            text-slate-500"></i>

                            <input
                            type="text"
                            name="nome"
                            required
                            value="<?= htmlspecialchars($u['nome']) ?>"
                            class="w-full
                            rounded-2xl
                            border border-slate-800
                            bg-slate-950/70
                            pl-12 pr-4 py-3.5
                            text-white
                            outline-none
                            focus:border-cyan-500
                            focus:ring-4 focus:ring-cyan-500/10
                            transition">

                        </div>

                    </div>

                    <!-- SENHA -->
                    <div>

                        <label class="block text-xs uppercase tracking-wider
                        text-slate-500 mb-2 font-bold">

                            Nova Senha

                        </label>

                        <div class="relative">

                            <i class="bi bi-lock
                            absolute left-4 top-1/2 -translate-y-1/2
                            text-slate-500"></i>

                            <input
                            type="password"
                            name="senha"
                            placeholder="Deixe vazio para não alterar"
                            class="w-full
                            rounded-2xl
                            border border-slate-800
                            bg-slate-950/70
                            pl-12 pr-4 py-3.5
                            text-white
                            outline-none
                            focus:border-purple-500
                            focus:ring-4 focus:ring-purple-500/10
                            transition">

                        </div>

                    </div>

                    <!-- SWITCH -->
                    <label class="flex items-center justify-between
                    rounded-2xl
                    border border-slate-800
                    bg-slate-950/60
                    px-4 py-4
                    cursor-pointer">

                        <div>

                            <p class="font-semibold text-white">
                                Usuário ativo
                            </p>

                            <p class="text-xs text-slate-500 mt-1">
                                Permitir acesso ao painel
                            </p>

                        </div>

                        <div class="relative">

                            <input
                            type="checkbox"
                            name="ativo"
                            class="sr-only peer"
                            <?= $u['ativo'] ? 'checked' : '' ?>>

                            <div class="w-14 h-8 rounded-full
                            bg-slate-700
                            peer-checked:bg-emerald-500
                            transition-all duration-300"></div>

                            <div class="absolute left-1 top-1
                            w-6 h-6 rounded-full bg-white
                            transition-all duration-300
                            peer-checked:translate-x-6"></div>

                        </div>

                    </label>

                    <!-- BOTÃO -->
                    <button
                    type="submit"
                    class="group/btn relative overflow-hidden
                    w-full
                    rounded-2xl
                    bg-gradient-to-r
                    from-blue-600
                    to-cyan-500
                    py-4
                    font-bold text-white
                    shadow-xl shadow-blue-500/20
                    hover:scale-[1.02]
                    transition-all duration-300">

                        <div class="absolute inset-0
                        opacity-0 group-hover/btn:opacity-100
                        transition duration-500
                        bg-white/10"></div>

                        <span class="relative flex items-center justify-center gap-2">

                            <i class="bi bi-save"></i>

                            Salvar Alterações

                        </span>

                    </button>

                </form>

            </div>

        </div>

    <?php endforeach; ?>

</div>


</div>

</body>
</html>