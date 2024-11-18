<?php

class tools
{
    public static function generateUUID(){
        $data = random_bytes(16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return bin2hex($data);
    }

    public static function saveFile()
    {
        // Retourne le nom du fichier si l'enregistrement a réussi, faux sinon.

        $name = self::generateUUID() . '.' . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        if (move_uploaded_file($_FILES['file']['tmp_name'], 'files/' . $name)) {
            return $name;
        }

        return false;
    }

    public static function saveImage()
    {
        // Vérification des données de l'image, puis enregistrement.
        // Retourne Faux si l'image n'en est pas une, ou si elle n'a pas pu être enregistrée.

        if ($_FILES['image']['tmp_name'] === '') {
            return false;
        }

        if (getimagesize($_FILES['image']['tmp_name']) === false && !in_array($_FILES['image']['type'], ['image/jpeg', 'image/png', 'image/webp'])) {
            return false;
        }

        // On s'assure que l'extension du fichier ne causerait pas de problèmes
        return DB::clean(self::saveFile());
    }


    public static function deleteFile($fileName)
    {

        if (file_exists('files/' . $fileName)) {
            unlink('files/' . $fileName);
            return true;
        }

        return false;
    }
}
