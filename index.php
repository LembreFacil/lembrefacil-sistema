<?php
session_start(); // Inicia a sessão

$apiUrl = 'https://web-production-2a8d.up.railway.app'; // URL base da API

// Recuperar lista de médicos
$ch = curl_init("$apiUrl"); // Configura a URL da API
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Retornar resposta em vez de exibi-la
curl_setopt($ch, CURLOPT_HTTPGET, true); // Define o método GET

$response = curl_exec($ch);

if (curl_errno($ch)) {
    $_SESSION['mensagem'] = 'Erro ao carregar a lista de médicos: ' . curl_error($ch);
    $medicos = [];
} else {
    $responseDecoded = json_decode($response, true);
    if ($responseDecoded['success']) {
        $medicos = $responseDecoded['data'];
    } else {
        $_SESSION['mensagem'] = 'Erro ao carregar a lista de médicos: ' . $responseDecoded['message'];
        $medicos = [];
    }
}

curl_close($ch);

// Verifica se há uma ação de exclusão
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_medicos' && isset($_POST['medicos_id'])) {
    $medicos_id = $_POST['medicos_id'];

    // Configuração de cURL para deletar médico
    $ch = curl_init("$apiUrl");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'action' => 'delete_medicos',
        'medicos_id' => $medicos_id,
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $_SESSION['mensagem'] = 'Erro ao excluir médico: ' . curl_error($ch);
    } else {
        $responseDecoded = json_decode($response, true);
        if ($responseDecoded['success']) {
            $_SESSION['mensagem'] = 'Médico excluído com sucesso';
        } else {
            $_SESSION['mensagem'] = 'Erro ao excluir médico: ' . $responseDecoded['message'];
        }
    }

    curl_close($ch);

    // Redireciona após a exclusão
    header('Location: index.php');
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LembreFácil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<?php include('navbar.php'); ?>
<div class="container mt-4">
    <?php include('mensagem.php'); ?> <!-- Exibe mensagens de sessão -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Lista de Médicos
                        <a href="medico-create.php" class="btn btn-primary float-end">Adicionar Médico</a>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Data Nascimento</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($medicos)): ?>
                                <?php foreach ($medicos as $medico): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($medico['id']) ?></td>
                                        <td><?= htmlspecialchars($medico['nome']) ?></td>
                                        <td><?= htmlspecialchars($medico['email']) ?></td>
                                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($medico['data_nascimento']))) ?></td>
                                        <td>
                                            <a href="medico-view.php?id=<?= urlencode($medico['id']) ?>" class="btn btn-secondary btn-sm">
                                                <span class="bi-eye-fill"></span>&nbsp;Visualizar
                                            </a>
                                            <a href="medico-edit.php?id=<?= urlencode($medico['id']) ?>" class="btn btn-success btn-sm">
                                                <span class="bi-pencil-fill"></span>&nbsp;Editar
                                            </a>
                                            <form action="index.php" method="POST" class="d-inline">
                                                <input type="hidden" name="action" value="delete_medicos">
                                                <input type="hidden" name="medicos_id" value="<?= htmlspecialchars($medico['id']) ?>">

                                                <button onclick="return confirm('Tem certeza que deseja excluir?')" 
                                                        type="submit" 
                                                        class="btn btn-danger btn-sm">
                                                    <span class="bi-trash3-fill"></span>&nbsp;Excluir
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Nenhum médico encontrado</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
