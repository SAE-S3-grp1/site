<?php

namespace model;

require_once __DIR__ . '/BaseModel.php';

use finfo;
use JsonSerializable;
use tools;

class File implements JsonSerializable
{
    private string $fileName;

    public function getFileName(): string
    {
        return $this->fileName;
    }


    private function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    public static function getFile(string | null $fileName): File | null
    {
        if (!is_null($fileName) && file_exists('files/' . $fileName)) {
            return new File($fileName);
        }

        return null;
    }



    public static function saveFile() : File | null
    {
        // Retourne le nom du fichier si l'enregistrement a réussi, faux sinon.

        $name = tools::generateUUID() . '.' . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        if (move_uploaded_file($_FILES['file']['tmp_name'], 'files/' . $name)) {
            return new File($name);
        }

        return null;
    }

    public static function saveImage() : File | null
    {
        // Vérification des données de l'image, puis enregistrement.
        // Retourne Faux si l'image n'en est pas une, ou si elle n'a pas pu être enregistrée.

        if (!isset($_FILES['file']) || $_FILES['file']['tmp_name'] === '') {
            return null;
        }

        // Vérifie le type MIME avec finfo
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($_FILES['file']['tmp_name']);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($mimeType, $allowedTypes)) {
            return null;
        }

        // On s'assure que l'extension du fichier ne causerait pas de problèmes
        return self::saveFile();
    }


    public function deleteFile() : bool
    {
            if (file_exists('files/' . $this->fileName)) {
                unlink('files/' . $this->fileName);
                return true;
            }

            return false;
    }

    public function __toString() : string
    {
        return $this->fileName;
    }


    public function jsonSerialize(): string
    {
        return $this->fileName;
    }
}