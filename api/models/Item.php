<?php

namespace model;

use JsonSerializable;

require_once __DIR__ . '/BaseModel.php';

class Item extends BaseModel implements JsonSerializable
{
    public static function create(string $name, int $xp, int $stocks, float $reduction, float $price, string $image) : Item
    {
        $DB = new \DB();

        $id = $DB->query("INSERT INTO ARTICLE (nom_article, xp_article, stock_article, reduction_article, prix_article, image_article)
                    VALUES (?, ?, ?, ?, ?, ?)", "siiids", [$name, $xp, $stocks, $reduction, $price, $image]);

        return new Item($id);
    }

    public function update(string $name, int $xp, int $stocks, float $reduction, float $price) : Item
    {
        $this->DB->query("UPDATE ARTICLE SET nom_article = ?, xp_article = ?, stock_article = ?, reduction_article = ?, prix_article = ? WHERE id_article = ?", "siiidi", [$name, $xp, $stocks, $reduction, $price, $this->id]);

        return $this;
    }

    public function updateImage(File $image) : Item
    {
        $this->DB->query("UPDATE ARTICLE SET image_article = ? WHERE id_article = ?", "si", [$image->getFileName(), $this->id]);

        return $this;
    }

    public function delete() : void
    {
        $this->DB->query("DELETE FROM ARTICLE WHERE id_article = ?", "i", [$this->id]);
    }

    public static function getInstance($id): ?Item
    {
        $DB = new \DB();
        $result = $DB->select("SELECT * FROM ARTICLE WHERE id_article = ?", "i", [$id]);

        if (count($result) == 0) {
            return null;
        }

        return new Item($id);
    }

    public function jsonSerialize(): array
    {
        $data = $this->DB->select("SELECT * FROM ARTICLE WHERE id_article = ?", "i", [$this->id])[0];

        return $data;
    }

    public static function bulkFetch() : array
    {
        $DB = new \DB();
        return $DB->select("SELECT * FROM ARTICLE");
    }

    public function __toString() : string
    {
        return json_encode($this);
    }
}