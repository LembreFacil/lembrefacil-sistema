<?php
session_start();

// URL da API
$apiUrl = 'https://web-production-2a8d.up.railway.app/';

// Função para fazer a requisição à API
function apiRequest($url, $method = 'GET', $data = []) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    if ($method == 'POST' || $method == 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $_SESSION['message'] = 'Erro ao comunicar-se com a API: ' . curl_error($ch);
        curl_close($ch);
        return null;
    }

    curl_close($ch);
    return json_decode($response, true);
}

// Obter o ID do médico da URL
$medico_id = $_GET['id'] ?? null;
$medico = null;

if ($medico_id) {
    // Buscar informações do médico pela API
    $response = apiRequest($apiUrl . "/medicos/{$medico_id}");

    if ($response && isset($response['success']) && $response['success']) {
        $medico = $response['data'];  // Dados do médico
    } else {
        $_SESSION['message'] = 'Erro ao carregar informações do médico.';
        header('Location: index.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_medicos'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $data_nascimento = $_POST['data_nascimento'];
    $senha = $_POST['senha'];

    $data = [
        'nome' => $nome,
        'email' => $email,
        'data_nascimento' => $data_nascimento,
        'senha' => $senha
    ];

    $response = apiRequest($apiUrl . "/medicos/{$medico_id}", 'PUT', $data);

    if ($response && isset($response['success']) && $response['success']) {
        $_SESSION['message'] = 'Médico atualizado com sucesso!';
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['message'] = 'Erro ao atualizar o médico: ' . ($response['message'] ?? 'Erro desconhecido.');
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
                            <form action="" method="POST">
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
                                    <button type="submit" name="update_medicos" class="btn btn-primary">Salvar</button>
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
