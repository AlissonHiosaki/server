<?php
// ======================================
// SRV-HIOSAKI 2026
// ======================================
$config = json_decode(
    file_get_contents(__DIR__ . '/site.json'),
    true
);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$erro = '';

// Já logado
if (isset($_SESSION['usuario'])) {

    header('Location: admin.php');
    exit;

}

// ======================================
// LOGIN
// ======================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $login = trim($_POST['login'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($login === '' || $senha === '') {

        $erro = 'Informe login e senha.';

    } else {

        // ======================================
        // API LOGIN EDITE-ME
        // ======================================
        $url = 'https://api.hiosaki.com.br/login/';

        $dados = [
            'login' => $login,
            'senha' => $senha
        ];

        $ch = curl_init($url);

        curl_setopt_array($ch, [

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_POST => true,

            CURLOPT_TIMEOUT => 15,

            CURLOPT_HTTPHEADER => [

                'Content-Type: application/json',

                'Authorization: Bearer HIOSAKI_2026_TOKEN'

            ],

            CURLOPT_POSTFIELDS =>
                json_encode($dados)

        ]);

        $resposta = curl_exec($ch);

        // erro curl
        if (curl_errno($ch)) {

            $erro =
                'Erro ao conectar API';

        } else {

            $resultado =
                json_decode($resposta, true);

            // sucesso
            if (
                isset($resultado['success']) &&
                $resultado['success']
            ) {

                $_SESSION['usuario'] =
                    $resultado['user'];

                header('Location: admin.php');

                exit;

            }

            // erro api
            $erro =
                $resultado['message']
                ?? 'Erro no login';

        }

        curl_close($ch);

    }

}
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= htmlspecialchars($config['favicon']) ?>" type="image/png" />
    <title> <?= htmlspecialchars($config['title']) ?> Login</title>
    <meta name="description" content="Área do cliente HIOSAKI">
    <link rel="shortcut icon" href="https://cdn.hiosaki.com.br/logos/favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
      :root{
    --primary:#1144a7;
     }
    body {
        background: black;
        color: white;
        font-family: Arial, sans-serif;
        overflow-x: hidden;
    }

    .gradient-text {
        background: linear-gradient(90deg,  var(--primary),  var(--primary), var(--primary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .glow {
        position: fixed;
        width: 450px;
        height: 450px;
        background:  var(--primary);
        filter: blur(5px);
        opacity: .20;
        border-radius: 999px;
        z-index: 0;
    }

    .card {
        background: black.50;
        backdrop-filter: blur(5px);
        border: 1px solid  var(--primary);
        box-shadow: 0 30px 80px rgba(0, 0, 0, .35);
    }

    .input {
        width: 100%;
        background: transparent;
        border: 1px solid  var(--primary);
        border-radius: 18px;
        padding: 16px 18px;
        color: white;
        outline: none;
        transition: .3s;
    }

    .input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px var(--primary);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 16px 26px;
        border-radius: 18px;
        font-weight: 800;
        transition: .3s;
    }

    .btn-primary {
        background:  var(--primary);
      
    }

    .btn-primary:hover {
        transform: translateY(-3px);
       
    }
  
    </style>
</head>

<body class="min-h-screen relative flex items-center justify-center px-6 py-10 overflow-hidden">

    <div class="glow top-[-120px] left-[-120px]"></div>
    <div class="glow bottom-[-120px] right-[-120px]"></div>

    <div class="relative z-10 w-full max-w-6xl grid lg:grid-cols-2 gap-10 items-center">

        <section class="hidden lg:block">
            <div
                class="inline-flex items-center gap-3 bg-green-500/10 border border-green-500/20 px-5 py-3 rounded-2xl mb-8">
                <span class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-green-300 font-bold">Painel online</span>
            </div>

            <h1 class="text-6xl font-black leading-tight" >
                Bem-vindo à
                <span class="gradient-text block "><?= htmlspecialchars($config['title']) ?></span>
            </h1>

            <p class="text-gray-300 text-xl mt-6 leading-relaxed">
                Acesse sua área administrativa para visualizar seu dashboard
            </p>

            <div class="grid grid-cols-3 gap-5 mt-10">
                <div class="card rounded-3xl p-5">
                    <i class="bi bi-shield-lock text-3xl text-[var(--primary)]"></i>
                    <p class="font-bold mt-3">Seguro</p>
                </div>

                <div class="card rounded-3xl p-5">
                    <i class="bi bi-cloud-check text-3xl text-[var(--primary)]"></i>
                    <p class="font-bold mt-3">Online</p>
                </div>

                <div class="card rounded-3xl p-5">
                    <i class="bi bi-headset text-3xl text-[var(--primary)]"></i>
                    <p class="font-bold mt-3">Suporte</p>
                </div>
            </div>
        </section>

        <form method="post" class="card rounded-[2rem] p-8 md:p-10 w-full max-w-md mx-auto">

            <div class="text-center mb-8">
                <div
                    class="w-20 h-20 mx-auto rounded-3xl bg-black border border-[var(--primary)] flex items-center justify-center mb-5">
                    <i class="bi bi-person-lock text-4xl text-[var(--primary)]"></i>
                </div>

                <h2 class="text-4xl font-black">
                    Área do <span class="gradient-text">Cliente</span>
                </h2>

                <p class="text-gray-400 mt-3">
                    Entre para gerenciar sua hospedagem.
                </p>
            </div>

            <?php if($erro): ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-300 p-4 rounded-2xl mb-5 flex gap-3">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <p><?= e($erro) ?></p>
            </div>
            <?php endif; ?>

            <label class="block text-sm font-bold text-gray-300 mb-2">
               Usuário
            </label>
            <div class="relative mb-5">
                <i class="bi bi-person absolute left-5 top-1/2 -translate-y-1/2 text-[var(--primary)]"></i>

                <input class="input pl-14" type="text" name="login" placeholder="Digite seu usuário" required autofocus>
            </div>

            <label class="block text-sm font-bold text-gray-300 mb-2">
                Senha
            </label>
            <div class="relative mb-6">
                <i class="bi bi-lock absolute left-5 top-1/2 -translate-y-1/2 text-[var(--primary)]"></i>

                <input class="input pl-14" type="password" name="senha" placeholder="Digite sua senha" required>
            </div>

            <button class="btn btn-primary bg-[var(--primary)] w-full">
                <i class="bi bi-box-arrow-in-right"></i>
                Entrar
            </button>

            <div class="flex items-center justify-between mt-6 text-sm">
                <a class="p-1 text-bold text-white hover:text-white hover:bg-[var(--primary)] hover:p-1 hover:rounded-2xl " href="/">
                    Voltar ao site
                </a>


            </div>

        </form>

    </div>

</body>

</html>