<?php
$config = json_decode(
    file_get_contents(__DIR__ . '/site.json'),
    true
);
?>
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ======================================
// EXIGIR LOGIN
// ======================================
if (!isset($_SESSION['usuario'])) {
    header('Location: ./login.php');
    exit;
}

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
function uploadImagem($file){

    if(!$file || $file['error'] !== 0){
        return null;
    }

    // Pasta real onde salva
    $uploadDir = __DIR__ . '/uploads/';

    // Link público que vai para o banco
    $uploadUrl = 'https://demo.hiosaki.com.br/dashboard/uploads/';

    if(!is_dir($uploadDir)){
        mkdir($uploadDir, 0777, true);
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    $permitidas = ['jpg','jpeg','png','webp','gif'];

    if(!in_array($ext, $permitidas)){
        return null;
    }

    $nome = uniqid('img_', true) . '.' . $ext;

    if(move_uploaded_file($file['tmp_name'], $uploadDir . $nome)){
        return $uploadUrl . $nome;
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
        'disponivel' => ($_POST['disponivel'] ?? 0)
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    html {
        scroll-behavior: smooth;
    }
    </style>
</head> 
<body class="bg-slate-950 text-slate-200">  <nav class="sticky top-0 z-50

bg-slate-900/80
border-b border-[#fead0a]
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

                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-black shadow-lg shadow-blue-600/30 font-bold text-xl tracking-tighter animate-pulse">
                        <img class="rounded-full " src="<?= htmlspecialchars($config['favicon']) ?>" alt="logo" title="logo" >
                    </div>  

                </div>  

                <!-- TEXTO -->  
                <div>  

                    <div class="flex items-center gap-2 flex-wrap">  

                        <h1 class="ttext-white font-bold text-lg tracking-wider">  

                            <a href="/"><?= htmlspecialchars($config['title']) ?> </a>

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
                        <span onclick="abrirProfile()" class="text-white font-semibold cursor-pointer">  

                            <?= htmlspecialchars($_SESSION['usuario']['nome'] ?? 'Usuário') ?>  

                        </span>  

                    </p>  

                </div>  

            </div>  

            <!-- DIREITA -->  
            <div class="flex flex-wrap items-center gap-3">  

                <!-- BTN ADICIONAR -->  
                <button onclick="abrirAdicionar()" class="inline-flex items-center gap-2  
            rounded-2xl  
            bg-gradient-to-r  
            from-blue-600  
            to-cyan-500  
            px-5 py-3  
            text-sm font-semibold text-white  
            shadow-lg shadow-blue-500/20  
            hover:scale-105  
            hover:shadow-blue-500/40  
            transition-all duration-300">  

                   <i class="bi bi-cart-plus text-base"></i>

                    <span class="hidden sm:inline">  
                        Adicionar Produtos  
                    </span>  

                </button> 

                <!-- BTN SAIR -->  
                <a href="logout.php" class="inline-flex items-center gap-2  
            rounded-2xl  
            bg-gradient-to-r  
            from-rose-600  
            to-red-500  
            px-5 py-3  
            text-sm font-semibold text-white  
            shadow-lg shadow-red-500/20  
            hover:scale-105  
            hover:shadow-red-500/40  
            transition-all duration-300">  

                    <i class="bi bi-escape text-base"></i>  

                    <span class="hidden sm:inline">  
                        Sair  
                    </span>  


                </a>  
                <a href="https://file.hiosaki.com.br/" class="inline-flex items-center gap-2  
            rounded-2xl  
            bg-gradient-to-r  
            from-green-800  
            to-green-500  
            px-5 py-3  
            text-sm font-semibold text-white  
            shadow-lg shadow-green-500/20  
            hover:scale-105  
            hover:shadow-green-500/40  
            transition-all duration-300">  

                    <i class="bi bi-folder2-open text-base"></i>

                    <span class="hidden sm:inline">  
                        FileBrowser  
                    </span>  


                </a>  

            </div>  

        </div>  

    </div>  

</nav>  

<div class="max-w-6xl mx-auto px-4 py-5">  

    <!-- BTN ADD -->  
    <div class="flex justify-end mb-6">  



    </div>  

    <!-- LISTA -->  
     <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

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
                            class="bg-[#fead0a]/10 border border-[#fead0a]/30 text-[#fead0a] font-bold uppercase tracking-widest text-xs px-4 py-2 rounded-xl">
                            Esgotado
                        </span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Conteúdo do Card -->
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h4
                            class="text-lg font-bold text-white group-hover:text-[#fead0a] transition-colors line-clamp-1">
                            <?php echo $p['nome']; ?>
                        </h4>
                       <!-- Preço e Botão -->
                  
                        <div>
                            <span class="block text-[10px] text-slate-500 uppercase tracking-wider">Preço</span>
                            <span class="text-emerald-400 font-bold mt-2">
                                R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?>
                            </span>
                        </div>
                        <p class="mt-2 text-sm text-slate-400 leading-relaxed font-light line-clamp-2">
                            <?php echo $p['descricao']; ?>
                        </p>
                    </div>

                    <!-- Preço e Botão -->
                     <div class="flex justify-between mt-4">  

                    <button onclick='editar(<?= json_encode($p) ?>)' class="rounded-full  
bg-gradient-to-r  
from-blue-600  
to-cyan-500  
px-4 py-2.5  
text-sm font-semibold text-white  
shadow-lg shadow-blue-500/20  
hover:scale-105  
hover:shadow-blue-500/40  
transition-all duration-300">  

                        <i class="bi bi-pencil"></i>  

                        Editar  

                    </button>  

                    <a href="?excluir=<?= $p['id'] ?>" class="rounded-full  
bg-gradient-to-r  
from-rose-600  
to-red-500  
px-4 py-2.5  
text-sm font-semibold text-white  
shadow-lg shadow-red-500/20  
hover:scale-105  
hover:shadow-red-500/40  
transition-all duration-300  
                        ">  

                        <i class="bi bi-trash"></i>  

                        Excluir  

                    </a>  

                </div>  

            </div>  

        </div>  

        <?php endforeach; ?>  

    </div>  

    </main>

<!-- MODAL PROFILE -->
<div id="modalProfile"
     class="hidden fixed inset-0 bg-black/70 backdrop-blur-md z-50 flex items-center justify-center p-3 md:p-5">

    <form enctype="multipart/form-data"
          class="bg-slate-900/80 border border-white/10 w-full md:w-[700px] rounded-3xl p-5 space-y-5">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-white">Perfil</h2>
                <p class="text-sm text-slate-400">Informações do usuário</p>
            </div>

            <button type="button"
                    onclick="fecharProfile()"
                    class="h-10 w-10 rounded-xl bg-white/5 hover:bg-red-500/20 text-slate-300 hover:text-red-400 transition-all">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="flex flex-col items-center text-center gap-3">

            <img
                width="100"
                height="100"
                class="rounded-full border-4 border-[#fead0a]/40 object-cover"
                src="http://gm.hiosaki.com.br/assets/avatar.avif"
                alt="Foto do perfil"
                title="Foto">

            <div>
                <h1 class="text-2xl font-bold text-white">
                    <?= htmlspecialchars($_SESSION['usuario']['nome'] ?? 'Usuário') ?>
                </h1>

                <p class="text-sm text-blue-400 font-semibold">
                    <?= htmlspecialchars($_SESSION['usuario']['nivel'] ?? 'Nível') ?>
                </p>

                <p class="text-sm text-slate-400">
                    <?= htmlspecialchars($_SESSION['usuario']['info'] ?? 'Info') ?>
                </p>
            </div>
         <a onclick="abrirProfileE()" class="inline-flex items-center gap-2  
            rounded-2xl  cursor-pointer
            bg-gradient-to-r  
            from-blue-600  
            to-cyan-500  
            px-5 py-3  
            text-sm font-semibold text-white  
            shadow-lg shadow-blue-500/20  
            hover:scale-105  
            hover:shadow-blue-500/40  
            transition-all duration-300">  Atualize suas informações

                    

                </a> 
            </div>
    </form>
</div>



<script>
function abrirProfileE() {
    document.getElementById('modalProfileE').classList.remove('hidden');
}

function fecharProfileE() {
    document.getElementById('modalProfileE').classList.add('hidden');
}

document.getElementById('formProfileE').addEventListener('submit', async function(e) {
    e.preventDefault();

    const msg = document.getElementById('profileMsg');
    msg.innerHTML = 'Salvando...';

    const formData = new FormData(this);

    const dados = {
        id: formData.get('id'),
        nome: formData.get('nome'),
        nivel: formData.get('nivel'),
        info: formData.get('info')
    };

    try {
        const response = await fetch('https://api.hiosaki.com.br/update-user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer HIOSAKI_2026_TOKEN'
            },
            body: JSON.stringify(dados)
        });

        const result = await response.json();

        if (result.success) {
            msg.innerHTML = 'Perfil atualizado com sucesso!';

            setTimeout(() => {
                location.reload();
            }, 800);
        } else {
            msg.innerHTML = result.message || 'Erro ao salvar.';
        }

    } catch (error) {
        msg.innerHTML = 'Erro ao conectar com a API.';
    }
});
</script>

<!-- MODAL ADICIONAR -->  
<div id="modalAdd" class="hidden fixed inset-0 bg-black/70 backdrop-blur-md z-50  
    flex items-center md:items-center justify-center p-2 md:p-5">  

    <form method="POST" enctype="multipart/form-data" class="bg-slate-900/50 border border-white/10  
        w-full md:w-[700px]  
        rounded-3xl md:rounded-3xl  
        p-5 space-y-3">  

        <div class="flex items-center justify-between mb-2">  

            <div>  

                <h2 class="text-xl font-bold text-white">  
                    Novo Produto  
                </h2>  

                <p class="text-sm text-slate-400">  
                    Adicione um produto ao estoque  
                </p>  

            </div>  

            <button type="button" onclick="fecharAdicionar()" class="h-10 w-10 rounded-xl bg-white/5 hover:bg-red-500/20  
                text-slate-300 hover:text-red-400 transition-all">  

                <i class="bi bi-x-lg"></i>  

            </button>  
        </div>  

        <input type="hidden" name="action" value="adicionar">  

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">  

            <input name="nome" placeholder="Nome do Produto"  
                class="p-3 bg-white/5 border border-white/10 rounded-2xl">  

            <input name="categoria" placeholder="Categoria"  
                class="p-3 bg-white/5 border border-white/10 rounded-2xl">  

            <input name="preco" type="number" step="0.01" placeholder="Preço"  
                class="p-3 bg-white/5 border border-white/10 rounded-2xl md:col-span-2">  

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:col-span-2">  

                <input type="file" name="imagem_file" class="w-full text-sm text-gray-300  
                    file:mr-4 file:px-5 file:py-2.5  
                    file:rounded-xl  
                    file:border-0  
                    file:bg-cyan-500/20  
                    file:text-cyan-300  
                    file:font-semibold  
                    file:cursor-pointer  
                    file:transition-all  
                    hover:file:bg-cyan-500/30  
                    hover:file:text-white  
                    bg-white/5  
                    border border-white/10  
                    rounded-2xl  
                    p-2">  

                <input name="imagem_url" placeholder="URL da imagem"  
                    class="p-3 bg-white/5 border border-white/10 rounded-2xl">  

            </div>  

            <textarea name="descricao" placeholder="Descrição do produto"  
                class="p-3 bg-white/5 border border-white/10 rounded-2xl md:col-span-2 h-28"></textarea>  

            <label class="md:col-span-2">

    <div class="flex items-center justify-between
        bg-white/5
        border border-white/10
        rounded-2xl
        p-4
        hover:border-cyan-500/40
        transition-all">

        <div>

            <div class="text-white font-semibold">
                Produto disponível
            </div>

            <div class="text-xs text-slate-400">
                Ativar exibição no catálogo
            </div>

        </div>

        <div class="relative">

            <input
                type="checkbox"
                name="disponivel"
                checked
                class="peer sr-only">

            <div class="
                w-14
                h-8
                rounded-full
                bg-slate-700
                transition
                peer-checked:bg-emerald-500">

            </div>

            <div class="
                absolute
                left-1
                top-1
                h-6
                w-6
                rounded-full
                bg-white
                transition-all
                peer-checked:translate-x-6">

            </div>

        </div>

    </div>

</label>

            <button class="md:col-span-2 rounded-full  
bg-gradient-to-r  
from-blue-600  
to-cyan-500  
px-4 py-2.5  
text-sm font-semibold text-white  
shadow-lg shadow-blue-500/20  
hover:scale-105  
hover:shadow-blue-500/40  
transition-all duration-300">  

                <i class="bi bi-check2-circle"></i>  

                Salvar Produto  

            </button>  

        </div>  

    </form>  

</div>  

<!-- MODAL EDITAR -->  
<div id="modal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-md  
    flex items-center justify-center z-50 p-4">  

    <form method="POST" enctype="multipart/form-data" class="bg-slate-900/50 border border-white/10  
        w-full md:w-96 p-5 rounded-3xl space-y-2">  

        <div class="flex items-center justify-between mb-2">  

            <div>  

                <h2 class="text-xl font-bold text-white">  
                    Alteração de produto  
                </h2>  

                <p class="text-sm text-slate-400">  
                    Edite as informações abaixo  
                </p>  

            </div>  

            <button type="button" onclick="fechar()" class="h-10 w-10 rounded-xl bg-white/5 hover:bg-red-500/20  
                text-slate-300 hover:text-red-400 transition-all">  

                <i class="bi bi-x-lg"></i>  

            </button>  
        </div>  

        <input type="hidden" name="action" value="editar">  

        <input type="hidden" name="id" id="id">  

        <input id="nome" name="nome" class="w-full p-3 bg-white/5 border border-white/10 rounded-2xl">  

        <input id="categoria" name="categoria" class="w-full p-3 bg-white/5 border border-white/10 rounded-2xl">  

        <input id="preco" name="preco" class="w-full p-3 bg-white/5 border border-white/10 rounded-2xl">  

        <input type="file" name="imagem_file" class="w-full text-sm text-gray-300  
            file:mr-4 file:px-5 file:py-2.5  
            file:rounded-xl  
            file:border-0  
            file:bg-cyan-500/20  
            file:text-cyan-300  
            file:font-semibold  
            file:cursor-pointer  
            file:transition-all  
            hover:file:bg-cyan-500/30  
            hover:file:text-white  
            bg-white/5  
            border border-white/10  
            rounded-2xl  
            p-2">  

        <input id="imagem" name="imagem_url" class="w-full p-3 bg-white/5 border border-white/10 rounded-2xl">  

        <textarea id="descricao" name="descricao"  
            class="w-full p-5 bg-white/5 border border-white/10 rounded-2xl"></textarea>  
            
                        <label class="md:col-span-2">

    <div class="flex items-center justify-between
        bg-white/5
        border border-white/10
        rounded-2xl
        p-4
        hover:border-cyan-500/40
        transition-all">

        <div>

            <div class="text-white font-semibold">
                Produto disponível
            </div>

            <div class="text-xs text-slate-400">
                Ativar exibição no catálogo
            </div>

        </div>

        <div class="relative">

          <input type="hidden" name="disponivel" value="0">

<input
type="checkbox"
name="disponivel"
value="1"
class="peer sr-only"
<?= !empty($p['disponivel']) ? 'checked' : '' ?>
>
                

            <div class="
                w-14
                h-8
                rounded-full
                bg-slate-700
                transition
                peer-checked:bg-emerald-500">

            </div>

            <div class="
                absolute
                left-1
                top-1
                h-6
                w-6
                rounded-full
                bg-white
                transition-all
                peer-checked:translate-x-6">

            </div>

        </div>

    </div>

</label>
            
        <button class="w-full rounded-full  
bg-gradient-to-r  
from-blue-600  
to-cyan-500  
px-4 py-2.5  
text-sm font-semibold text-white  
shadow-lg shadow-blue-500/20  
hover:scale-105  
hover:shadow-blue-500/40  
transition-all duration-300">  

            Salvar Alteração  

        </button>  


    </form>  

</div>  

<script>  
function abrirProfile() {  
    document.getElementById('modalProfile').classList.remove('hidden');  
}  

function fecharProfile() {  
    document.getElementById('modalProfile').classList.add('hidden');  
}
</script>  

<script>  
function abrirAdicionar() {  
    document.getElementById('modalAdd').classList.remove('hidden');  
}  

function fecharAdicionar() {  
    document.getElementById('modalAdd').classList.add('hidden');  
}  

function editar(p) {  

    document.getElementById('modal').classList.remove('hidden');  

    document.getElementById('id').value = p.id;  
    document.getElementById('nome').value = p.nome;  
    document.getElementById('categoria').value = p.categoria;  
    document.getElementById('preco').value = p.preco;  
    document.getElementById('imagem').value = p.imagem;  
    document.getElementById('descricao').value = p.descricao;  
}  

function fechar() {  
    document.getElementById('modal').classList.add('hidden');  
}  
</script>  

<!-- BTN VOLTAR TOPO -->
    <button id="btnTopo" onclick="voltarTopo()" class="hidden fixed bottom-30 right-6 z-50 hover:animate-pulse cursor-pointer
bg-gradient-to-r
from-[#fead0a]
to-[#fead0a]
w-14 h-14
rounded-full
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
            class="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-[1px] bg-gradient-to-r from-transparent via-[#fead0a]/30 to-transparent">
        </div>

        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left sm:flex sm:items-center sm:justify-between text-xs text-slate-500">

            <!-- Direitos Autorais e Marca -->
            <div class="mb-6 sm:mb-0 space-y-1">
                <style>
                    .sub-text{
                    font-family:'Playfair Display',
                    serif; 
                    font-size:50px;
                        
                    }
                    </style>
                <p class="sub-text font-bold text-slate-300 text-sm tracking-wider uppercase">
                    <span class="text-1x1"><?= htmlspecialchars($config['title']) ?></p>
                <p class="text-slate-500 font-light">
                    © 2026 • Todos os direitos reservados. Hiosaki
                </p>
            </div>

            <!-- Links e Créditos de Desenvolvimento -->
            <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6 justify-center sm:justify-end">

                <!-- Link de Contato Estilizado (Verde WhatsApp) -->
                <a href="https://wa.me/5516920016949" target="_blank"
                    class="inline-flex items-center gap-1.5 text-[#fead0a] bg-[#fead0a]/5 border border-[#fead0a]/20 px-3 py-1.5 rounded-xl hover:bg-[#fead0a] hover:text-slate-950 transition-all duration-300 font-medium shadow-lg shadow-[#fead0a]/20">
                    <i class="bi bi-whatsapp"></i>
                    Contato Comercial
                </a>

                <!-- Créditos do Desenvolvedor -->
                <div
                    class="flex items-center gap-1 text-slate-500 bg-slate-900/40 border border-slate-900 px-3 py-1.5 rounded-xl">
                    <span>Desenvolvido por</span>
                    <span
                        class="text-slate-300 font-semibold hover:text-[#fead0a] transition-colors cursor-default shadow-lg hover-text-[#fead0a] transition-all duration-300 hover:rounded-xl hover:py-0.2 px-0.2">
                        Alisson Hiosaki
                    </span>
                    <span class="h-1.5 w-1.5 rounded-full bg-[#fead0a] ml-1 animate-pulse"></span>
                </div>

            </div>
        </div>
    </footer>

</body>

</html>