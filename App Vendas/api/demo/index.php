<?php
header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . "/db.php";

$token = "HIOSAKI_2026_TOKEN";

$headers = getallheaders();
$auth = $headers["Authorization"] ?? "";

if ($auth !== "Bearer " . $token) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Token inválido"]);
    exit;
}

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET") {
    $stmt = $pdo->query("SELECT * FROM demo ORDER BY id DESC");
    echo json_encode(["success" => true, "produtos" => $stmt->fetchAll()]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$action = $data["action"] ?? "";

if ($method === "POST" && $action === "adicionar") {
    $stmt = $pdo->prepare("
        INSERT INTO demo (nome, categoria, preco, imagem, descricao, disponivel)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $data["nome"],
        $data["categoria"],
        $data["preco"],
        $data["imagem"],
        $data["descricao"],
        !empty($data["disponivel"]) ? 1 : 0
    ]);

    echo json_encode(["success" => true]);
    exit;
}

if ($method === "POST" && $action === "editar") {
    $stmt = $pdo->prepare("
        UPDATE demo SET
        nome = ?, categoria = ?, preco = ?, imagem = ?, descricao = ?, disponivel = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $data["nome"],
        $data["categoria"],
        $data["preco"],
        $data["imagem"],
        $data["descricao"],
        !empty($data["disponivel"]) ? 1 : 0,
        $data["id"]
    ]);

    echo json_encode(["success" => true]);
    exit;
}

if ($method === "POST" && $action === "excluir") {
    $stmt = $pdo->prepare("DELETE FROM demo WHERE id = ?");
    $stmt->execute([$data["id"]]);

    echo json_encode(["success" => true]);
    exit;
}

echo json_encode(["success" => false, "message" => "Ação inválida"]);