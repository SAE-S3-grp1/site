<?php

function connectDatabase($host, $username, $password, $database) {
    $db = new mysqli($host, $username, $password, $database);
    if ($db->connect_error) {
        die("Échec de la connexion : " . $db->connect_error);
    }
    return $db;
}

// Fonction pour exécuter une requête SELECT
function executeSelectQuery($db, $query, $params = []) {
    $stmt = $db->prepare($query);
    if (!empty($params)) {
        $types = str_repeat("s", count($params)); // Assume string types for simplicity
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC); // Retourne un tableau associatif
}

// Fonction pour exécuter une requête INSERT/UPDATE/DELETE
function executeModifyQuery($db, $query, $params = []) {
    $stmt = $db->prepare($query);
    if (!empty($params)) {
        $types = str_repeat("s", count($params));
        $stmt->bind_param($types, ...$params);
    }
    return $stmt->execute();
}

function printSelectQuery($query_response) {
    if (empty($query_response)) {
        echo "Aucun résultat trouvé.";
        return;
    }

    // Début du tableau HTML
    echo "<table border='1' cellpadding='5' cellspacing='0'>";

    // Afficher les noms des colonnes (en utilisant les clés du premier élément)
    echo "<tr>";
    foreach (array_keys($query_response[0]) as $col_name) {
        echo "<th>" . htmlspecialchars($col_name) . "</th>";
    }
    echo "</tr>";

    // Afficher les lignes de résultats
    foreach ($query_response as $row) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>";
        }
        echo "</tr>";
    }

    // Fin du tableau HTML
    echo "</table>";
}


$db = connectDatabase(
    "lithium.voltis.cloud",
    "u101_saQxwWi2aa",
    "L+glq7vxzK=.p9HMFwMQNssu",
    "s101_bdeTest"
);

#printSelectQuery(executeSelectQuery($db, "SELECT * FROM MEMBRE;"));


?>
