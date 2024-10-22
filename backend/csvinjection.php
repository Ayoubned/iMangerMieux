<?php
// Include the PDO initialization script
require_once('init_pdo.php');

// CSV file path

$csvFile = 'sql\aliments.csv';
$csvFile2 = 'sql\users.csv';
try {
    // Open the CSV file
    if (($handle = fopen($csvFile, 'r')) !== false) {
        
        // Get the first row, which contains the column headers
        $headers = fgetcsv($handle, 1000, ",");

        // Find the indexes of the relevant columns
        $alimentNameIndex = array_search('alim_nom_fr', $headers); // Find index for aliment name

        // Array mapping CSV headers to type_ratio labels (as per database structure)
        $ratios = [
            'Energie (kj/100g)' => array_search('Energie, Règlement UE N° 1169/2011 (kJ/100 g)', $headers),
            'Energie (kcal/100g)' => array_search('Energie, Règlement UE N° 1169/2011 (kcal/100 g)', $headers),
            'Eau (g/100g)' => array_search('Eau (g/100 g)', $headers),
            'Lipides (g/100g)' => array_search('Lipides (g/100 g)', $headers),
            'Glucides (g/100g)' => array_search('Glucides (g/100 g)', $headers),
            'Sucres (g/100g)' => array_search('Sucres (g/100 g)', $headers),
            'Protéines (g/100g)' => array_search('Protéines, N x facteur de Jones (g/100 g)', $headers),
            'Fibres alimentaires (g/100g)' => array_search('Fibres alimentaires (g/100 g)', $headers),
            'Cholestérol (mg/100g)' => array_search('Cholestérol (mg/100 g)', $headers),
            'Calcium (mg/100g)' => array_search('Calcium (mg/100 g)', $headers),
            'Fer (mg/100g)' => array_search('Fer (mg/100 g)', $headers),
            'Potassium (mg/100g)' => array_search('Potassium (mg/100 g)', $headers),
            'Zinc (mg/100g)' => array_search('Zinc (mg/100 g)', $headers),
            'Magnésium (mg/100g)' => array_search('Magnésium (mg/100 g)', $headers),
            'Vitamine C (mg/100g)' => array_search('Vitamine C (mg/100 g)', $headers),
            'Vitamine D (µg/100g)' => array_search('Vitamine D (µg/100 g)', $headers),
            'Vitamine E (mg/100g)' => array_search('Vitamine E (mg/100 g)', $headers),
        ];

        // Loop through each row of the CSV
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            $alimentName = $data[$alimentNameIndex];
            // Insert the aliment (if not exists) and get the aliment ID
            $stmt = $pdo->prepare("INSERT INTO aliment (NOM) VALUES (:nom) ON DUPLICATE KEY UPDATE ID_ALIMENT=LAST_INSERT_ID(ID_ALIMENT)");
            $stmt->execute(['nom' => $alimentName]);
            $alimentId = $pdo->lastInsertId();

            // Loop through each ratio and insert it into `contient`
            foreach ($ratios as $ratioName => $columnIndex) {
                $ratioValue = isset($data[$columnIndex]) ? trim($data[$columnIndex]) : null;
                if (strpos($ratioValue, '<') !== false) {
                    $ratioValue = preg_replace('/<\s*/', '', $ratioValue); // Remove the "<" symbol
                }
                $ratioValue = str_replace(',', '.', $ratioValue);
                // Check for '-' which should be treated as 0
                if ($ratioValue === 'traces') {
                    $ratioValue = 0.005;
                }
                if ($ratioValue === '-' || $ratioValue === '') {
                    $ratioValue = 0;
                }
            
                // Validate that the ratio value is numeric or now is 0
                if ($ratioValue !== null && (is_numeric($ratioValue) || $ratioValue === 0)) {
                    $ratioValue = floatval($ratioValue); // Convert to float
            
                    // Get the ID_TR from the type_ratio table
                    $stmt = $pdo->prepare("SELECT ID_TR FROM type_ratio WHERE LAB = :lab");
                    $stmt->execute(['lab' => $ratioName]);
                    $typeRatio = $stmt->fetch(PDO::FETCH_OBJ);
            
                    if ($typeRatio) {
                        // Insert the ratio value into the contient table
                        $stmt = $pdo->prepare("INSERT INTO contient (ID_ALIMENT, ID_TR, VALEUR_RATIO) VALUES (:id_aliment, :id_tr, :valeur_ratio)");
                        $stmt->execute([
                            'id_aliment' => $alimentId,
                            'id_tr' => $typeRatio->ID_TR,
                            'valeur_ratio' => $ratioValue
                        ]);
                    }
                } else {
                    // Log or skip non-numeric values .
                    echo "Invalid ratio value for $ratioName: $ratioValue\n";
                }
            }
        }

        // Close the CSV file
        fclose($handle);

        echo "CSV data successfully inserted into the database.";
        
    } else {
        echo "Failed to open the CSV file.";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
try {

// Open the CSV file for reading
if (($handle = fopen($csvFile2, "r")) !== FALSE) {
    // Skip the header row
    fgetcsv($handle, 1000, ",");

    // Prepare the SQL statement with placeholders
    $sql = "INSERT INTO utilisateur (
        id_age,id_sexe,id_ns,username,password
    ) VALUES (
        ?, ?, ?, ?, ?
    )";

    // Prepare the statement
    $stmt = $pdo->prepare($sql);

    // Loop through the CSV file and insert into the database
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        // Bind values from CSV to SQL query
        $stmt->execute([
            $data[0], $data[1], $data[2], $data[3], $data[4]
        ]);
    }

    // Close the file
    fclose($handle);

    echo "Data imported successfully!";
}

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
