<?php
session_start();

require_once __DIR__ . '/services/ApiClient.php'; // Caminho para a classe ApiClient

// Criar instância do ApiClient
$apiClient = new ApiClient('https://web-production-2a8d.up.railway.app/');
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
                        <?php
                        if (isset($_GET['id'])) {
                            $medicos_id = $_GET['id'];
                            
                            // Chamar a API para obter os dados do médico
                            $medico = $apiClient->buscarMedicoPorId($medicos_id);

                            if (!empty($medico)) {
                                ?>
                                <form action="acoes.php" method="POST">
                                    <input type="hidden" name="medicos_id" value="<?= htmlspecialchars($medico['id']) ?>">
                                    <div class="mb-3">
                                        <label>Nome</label>
                                        <input type="text" name="nome" value="<?= htmlspecialchars($medico['nome']) ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Email</label>
                                        <input type="email" name="email" value="<?= htmlspecialchars($medico['email']) ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Data de Nascimento</label>
                                        <input type="date" name="data_nascimento" value="<?= htmlspecialchars($medico['data_nascimento']) ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Senha</label>
                                        <input type="password" name="senha" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" name="update_medicos" class="btn btn-primary">Salvar</button>
                                    </div>
                                </form>
                                <?php
                            } else {
                                echo "<h5>Médico não encontrado</h5>";
                            }
                        } else {
                            echo "<h5>ID do médico não fornecido</h5>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
