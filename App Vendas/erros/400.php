<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>400 - Requisição Inválida</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body{
background:#0f172a;
}
.glass{
background:rgba(255,255,255,.05);
backdrop-filter:blur(14px);
border:1px solid rgba(255,255,255,.08);
}
</style>
</head>
<body class="min-h-screen flex items-center justify-center text-white p-4">

<div class="glass max-w-lg w-full rounded-3xl p-10 text-center shadow-2xl">

<div class="w-24 h-24 mx-auto rounded-full bg-yellow-500/20 flex items-center justify-center mb-6">
<i class="bi bi-exclamation-circle text-5xl text-yellow-400"></i>
</div>

<h1 class="text-7xl font-black">400</h1>

<h2 class="text-3xl font-bold mt-4">
Requisição inválida
</h2>

<p class="text-gray-300 mt-4">
O servidor não conseguiu processar sua solicitação.
</p>

<div class="mt-8 flex gap-4 justify-center flex-wrap">

<a href="/"
class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-2xl transition">
<i class="bi bi-house"></i>
Início
</a>

<button onclick="history.back()"
class="bg-white/10 hover:bg-white/20 px-6 py-3 rounded-2xl transition">
<i class="bi bi-arrow-left"></i>
Voltar
</button>

</div>

</div>

</body>
</html>