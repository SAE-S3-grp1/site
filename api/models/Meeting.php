<?php

namespace model;

use JsonSerializable;

require_once __DIR__ . '/BaseModel.php';

class Meeting extends BaseModel implements JsonSerializable
{

    public function delete() : void
    {
        $file = $this->getFile();
        $file->deleteFile();
        $this->DB->query("DELETE FROM REUNION WHERE id_reunion = ?", "i", [$this->id]);
    }

    public function getFile() : File
    {
        $data = $this->DB->select("SELECT fichier_reunion
                                 FROM REUNION
                                 WHERE id_reunion = ?", "i", [$this->id])[0];

        return new File($data['fichier_reunion']);
    }

    public function updateFile(File $file) : Meeting
    {
        $this->DB->query("UPDATE REUNION SET fichier_reunion = ? WHERE id_reunion = ?", "si", [$file->getFileName(), $this->id]);

        return $this;
    }

    public function getUser() : Member
    {
        $data = $this->DB->select("SELECT id_membre
                                 FROM REUNION
                                 WHERE id_reunion = ?", "i", [$this->id])[0];

        return new Member($data['id_membre']);
    }

    public static function create(string $date, File $file, Member $member) : Meeting
    {
        $DB = new \DB();

        $id = $DB->query("INSERT INTO REUNION (date_reunion, fichier_reunion, id_membre)
                    VALUES (?, ?, ?)", "ssi", [$date, $file->getFileName(), $member->getId()]);

        return new Meeting($id);
    }

    public static function getInstance($id): ?Meeting
    {
        $DB = new \DB();
        $result = $DB->select("SELECT * FROM REUNION WHERE id_reunion = ?", "i", [$id]);

        if (count($result) == 0) {
            return null;
        }

        return new Meeting($id);
    }


    public function jsonSerialize(): array
    {
        $data = $this->DB->select("SELECT id_reunion, date_reunion, fichier_reunion
                                 FROM REUNION
                                 WHERE id_reunion = ?", "i", [$this->id])[0];

        $data['user'] = $this->getUser();

        return $data;
    }

    public static function bulkFetch() : array
    {
        $DB = new \DB();
        return $DB->select("SELECT * FROM REUNION");
    }

    public function __toString() : string
    {
        return json_encode($this);
    }

}