<?php

class AlimentApiTest
{
    private $apiUrl = 'http://localhost/iMangerMieux/backend/aliments.php';

    public function runTests()
    {
        echo "<h2>Running Aliment API Tests</h2>";

        $idAliment = $this->testCreateAliment();
        if ($idAliment) {
            $this->testGetAliment($idAliment);
            $this->testUpdateAliment($idAliment);
            $this->testDeleteAliment($idAliment);
        }
    }

    private function testCreateAliment()
    {
        echo "<h3>Test Create Aliment</h3>";
        $data = ['NOM' => 'Apple'];
        $response = $this->makeRequest('POST', $data);

        if ($response['status'] == 201) {
            echo "Create Test Passed<br>";
            echo "Created Aliment ID: " . $response['data']->ID_ALIMENT . "<br>";
            return $response['data']->ID_ALIMENT;
        } else {
            echo "Create Test Failed<br>";
            echo "Response: " . json_encode($response) . "<br>";
            return null;
        }
    }

    private function testGetAliment($idAliment)
    {
        echo "<h3>Test Get Aliment</h3>";
        $response = $this->makeRequest('GET', [], $idAliment);

        if ($response['status'] == 200) {
            echo "Get Test Passed<br>";
            echo "Aliment Data: " . json_encode($response['data']) . "<br>";
        } else {
            echo "Get Test Failed<br>";
            echo "Response: " . json_encode($response) . "<br>";
        }
    }

    private function testUpdateAliment($idAliment)
    {
        echo "<h3>Test Update Aliment</h3>";
        $data = ['NOM' => 'Updated Apple'];
        $response = $this->makeRequest('PUT', $data, $idAliment);

        if ($response['status'] == 200) {
            echo "Update Test Passed<br>";
            echo "Updated Aliment Data: " . json_encode($response['data']) . "<br>";
        } else {
            echo "Update Test Failed<br>";
            echo "Response: " . json_encode($response) . "<br>";
        }
    }

    private function testDeleteAliment($idAliment)
    {
        echo "<h3>Test Delete Aliment</h3>";
        $response = $this->makeRequest('DELETE', [], $idAliment);

        if ($response['status'] == 200) {
            echo "Delete Test Passed<br>";
        } else {
            echo "Delete Test Failed<br>";
            echo "Response: " . json_encode($response) . "<br>";
        }
    }

    private function makeRequest($method, $data = [], $id = null)
    {
        $url = $id ? $this->apiUrl . '?ID_ALIMENT=' . $id : $this->apiUrl;
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
$test = new AlimentApiTest();
$test->runTests();

?>
