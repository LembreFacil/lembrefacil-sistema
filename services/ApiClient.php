<?php
class ApiClient {
    private $baseUrl;

    public function __construct($baseUrl) {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    private function sendRequest($method, $endpoint, $data = []) {
        $url = $this->baseUrl . $endpoint;
        $ch = curl_init();

        // Configurações básicas do cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        if ($method === 'GET' && !empty($data)) {
            $url .= '?' . http_build_query($data);
        }

        curl_setopt($ch, CURLOPT_URL, $url);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['success' => false, 'message' => "Erro na requisição: $error"];
        }

        return json_decode($response, true);
    }

    // Listar médicos
    public function listarMedicos() {
        return $this->sendRequest('GET', '/api.php');
    }

    // Criar médico
    public function createMedico($nome, $email, $dataNascimento, $senha = null) {
        $data = [
            'action' => 'create_medicos',
            'nome' => $nome,
            'email' => $email,
            'data_nascimento' => $dataNascimento,
            'senha' => $senha
        ];
        return $this->sendRequest('POST', '/api.php', $data);
    }

    // Atualizar médico
    public function atualizarMedico($id, $nome, $email, $dataNascimento, $senha = null) {
        $data = [
            'update_medicos' => true,
            'medicos_id' => $id,
            'nome' => $nome,
            'email' => $email,
            'data_nascimento' => $dataNascimento,
            'senha' => $senha
        ];
        return $this->sendRequest('POST', '/api.php', $data);
    }


    // Deletar médico
    public function deletarMedico($id) {
        $data = [
            'delete_medicos' => $id
        ];
        return $this->sendRequest('POST', '/api.php', $data);
    }
}

// Exemplo de uso
$apiClient = new ApiClient('https://web-production-2a8d.up.railway.app/');
