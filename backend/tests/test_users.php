<?php

class UtilisateurApiTest
{
    private $apiUrl = 'http://localhost/iMangerMieux/backend/users.php';

    public function runTests()
    {
        echo "<h2>Running Utilisateur API Tests</h2>";

        $idUtilisateur = $this->testCreateUtilisateur();
        if ($idUtilisateur) {
            $this->testGetUtilisateur($idUtilisateur);
            $this->testUpdateUtilisateur($idUtilisateur);
            $this->testDeleteUtilisateur($idUtilisateur);
        }
    }

    private function testCreateUtilisateur()
    {
        echo "<h3>Test Create Utilisateur</h3>";
        $data = [
            'ID_AGE' => 2,
            'ID_SEXE' => 14,
            'ID_NS' => 2,
            'USERNAME' => 'testuser',
            'PASSWORD' => 'testpassword'
        ];
        $response = $this->makeRequest('POST', $data);

        if ($response['status'] == 201) {
            echo "Create Test Passed<br>";
            echo "Created Utilisateur ID: " . $response['data']->ID_UTILISATEUR . "<br>";
            return $response['data']->ID_UTILISATEUR;
        } else {
            echo "Create Test Failed<br>";
            echo "Response: " . json_encode($response) . "<br>";
            return null;
        }
    }

    private function testGetUtilisateur($idUtilisateur)
    {
        echo "<h3>Test Get Utilisateur</h3>";
        $response = $this->makeRequest('GET', [], $idUtilisateur);

        if ($response['status'] == 200) {
            echo "Get Test Passed<br>";
            echo "Utilisateur Data: " . json_encode($response['data']) . "<br>";
        } else {
            echo "Get Test Failed<br>";
            echo "Response: " . json_encode($response) . "<br>";
        }
    }

    private function testUpdateUtilisateur($idUtilisateur)
    {
        echo "<h3>Test Update Utilisateur</h3>";
        $data = [
            'ID_AGE' => 3,
            'ID_SEXE' => 1,
            'ID_NS' => 3,
            'USERNAME' => 'updateduser',
            'PASSWORD' => 'newpassword'
        ];
        $response = $this->makeRequest('PUT', $data, $idUtilisateur);

        if ($response['status'] == 200) {
            echo "Update Test Passed<br>";
            echo "Updated Utilisateur Data: " . json_encode($response['data']) . "<br>";
        } else {
            echo "Update Test Failed<br>";
            echo "Response: " . json_encode($response) . "<br>";
        }
    }

    private function testDeleteUtilisateur($idUtilisateur)
    {
        echo "<h3>Test Delete Utilisateur</h3>";
        $response = $this->makeRequest('DELETE', [], $idUtilisateur);

        if ($response['status'] == 200) {
            echo "Delete Test Passed<br>";
        } else {
            echo "Delete Test Failed<br>";
            echo "Response: " . json_encode($response) . "<br>";
        }
    }

    private function makeRequest($method, $data = [], $id = null)
    {
        $url = $id ? $this->apiUrl . '?ID_UTILISATEUR=' . $id : $this->apiUrl;
        $options = [
            'http' => [
                'method' => $method,
                'header' => 'Content-type: application/json',
                'content' => json_encode($data)
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $statusCode = $http_response_header[0];

        preg_match('/\d{3}/', $statusCode, $match);
        $status = (int)$match[0];

        return [
            'status' => $status,
            'data' => json_decode($response)
        ];
    }
}

// Run tests
$test = new UtilisateurApiTest();
$test->runTests();

?>
