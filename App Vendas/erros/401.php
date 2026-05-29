<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>401 - Não autorizado</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-slate-950 min-h-screen flex items-center justify-center text-white p-4">

<div class="bg-white/5 border border-white/10 rounded-3xl p-10 max-w-lg w-full text-center backdrop-blur-xl">

<div class="w-24 h-24 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
<i class="bi bi-lock-fill text-red-400 text-5xl"></i>
</div>

<h1 class="text-7xl font-black">401</h1>

<h2 class="text-3xl font-bold mt-4">
Acesso não autorizado
</h2>

<p class="text-gray-400 mt-4">
Você precisa fazer login para acessar esta página.
</p>

<div class="mt-8 flex justify-center gap-4 flex-wrap">

<a href="/login"
class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-2xl transition">
<i class="bi bi-box-arrow-in-right"></i>
Login
</a>

<button onclick="history.back()"
class="bg-white/10 hover:bg-white/20 px-6 py-3 rounded-2xl transition">
Voltar
</button>

</div>

</div>

</body>
</html>