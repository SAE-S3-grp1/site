<?php
namespace model;

use JsonSerializable;

require_once __DIR__ . '/BaseModel.php';


class Member extends BaseModel implements JsonSerializable
{
    public function delete() : void
    {
        // /** @lang SQL */ permet d'afficher sur PHPStorm la coloration syntaxique SQL
        $this->DB->query(/** @lang SQL */"CALL suppressionCompte(?)", "i", [$this->id]);
    }

    // TODO: Create an Image type ($pp)
    public function update(string $nom, string $prenom, string $email, string $tp, int $xp) : Member
    {
        $this->DB->query("UPDATE MEMBRE SET nom_membre = ?, prenom_membre = ?, email_membre = ?, tp_membre = ?, xp_membre = ? WHERE id_membre = ?", "ssssii", [$nom, $prenom, $email, $tp, $xp, $this->id]);

        return $this;
    }

    public function updateProfilePic(File $pp)
    {
        $this->DB->query("UPDATE MEMBRE SET pp_membre = ? WHERE id_membre = ?", "si", [$pp->getFileName(), $this->id]);

        return $this;
    }

    public static function create(string $nom, string $prenom, string $email, File | null $pp, string $tp) : Member
    {
        $DB = new \DB();

        $id = $DB->query("INSERT INTO MEMBRE (nom_membre, prenom_membre, email_membre, pp_membre, tp_membre) VALUES (?,?,?,?,?)", "sssss", [$nom, $prenom, $email, $pp, $tp]);

        return new Member($id);
    }

    public function toJson(): array
    {
        $result = $this->DB->select("SELECT id_membre, nom_membre, prenom_membre, email_membre, xp_membre, discord_token_membre, pp_membre, tp_membre, (SELECT COUNT(*) FROM ASSIGNATION WHERE MEMBRE.id_membre = ASSIGNATION.id_membre) as nb_roles
                                      FROM MEMBRE WHERE id_membre = ?", "i", [$this->id]);

        return $result[0];
    }

    public static function getInstance($id) : ?Member
    {
        $DB = new \DB();
        $result = $DB->select("SELECT * FROM MEMBRE WHERE id_membre = ?", "i", [$id]);

        if (count($result) == 0) {
            return null;
        }

        return new Member($id);
    }


    public static function bulkFetch(): array
    {
        // Retourne un tableau de tous les membres au format JSON, et non sous forme d'objet
        // Les roles ne sont pas inclus non plus.
        // Il faut utiliser la méthode fetch() pour obtenir l'objet membre, ainsi que les roles

        $DB = new \DB();
        $result = $DB->select("SELECT * FROM MEMBRE");

        return $result;
    }

    /**
     * @return Role[]
    **/
    public function getRoles(): array
    {
        $result = $this->DB->select("SELECT id_role
                               FROM ASSIGNATION
                               WHERE ASSIGNATION.id_membre = ?", "i", [$this->id]);

        $roles = [];
        foreach ($result as $role) {
            $roles[] = Role::getInstance($role["id_role"]);
        }

        return $roles;
    }

    public function toJsonWithRoles() : array
    {
        $data =  $this->toJson();

        // Pour chaque role, on applique la méthode get() pour obtenir le role sous forme de json
        $data["roles"] = [];

        foreach ($this->getRoles() as $role) {
            $data["roles"][] = $role->toJson();
        }


        return $data;
    }

    public function __toString()
    {
        return json_encode($this->toJsonWithRoles());
    }

    public function jsonSerialize(): array
    {
        return $this->toJsonWithRoles();
    }
}



