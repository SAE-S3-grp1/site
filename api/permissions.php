<?php
include_once 'DB.php';


function hasPermission($userid)
{
    $permColumnName = [
        "Acces aux logs" => "logs",
        "Gestion de la boutique" => "boutique",
        "Gestion des reunions" => "reunions",
        "Gestion des utilisateurs" => "utilisateur",
        "Gestion des grades" => "grades",
        "Gestion des roles" => "roles",
        "Gestion des actualites" => "actualites",
        "Gestion des evenements" => "evenements",
        "Gestion de la comptabilite" => "comptabilite",
        "Acces aux achats" => "achats",
        "Moderation" => "moderation",
    ];

    $DB = new DB();

    $fetchedPermissions = $DB->select("SELECT `Acces aux logs`, `Gestion de la boutique`, `Gestion des reunions`, `Gestion des utilisateurs`, `Gestion des grades`, `Gestion des roles`, `Gestion des actualites`, `Gestion des evenements`, `Gestion de la comptabilite`, `Acces aux achats`, Moderation
                                FROM LISTE_PERMISSIONS
                                WHERE id_membre = ?", "i", [$userid]);
    $fetchedPermissions = $fetchedPermissions[0];

    $userPerms = [];
    $hasAtLeastOnePerm = false;

    // On récupère les permissions de l'utilisateur
    // Et on les enregistre dans $userPerms
    foreach (array_keys($fetchedPermissions) as $perm) {
        $simplePermissionName = $permColumnName[$perm];
        $permissionValue = $fetchedPermissions[$perm];

        $userPerms[$simplePermissionName] = $permissionValue;
        if ($permissionValue == 1) {
            $hasAtLeastOnePerm = true;
        }
    }

    // Si l'utilisateur a au moins une permission, on lui donne la permission d'
    $perm

    return $userPerms;

}

echo json_encode(hasPermission(1));
