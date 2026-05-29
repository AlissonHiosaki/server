<?php
session_start();

$arquivo = __DIR__ . '/../site.json';

if (!file_exists($arquivo)) {
    file_put_contents($arquivo, json_encode([
        "title" => "SRV-HIOSAKI",
        "nome" => "SRV",
        "nome_2" => "Hospedagem",
        "texto_1" => "",
        "texto_2" => "",
        "texto_3" => "",
        "texto_4" => "",
        "texto_5" => "",
        "favicon" => "",
        "logo" => "",
        "banner" => "",
        "background" => "",
        "cor" => "#2563eb"
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$config = json_decode(file_get_contents($arquivo), true);

$config = array_merge([
    "title" => "",
    "nome" => "",
    "nome_2" => "",
    "texto_1" => "",
    "texto_2" => "",
    "texto_3" => "",
    "texto_4" => "",
    "texto_5" => "",
    "favicon" => "",
    "logo" => "",
    "banner" => "",
    "background" => "",
    "cor" => "#2563eb"
], $config ?? []);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $config = [
        "title" => $_POST['title'] ?? '',
        "nome" => $_POST['nome'] ?? '',
        "nome_2" => $_POST['nome_2'] ?? '',
        "texto_1" => $_POST['texto_1'] ?? '',
        "texto_2" => $_POST['texto_2'] ?? '',
        "texto_3" => $_POST['texto_3'] ?? '',
        "texto_4" => $_POST['texto_4'] ?? '',
        "texto_5" => $_POST['texto_5'] ?? '',
        "favicon" => $_POST['favicon'] ?? '',
        "logo" => $_POST['logo'] ?? '',
        "banner" => $_POST['banner'] ?? '',
        "background" => $_POST['background'] ?? '',
        "cor" => $_POST['cor'] ?? '#2563eb'
    ];

    file_put_contents(
        $arquivo,
        json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );

    header('Location: index.php?salvo=1');
    exit;
}

function e($valor) {
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="pt-BR" class="bg-slate-950">

<head>
    <meta charset="UTF-8">
    <title>Editar Site</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php if (!empty($config['favicon'])): ?>
        <link rel="icon" href="<?= e($config['favicon']) ?>">
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .bg-panel {
            background:
                radial-gradient(circle at top right, rgba(37, 99, 235, .18), transparent 35%),
                radial-gradient(circle at top left, rgba(31, 213, 96, .10), transparent 30%),
                #020617;
        }
    </style>
</head>

<body class="min-h-screen bg-panel text-slate-200 antialiased">

    <!-- NAVBAR -->
    <nav class="sticky top-0 z-50 bg-slate-900/80 border-b border-slate-800 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between h-20">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-full bg-white text-slate-950 font-black shadow-lg shadow-blue-600/30">
                        <img class="rounded-full " src="<?= htmlspecialchars($config['favicon']) ?>" alt="logo" title="logo" >
                    </div>

                    <div>
                        <h1 class="text-white font-black tracking-wide">
                            Painel do Site
                        </h1>
                        <p class="text-xs text-slate-400">
                            Personalização visual
                        </p>
                    </div>

                </div>

                <div class="flex items-center gap-3">

                    <a href="../index.php"
                        class="hidden sm:inline-flex items-center gap-2 rounded-full bg-slate-800 border border-slate-700 px-4 py-2 text-sm font-semibold text-slate-300 hover:bg-slate-700 transition">
                        <i class="bi bi-box-arrow-up-right"></i>
                        Ver site
                    </a>

                    <button form="formConfig"
                        class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-600/30 hover:bg-blue-500 transition">
                        <i class="bi bi-check2-circle"></i>
                        Salvar
                    </button>

                </div>

            </div>

        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <?php if (isset($_GET['salvo'])): ?>
            <div class="mb-8 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-5 text-emerald-300 flex items-center gap-3">
                <i class="bi bi-check-circle-fill text-xl"></i>
                <span class="font-semibold">Configurações salvas com sucesso!</span>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- FORM -->
            <section class="lg:col-span-2">

                <form id="formConfig" method="POST"
                    class="bg-slate-900/70 border border-slate-800 rounded-3xl shadow-2xl shadow-black/30 overflow-hidden">

                    <div class="p-6 border-b border-slate-800">
                        <h2 class="text-2xl font-black text-white">
                            Editar informações
                        </h2>
                        <p class="text-sm text-slate-400 mt-1">
                            Altere textos, imagens e cores principais do site.
                        </p>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-slate-300">
                                <i class="bi bi-browser-chrome text-blue-400"></i>
                                Title da página
                            </label>
                            <input name="title" value="<?= e($config['title']) ?>"
                                class="w-full mt-2 bg-slate-950/80 border border-slate-800 rounded-2xl p-4 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition">
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-300">
                                <i class="bi bi-app-indicator text-emerald-400"></i>
                                Nome do ícone
                            </label>
                             <input name="favicon" value="<?= e($config['favicon']) ?>"
                                class="w-full mt-2 bg-slate-950/80 border border-slate-800 rounded-2xl p-4 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition">
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-300">
                                <i class="bi bi-type text-cyan-400"></i>
                                Nome do site
                            </label>
                            <input name="nome_2" value="<?= e($config['nome_2']) ?>"
                                class="w-full mt-2 bg-slate-950/80 border border-slate-800 rounded-2xl p-4 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition">
                        </div>

                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <div class="md:col-span-2">
                                <label class="text-sm font-semibold text-slate-300">
                                    <i class="bi bi-card-text text-blue-400"></i>
                                    Texto <?= $i ?>
                                </label>
                                <textarea name="texto_<?= $i ?>"
                                    class="w-full h-28 mt-2 bg-slate-950/80 border border-slate-800 rounded-2xl p-4 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition resize-none"><?= e($config["texto_$i"]) ?></textarea>
                            </div>
                        <?php endfor; ?>

                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-slate-300">
                                <i class="bi bi-stars text-yellow-400"></i>
                                Favicon URL
                            </label>
                            <input name="favicon" value="<?= e($config['favicon']) ?>"
                                class="w-full mt-2 bg-slate-950/80 border border-slate-800 rounded-2xl p-4 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition">
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-slate-300">
                                <i class="bi bi-image text-emerald-400"></i>
                                Logo URL
                            </label>
                            <input name="logo" value="<?= e($config['logo']) ?>"
                                class="w-full mt-2 bg-slate-950/80 border border-slate-800 rounded-2xl p-4 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition">
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-slate-300">
                                <i class="bi bi-card-image text-purple-400"></i>
                                Banner URL
                            </label>
                            <input name="banner" value="<?= e($config['banner']) ?>"
                                class="w-full mt-2 bg-slate-950/80 border border-slate-800 rounded-2xl p-4 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition">
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-slate-300">
                                <i class="bi bi-wallpaper text-cyan-400"></i>
                                Background URL
                            </label>
                            <input name="background" value="<?= e($config['background']) ?>"
                                class="w-full mt-2 bg-slate-950/80 border border-slate-800 rounded-2xl p-4 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition">
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-slate-300">
                                <i class="bi bi-palette text-pink-400"></i>
                                Cor principal
                            </label>

                            <div class="mt-2 flex items-center gap-4">
                                <input type="color" name="cor" value="<?= e($config['cor']) ?>"
                                    class="w-20 h-14 rounded-2xl bg-slate-950 border border-slate-800 cursor-pointer">

                                <span class="text-sm text-slate-400 bg-slate-950 border border-slate-800 rounded-full px-4 py-2">
                                    <?= e($config['cor']) ?>
                                </span>
                            </div>
                        </div>

                    </div>

                    <div class="p-6 border-t border-slate-800 flex flex-col sm:flex-row gap-3 sm:justify-end">

                        <a href="index.php"
                            class="inline-flex justify-center items-center gap-2 rounded-2xl bg-slate-800 hover:bg-slate-700 px-6 py-4 font-bold transition">
                            <i class="bi bi-arrow-clockwise"></i>
                            Recarregar
                        </a>

                        <button
                            class="inline-flex justify-center items-center gap-2 rounded-2xl bg-blue-600 hover:bg-blue-500 px-6 py-4 font-bold text-white shadow-lg shadow-blue-600/30 transition">
                            <i class="bi bi-save2"></i>
                            Salvar configurações
                        </button>

                    </div>

                </form>

            </section>

            <!-- PREVIEW -->
            <aside class="lg:col-span-1">

                <div class="sticky top-28 bg-slate-900/70 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl shadow-black/30">

                    <div class="p-5 border-b border-slate-800 flex items-center justify-between">
                        <div>
                            <h2 class="font-black text-white">
                                Preview
                            </h2>
                            <p class="text-xs text-slate-400">
                                Como está ficando
                            </p>
                        </div>

                        <div class="h-3 w-3 rounded-full animate-pulse"
                            style="background: <?= e($config['cor']) ?>;">
                        </div>
                    </div>

                    <div class="relative min-h-[420px] overflow-hidden">

                        <?php if (!empty($config['background'])): ?>
                            <img src="<?= e($config['background']) ?>"
                                class="absolute inset-0 h-full w-full object-cover opacity-30">
                        <?php endif; ?>

                        <div class="absolute inset-0 bg-slate-950/75"></div>

                        <div class="relative z-10 p-6">

                            <div class="flex items-center gap-3 mb-8">

                                <div class="h-12 w-12 rounded-full bg-white text-slate-950 flex items-center justify-center font-black shadow-lg">
                                    <img class="rounded-full " src="<?= htmlspecialchars($config['favicon']) ?>" alt="logo" title="logo" >
                                </div>

                                <div>
                                    <p class="font-black text-white">
                                        <?= e($config['nome_2'] ?: 'Seu site') ?>
                                    </p>
                                    <p class="text-xs text-slate-400">
                                        <?= e($config['title'] ?: 'Title da página') ?>
                                    </p>
                                </div>

                            </div>

                            <?php if (!empty($config['logo'])): ?>
                                <img src="<?= e($config['logo']) ?>"
                                    class="h-20 max-w-full object-contain mb-6 rounded-xl">
                            <?php endif; ?>

                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold mb-4"
                                style="background: <?= e($config['cor']) ?>20; color: <?= e($config['cor']) ?>;">
                                <?= e($config['texto_1'] ?: 'Texto destaque') ?>
                            </span>

                            <h1 class="text-4xl font-black leading-tight text-white">
                                <?= e($config['texto_2'] ?: 'Título principal') ?>
                                <span style="color: <?= e($config['cor']) ?>;">
                                    <?= e($config['texto_3'] ?: 'colorido') ?>
                                </span>
                            </h1>

                            <p class="mt-4 text-sm text-slate-300 leading-relaxed">
                                <?= e($config['texto_4'] ?: 'Descrição do seu site aparecerá aqui.') ?>
                            </p>

                            <?php if (!empty($config['banner'])): ?>
                                <img src="<?= e($config['banner']) ?>"
                                    class="mt-6 rounded-2xl border border-slate-700 shadow-xl w-full object-cover">
                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </aside>

        </div>

    </main>

</body>
</html>