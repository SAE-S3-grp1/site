<?php
function connectDatabase($host, $username, $password, $database) {
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Échec de la connexion : " . $conn->connect_error);
    }
    return $conn;
}

// Fonction pour exécuter une requête SELECT
function executeSelectQuery($conn, $query, $params = []) {
    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $types = str_repeat("s", count($params)); // Assume string types for simplicity
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC); // Retourne un tableau associatif
}

// Fonction pour exécuter une requête INSERT/UPDATE/DELETE
function executeModifyQuery($conn, $query, $params = []) {
    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $types = str_repeat("s", count($params));
        $stmt->bind_param($types, ...$params);
    }
    return $stmt->execute();
}

$conn = connectDatabase(
    "lithium.voltis.cloud",
    "u101_saQxwWi2aa",
    "L+glq7vxzK=.p9HMFwMQNssu",
    "s101_bdeTest"
);

?>