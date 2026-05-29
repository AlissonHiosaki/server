<?php
$config = json_decode(
    file_get_contents(__DIR__ . '/dashboard/site.json'),
    true
);
?>
<?php

// ======================================
// API
// ======================================
$apiUrl = 'https://api.hiosaki.com.br/clientes/demo/';
$token  = 'HIOSAKI_2026_TOKEN';

// ======================================
// FUNÇÃO API
// ======================================
function chamarApi($url, $token, $data = null) {
    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ]
    ]);

    if ($data !== null) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);

    if ($response === false) {
        curl_close($ch);
        return ['success' => false, 'produtos' => []];
    }

    curl_close($ch);

    $json = json_decode($response, true);
    return is_array($json) ? $json : ['success' => false, 'produtos' => []];
}

// ======================================
// UPLOAD DE IMAGEM LOCAL
// ======================================
function uploadImagem($file) {
    if (!$file || !isset($file['error']) || $file['error'] !== 0) {
        return null;
    }

    $uploadDir = __DIR__ . '/uploads/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $permitidas = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    if (!in_array($ext, $permitidas)) {
        return null;
    }

    $nome = uniqid('img_', true) . '.' . $ext;
    $destino = $uploadDir . $nome;

    if (move_uploaded_file($file['tmp_name'], $destino)) {
        return 'uploads/' . $nome;
    }

    return null;
}

// ======================================
// ADICIONAR / EDITAR
// ======================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    $imagemUpload = uploadImagem($_FILES['imagem_file'] ?? null);
    $imagemUrl = trim($_POST['imagem_url'] ?? '');
    $imagemFinal = $imagemUpload ?: $imagemUrl;

    chamarApi($apiUrl, $token, [
        'action' => $action,
        'id' => $_POST['id'] ?? null,
        'nome' => $_POST['nome'] ?? '',
        'categoria' => $_POST['categoria'] ?? '',
        'preco' => $_POST['preco'] ?? 0,
        'imagem' => $imagemFinal,
        'descricao' => $_POST['descricao'] ?? '',
        'disponivel' => isset($_POST['disponivel'])
    ]);

    header('Location: index.php');
    exit;
}

// ======================================
// EXCLUIR
// ======================================
if (isset($_GET['excluir'])) {
    chamarApi($apiUrl, $token, [
        'action' => 'excluir',
        'id' => (int) $_GET['excluir']
    ]);

    header('Location: index.php');
    exit;
}

// ======================================
// LISTAR PRODUTOS
// ======================================
$resultado = chamarApi($apiUrl, $token);
$produtos = $resultado['produtos'] ?? [];

if (!is_array($produtos)) {
    $produtos = [];
}

?>

<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-slate-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= htmlspecialchars($config['favicon']) ?>" type="image/png" />
    <title> <?= htmlspecialchars($config['title']) ?></title>

    <!-- Tailwind CSS v4 -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Bootstrap Icons (Para ícones limpos) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    html {
        scroll-behavior: smooth;
    }
    </style>
</head>

<body class="text-slate-200 min-h-full antialiased selection:bg-[#1fd560] selection:text-white">

    <!-- Navbar / Header -->
    <nav class="sticky top-0 z-50 bg-slate-900/80 border-b border-slate-800 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-black shadow-lg shadow-blue-600/30 font-bold text-xl tracking-tighter
                        animate-pulse">
                       <img class="rounded-full " src="<?= htmlspecialchars($config['favicon']) ?>" alt="logo" title="logo" >
                    </div>
                    <span class="text-white font-bold text-lg tracking-wider"><?= htmlspecialchars($config['nome_2']) ?></span>
                </div>

                <!-- Redes sociais / Contato -->
                <div class="flex items-center gap-4">
                    <a href="https://www.instagram.com/gm.aromatizante/" target="_blank" title="Instagram" class="inline-flex items-center justify-center
