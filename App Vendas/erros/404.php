<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página não encontrada</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background:
                radial-gradient(circle at top left, rgba(59,130,246,.20), transparent 30%),
                radial-gradient(circle at bottom right, rgba(168,85,247,.20), transparent 30%),
                #0f172a;
        }

        .glass {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.1);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center text-white px-4">

    <div class="glass max-w-xl w-full rounded-3xl p-8 md:p-12 shadow-2xl text-center">

        <!-- Ícone -->
        <div class="flex justify-center mb-6">
            <div class="w-24 h-24 rounded-full bg-red-500/20 flex items-center justify-center">
                <i class="bi bi-exclamation-triangle text-5xl text-red-400"></i>
            </div>
        </div>

        <!-- Código -->
        <h1 class="text-7xl md:text-8xl font-black text-white drop-shadow-lg">
            404
        </h1>

        <!-- Título -->
        <h2 class="mt-4 text-2xl md:text-3xl font-bold">
            Página não encontrada
        </h2>

        <!-- Texto -->
        <p class="mt-4 text-gray-300 leading-relaxed">
            A página que você tentou acessar não existe,
            foi removida ou está temporariamente indisponível.
        </p>

        <!-- Botões -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">

            <a href="/"
               class="px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 transition-all duration-300 font-semibold shadow-lg">
                <i class="bi bi-house-door me-2"></i>
                Página Inicial
            </a>

            <button onclick="history.back()"
                    class="px-6 py-3 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/10 transition-all duration-300 font-semibold">
                <i class="bi bi-arrow-left me-2"></i>
                Voltar
            </button>

        </div>

        <!-- Rodapé -->
        <div class="mt-10 text-sm text-gray-400">
            © <?= date('Y') ?> Seu Sistema
        </div>

    </div>

</body>
</html>