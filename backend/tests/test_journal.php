<?php

class JournalApiTest
{
    private $apiUrl = 'http://localhost/iMangerMieux/backend/journal.php';

    public function runTests()
    {
        echo "<h2>Running Journal API Tests</h2>";

        $idJournal = $this->testCreateJournal();
        if ($idJournal) {
            $this->testGetJournal($idJournal);
            $this->testUpdateJournal($idJournal);
            $this->testDeleteJournal($idJournal);
        }
    }

    private function testCreateJournal()
    {
        echo "<h3>Test Create Journal</h3>";
        $data = [
            'ID_UTILISATEUR' => 1,
            'DATE' => '2024-10-01'
        ];
        $response = $this->makeRequest('POST', $data);

        if ($response['status'] == 201) {
            echo "Create Test Passed<br>";
            echo "Created Journal ID: " . $response['data']->ID_JOURNAL . "<br>";
            return $response['data']->ID_JOURNAL;
        } else {
            echo "Create Test Failed<br>";
            echo "Response: " . json_encode($response) . "<br>";
            return null;
        }
    }

    private function testGetJournal($idJournal)
    {
        echo "<h3>Test Get Journal</h3>";
        $response = $this->makeRequest('GET', [], $idJournal);

        if ($response['status'] == 200) {
            echo "Get Test Passed<br>";
            echo "Journal Data: " . json_encode($response['data']) . "<br>";
        } else {
            echo "Get Test Failed<br>";
            echo "Response: " . json_encode($response) . "<br>";
        }
    }

    private function testUpdateJournal($idJournal)
    {
        echo "<h3>Test Update Journal</h3>";
        $data = ['DATE' => '2024-11-01'];
        $response = $this->makeRequest('PUT', $data, $idJournal);

        if ($response['status'] == 200) {
            echo "Update Test Passed<br>";
            echo "Updated Journal Data: " . json_encode($response['data']) . "<br>";
        } else {
            echo "Update Test Failed<br>";
            echo "Response: " . json_encode($response) . "<br>";
        }
    }

    private function testDeleteJournal($idJournal)
    {
        echo "<h3>Test Delete Journal</h3>";
        $response = $this->makeRequest('DELETE', [], $idJournal);

        if ($response['status'] == 200) {
            echo "Delete Test Passed<br>";
        } else {
            echo "Delete Test Failed<br>";
            echo "Response: " . json_encode($response) . "<br>";
        }
    }

    private function makeRequest($method, $data = [], $id = null)
    {
        $url = $id ? $this->apiUrl . '?ID_JOURNAL=' . $id : $this->apiUrl;
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
$test = new JournalApiTest();
$test->runTests();

?>