w-9 h-9 rounded-full
bg-gradient-to-br
from-[#feda75]
via-[#fe089c]
to-[#4f5bd5]
text-white
shadow-lg shadow-pink-500/20
hover:scale-110
hover:shadow-pink-500/40
transition-all duration-300"><i class="bi bi-instagram"></i></a>

                    <a href="#pedido" title="WhatsApp"
                        class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-md hover:bg-emerald-500 transition-all cursor-pointer">
                        <i class="bi bi-whatsapp"></i> <span class="hidden sm:inline">Fazer Pedido</span>
                    </a>

                    <a onclick="window.location.href='/dashboard/'" title="Dashboard"
                        class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-md hover:bg-blue-500 transition-all cursor-pointer">
                        <i class="bi bi-building-fill-check"></i> <span class="hidden sm:inline">Painel
                            Admistravito</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                keyframes: {
                    typing: {
                        "0%": {
                            width: "0%",
                            visibility: "hidden"
                        },
                        "100%": {
                            width: "100%"
                        }
                    },
                    blink: {
                        "50%": {
                            borderColor: "transparent"
                        },
                        "100%": {
                            borderColor: "white"
                        }
                    }
                },
                animation: {
                    typing: "typing 2s steps(20) infinite alternate, blink .7s infinite"
                }
            },
        },
        plugins: [],
    }
    </script>
    <!-- Hero Section / Banner -->
    <header class="relative bg-slate-900 border-b border-slate-800/60 overflow-hidden py-16 sm:py-24">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(239,68,68,0.12),transparent_45%)]">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
            <span
                class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-[#1fd560]/10 text-[#1fd560] ring-1 ring-inset ring-[#1fd560]/20 mb-4">
               <?= htmlspecialchars($config['texto_1']) ?>
            </span>
            <h2
                class="text-4xl sm:text-6xl font-extrabold text-white tracking-tight max-w-2xl leading-none animate-typing">
                <?= htmlspecialchars($config['texto_2']) ?> <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#1fd560] to-blue-800
                    animate-pulse overflow-hidden whitespace-nowrap
                    "><?= htmlspecialchars($config['texto_3']) ?></span>
            </h2>
            <p class="mt-4 text-base sm:text-xl text-slate-400 max-w-xl font-light">
               <?= htmlspecialchars($config['texto_4']) ?>
            </p>
        </div>
    </header>

    <!-- Área de Catálogo -->
    <main id="pedido" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Título da Seção -->
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-800/60">
            <div>
                <h3 class="text-xl font-bold text-white tracking-tight">Catálogo de Produtos</h3>
                <p class="text-xs text-slate-400">Escolha o aroma perfeito para a sua jornada</p>
            </div>
            <span class="text-xs font-mono text-slate-500 bg-slate-900 border border-slate-800 px-2.5 py-1 rounded-md">
                <?php echo count($produtos); ?> itens disponíveis
            </span>
        </div>

        <!-- Grid de Cards de Produtos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($produtos as $p): ?>

            <!-- Card -->
            <div
                class="group bg-slate-900/40 border border-slate-800/80 rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl hover:border-slate-700/60 transition-all duration-300 flex flex-col justify-between">

                <!-- Imagem do Produto -->
                <div class="relative aspect-video w-full overflow-hidden bg-slate-800">
                    <img src="<?php echo $p['imagem']; ?>" alt="<?php echo $p['nome']; ?>"
                        class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-500 opacity-80 group-hover:opacity-100">

                    <!-- Badge de Categoria -->
                    <span
                        class="absolute top-3 left-3 bg-slate-950/80 border border-slate-800 backdrop-blur-md text-slate-300 text-[10px] uppercase font-bold tracking-wider px-2.5 py-1 rounded-md">
                        <?php echo $p['categoria']; ?>
                    </span>

                    <!-- Esgotado Overlay -->
                    <?php if (!$p['disponivel']): ?>
                    <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-xs flex items-center justify-center">
                        <span
                            class="bg-[#1fd560]/10 border border-[#1fd560]/30 text-[#1fd560] font-bold uppercase tracking-widest text-xs px-4 py-2 rounded-xl">
                            Esgotado
                        </span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Conteúdo do Card -->
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h4
                            class="text-lg font-bold text-white group-hover:text-[#1fd560] transition-colors line-clamp-1">
                            <?php echo $p['nome']; ?>
                        </h4>
                        <p class="mt-2 text-sm text-slate-400 leading-relaxed font-light line-clamp-2">
                            <?php echo $p['descricao']; ?>
                        </p>
                    </div>

                    <!-- Preço e Botão -->
                    <div class="mt-6 pt-4 border-t border-slate-800/60 flex items-center justify-between">
                        <div>
                            <span class="block text-[10px] text-slate-500 uppercase tracking-wider">Preço</span>
                            <span class="text-emerald-400 font-bold mt-2">
                                R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?>
                            </span>
                        </div>

                        <?php if ($p['disponivel']): ?>
                        <!-- Link direto para o WhatsApp montando a mensagem automática -->
                        <?php 
                                $mensagem = urlencode("Olá (Nome do seu site)! Gostaria de encomendar o produto: " . $p['nome']);
                                ?>
                        <a href="https://wa.me/551699999999?text=<?php echo $mensagem; ?>" target="_blank"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-slate-800 hover:bg-[#1fd560] text-slate-300 hover:text-white border border-slate-700/60 hover:border-[#1fd560] p-2.5 text-sm font-semibold transition-all cursor-pointer shadow-inner">
                            <i class="bi bi-cart3"></i> Comprar
                        </a>
                        <?php else: ?>
                        <button disabled
                            class="inline-flex items-center gap-1.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-600 p-2.5 text-sm font-semibold cursor-not-allowed">
                            <i class="bi bi-slash-circle"></i> Indisponível
                        </button>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <?php endforeach; ?>
        </div>
    </main>

    <!-- BTN VOLTAR TOPO -->
    <button id="btnTopo" onclick="voltarTopo()" class="hidden fixed bottom-30 right-6 z-50 animate-pulse
bg-gradient-to-r
from-blue-600
to-cyan-500
w-14 h-14
rounded-full
shadow-lg shadow-blue-500/30
text-white
hover:scale-110
transition-all">

        <i class="bi bi-arrow-up text-xl"></i>

    </button>

    <script>
    const btn =
        document.getElementById("btnTopo");

    window.addEventListener(
        "scroll",
        () => {

            if (
                window.scrollY > 300
            ) {

                btn.classList.remove(
                    "hidden"
                );

            } else {

                btn.classList.add(
                    "hidden"
                );

            }

        }
    );

    function voltarTopo() {

        window.scrollTo({

            top: 0,
            behavior: "smooth"

        });

    }
    </script>

    <!-- Footer -->
    <footer class="border-t border-slate-900 bg-slate-950 mt-20 py-12 relative overflow-hidden">
        <!-- Linha sutil de efeito brilhante no topo do rodapé -->
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-[1px] bg-gradient-to-r from-transparent via-[#1fd560]/30 to-transparent">
        </div>

        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left sm:flex sm:items-center sm:justify-between text-xs text-slate-500">

            <!-- Direitos Autorais e Marca -->
            <div class="mb-6 sm:mb-0 space-y-1">
                <p class="font-bold text-slate-300 text-sm tracking-wider uppercase"><?= htmlspecialchars($config['title']) ?></p>
                <p class="text-slate-500 font-light">
                    © 2026 • Todos os direitos reservados. <?= htmlspecialchars($config['title']) ?>
                </p>
            </div>

            <!-- Links e Créditos de Desenvolvimento -->
            <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6 justify-center sm:justify-end">

                <!-- Link de Contato Estilizado (Verde WhatsApp) -->
                <a href="https://wa.me/5516920016949" target="_blank"
                    class="inline-flex items-center gap-1.5 text-[#1fd560] bg-[#1fd560]/5 border border-[#1fd560]/20 px-3 py-1.5 rounded-xl hover:bg-[#1fd560] hover:text-slate-950 transition-all duration-300 font-medium shadow-lg shadow-[#1fd560]/20">
                    <i class="bi bi-whatsapp"></i>
                    Contato Comercial
                </a>

                <!-- Créditos do Desenvolvedor -->
                <div
                    class="flex items-center gap-1 text-slate-500 bg-slate-900/40 border border-slate-900 px-3 py-1.5 rounded-xl">
                    <span>Desenvolvido por</span>
                    <span
                        class="text-slate-300 font-semibold hover:text-[#1fd560] transition-colors cursor-default shadow-lg hover:shadow-[#1fd560]/20 hover:bg-[#1fd560] hover:text-slate-950 transition-all duration-300 hover:rounded-xl hover:py-0.2 px-0.2">
                        Alisson Hiosaki
                    </span>
                    <span class="h-1.5 w-1.5 rounded-full bg-[#1fd560] ml-1 animate-pulse"></span>
                </div>

            </div>
        </div>
    </footer>

</body>
<script>
fetch("https://hiosaki.com.br/sistema/tracker.php", {
    method: "POST",
    mode: "cors",
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify({
        dominio: location.hostname,
        pagina: location.pathname,
        url: location.href,
        referer: document.referrer,
        user_agent: navigator.userAgent
    })
})
.then(async response => {
    const texto = await response.text();
    console.log("STATUS:", response.status);
    console.log("RESPOSTA:", texto);
})
.catch(error => {
    console.error("ERRO AO ENVIAR TRACKER:", error);
});
</script>
</html>