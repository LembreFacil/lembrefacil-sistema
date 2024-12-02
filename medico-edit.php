<?php
session_start();

// URL da API
$apiUrl = 'https://web-production-2a8d.up.railway.app/';

// Verificar se o ID do médico foi fornecido
$medico_id = $_GET['id'] ?? null;
$medico = null;

// Verificar se o ID do médico foi fornecido
if (!$medico_id) {
    $_SESSION['message'] = 'ID do médico não fornecido.';
    header('Location: index.php');
    exit;
}

// Buscar informações do médico pela API usando cURL
$ch = curl_init($apiUrl . "/medicos/{$medico_id}");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$response = json_decode($response, true);

if ($response && isset($response['success']) && $response['success']) {
    $medico = $response['data'];
} else {
    $_SESSION['message'] = 'Erro ao carregar informações do médico.';
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_medicos'])) {
    // Obter os dados do formulário
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $data_nascimento = $_POST['data_nascimento'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Validar os dados
    if (empty($nome) || empty($email) || empty($data_nascimento)) {
        $_SESSION['message'] = 'Por favor, preencha todos os campos obrigatórios.';
        header('Location: ' . $_SERVER['PHP_SELF'] . "?id={$medico_id}");
        exit;
    }

    // Preparar os dados para envio
    $data = [
        'medicos_id' => $medico_id,
        'nome' => $nome,
        'email' => $email,
        'data_nascimento' => $data_nascimento,
        'senha' => $senha,
    ];

    // Configurar cURL para editar o médico
    $ch = curl_init($apiUrl . "/medicos/{$medico_id}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); // Usar PUT para edição
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);
    curl_close($ch);

    // Verificar se a requisição foi bem-sucedida
    $response = json_decode($response, true);
    if ($response && isset($response['success']) && $response['success']) {
        $_SESSION['message'] = 'Médico atualizado com sucesso!';
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['message'] = 'Erro ao atualizar o médico: ' . ($response['message'] ?? 'Erro desconhecido.');
        header('Location: ' . $_SERVER['PHP_SELF'] . "?id={$medico_id}");
        exit;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Médico - Editar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php include('navbar.php'); ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Editar médico
                            <a href="index.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-info mt-3">
                                <?= $_SESSION['message']; ?>
                                <?php unset($_SESSION['message']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($medico): ?>
                            <form action="medico-edit.php" method="POST">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
