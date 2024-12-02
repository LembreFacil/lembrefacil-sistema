<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_medicos'])) {
    // Obter os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $data_nascimento = $_POST['data_nascimento'];
    $senha = $_POST['senha'];

    // Configurar a URL da API
    $apiUrl = 'https://web-production-2a8d.up.railway.app/';

    // Configurar cURL para atualizar um médico
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'action' => 'update_medicos', // Adiciona a ação esperada pela API
        'nome' => $nome,
        'email' => $email,
        'data_nascimento' => $data_nascimento,
        'senha' => $senha,
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        // Erro na chamada da API
        $_SESSION['message'] = 'Erro ao comunicar-se com a API: ' . curl_error($ch);
    } else {
        $responseDecoded = json_decode($response, true);
        if ($responseDecoded['success']) {
            // Sucesso na atualização do médico
            $_SESSION['message'] = 'Médico atualizado com sucesso!';
            header('Location: index.php');
            exit;
        } else {
            // Erro retornado pela API
            $_SESSION['message'] = 'Erro ao atualizar o médico: ' . $responseDecoded['message'];
        }
    }

    curl_close($ch);
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
                        <form action="medico-edit.php" method="POST">
                            <div class="mb-3">
                                <label>Nome</label>
                                <input type="text" name="nome" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Data de Nascimento</label>
                                <input type="date" name="data_nascimento" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Senha</label>
                                <input type="password" name="senha" class="form-control">
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="update_medicos" class="btn btn-primary">Salvar</button>
                            </div>
                        </form>
                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-info mt-3">
                                <?= $_SESSION['message']; ?>
                                <?php unset($_SESSION['message']); ?>
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
