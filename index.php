<?php
session_start();

require_once __DIR__ . '/services/ApiClient.php'; // Certifique-se de que o caminho está correto

$apiClient = new ApiClient('https://web-production-2a8d.up.railway.app/');

// Recuperar lista de médicos
$response = $apiClient->listarMedicos();
$medicos = [];
if ($response['success']) {
    $medicos = $response['data'];
} else {
    $_SESSION['mensagem'] = 'Erro ao carregar a lista de médicos: ' . $response['message'];
}

$response = $apiClient->deletarMedico();
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
    <?php include('mensagem.php'); ?>
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

                                                <!-- Confirmação antes de enviar -->
                                                <button onclick="return confirm('Tem certeza que deseja excluir?')" 
                                                        type="submit" 
                                                        class="btn btn-danger btn-sm"
                                                        id="delete-btn">
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
