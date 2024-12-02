<?php
session_start();

// Defina a URL da sua API
$apiUrl = 'https://web-production-2a8d.up.railway.app/';

// Função para fazer a requisição à API
function apiRequest($url, $method = 'GET', $data = []) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    }
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// Verifique se o ID do médico foi fornecido na URL
$medico_id = $_GET['id'] ?? null;
$medico = null;

if ($medico_id) {
    // Obter os dados do médico da API
    $response = apiRequest($apiUrl . "/medicos/{$medico_id}");

    if ($response && isset($response['success']) && $response['success']) {
        $medico = $response['data'];
    } else {
        $_SESSION['message'] = 'Erro ao carregar informações do médico.';
        header('Location: index.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter os dados do formulário
    $nome = $_POST['nome'] ?? ''; // Usar '??' para evitar "undefined index"
    $email = $_POST['email'] ?? ''; 
    $data_nascimento = $_POST['data_nascimento'] ?? ''; 
    $senha = $_POST['senha'] ?? ''; 

    // Dados a serem enviados para a API de atualização
    $data = [
        'action' => 'update_medicos',  // Ação especificada para a API
        'nome' => $nome,
        'email' => $email,
        'data_nascimento' => $data_nascimento,
        'senha' => $senha
    ];

    // Enviar os dados para a API para atualizar o médico
    $response = apiRequest($apiUrl . "/medicos/{$medico_id}", 'POST', $data);

    if ($response && isset($response['success']) && $response['success']) {
        $_SESSION['message'] = 'Médico atualizado com sucesso!';
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['message'] = 'Erro ao atualizar o médico: ' . ($response['message'] ?? 'Erro desconhecido.');
    }
}
?>

<!-- HTML para o formulário de edição de médico -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Médico</title>
    <!-- Adicione seus links de CSS aqui -->
</head>
<body>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert">
            <?= $_SESSION['message']; ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <?php if ($medico): ?>
        <h2>Editar Médico: <?= htmlspecialchars($medico['nome'] ?? ''); ?></h2>
        <form action="medico-edit.php?id=<?= $medico_id ?>" method="POST">
            <div class="mb-3">
                <label>Nome</label>
                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($medico['nome'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($medico['email'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label>Data de Nascimento</label>
                <input type="date" name="data_nascimento" class="form-control" value="<?= htmlspecialchars($medico['data_nascimento'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label>Senha</label>
                <input type="password" name="senha" class="form-control">
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-warning">
            Médico não encontrado.
        </div>
    <?php endif; ?>
</body>
</html>
