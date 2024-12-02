<?php
session_start();

// Verifique se o ID do médico foi passado via GET
if (isset($_GET['id'])) {
    $medicos_id = $_GET['id'];

    // Configurar a URL da API para obter os dados do médico
    $apiUrl = 'https://web-production-2a8d.up.railway.app/medicos/' . $medicos_id;

    // Inicializar o cURL
    $ch = curl_init($apiUrl);

    // Definir as opções cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, true);

    // Executar a requisição
    $response = curl_exec($ch);

    // Verificar se houve erro na requisição
    if ($response === false) {
        $_SESSION['message'] = 'Erro ao conectar à API: ' . curl_error($ch);
        header('Location: index.php');
        exit;
    }

    // Fechar a conexão cURL
    curl_close($ch);

    // Decodificar a resposta JSON da API
    $responseData = json_decode($response, true);

    // Verificar se a resposta foi bem-sucedida
    if ($responseData['success'] && isset($responseData['data'])) {
        $medicos = $responseData['data'];
    } else {
        $_SESSION['message'] = 'Médico não encontrado.';
        header('Location: index.php');
        exit;
    }
} else {
    $_SESSION['message'] = 'ID do médico não fornecido.';
    header('Location: index.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Médicos - Visualizar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <?php include('navbar.php'); ?>
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4>Visualizar médico
                <a href="index.php" class="btn btn-danger float-end">Voltar</a>
              </h4>
            </div>
            <div class="card-body">
                <?php if (isset($medicos)): ?>
                <div class="mb-3">
                  <label>Nome</label>
                  <p class="form-control">
                    <?= htmlspecialchars($medicos['nome'] ?? 'N/A'); ?>
                  </p>
                </div>
                <div class="mb-3">
                  <label>Email</label>
                  <p class="form-control">
                    <?= htmlspecialchars($medicos['email'] ?? 'N/A'); ?>
                  </p>
                </div>
                <div class="mb-3">
                  <label>Data Nascimento</label>
                  <p class="form-control">
                    <?= isset($medicos['data_nascimento']) ? date('d/m/Y', strtotime($medicos['data_nascimento'])) : 'N/A'; ?>
                  </p>
                </div>
                <?php else: ?>
                  <h5>Erro: Médico não encontrado</h5>
                <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
