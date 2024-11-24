<?php

// Le filter permets de vérifier que les types récupérés sont corrects
// On va aussi les nettoyer pour éviter les injections XSS. Les injections SQL sont gérées par la classe DB
class Filter
{

    private static function deny(string $value, string $attribute) {
        http_response_code(400);
        echo json_encode(["message" => $value . " is not a valid " . $attribute . " in this context"]);
        exit();
    }

    public static function string(string $value, int $minLenght=0, int $maxLenght=5000) : string
    {
        // On retire la "merde" de la chaine
        $filtered = htmlspecialchars($value);

        if (strlen($filtered) < $minLenght || strlen($filtered) > $maxLenght)
        {
            self::deny($value, "string");
        }

        return $filtered;
    }

    public static function email(string $value, int $minLenght=5, int $maxLenght=254) : string | bool
    {
        $filtered = filter_var($value, FILTER_VALIDATE_EMAIL);

        if (!$filtered || strlen($filtered) < $minLenght || strlen($filtered) > $maxLenght)
        {
            self::deny($value, "email");
        }

        return $filtered;
    }

    public static function int(string $value, int $min=0, int $max=PHP_INT_MAX) : int | bool
    {
        $filtered = filter_var($value, FILTER_VALIDATE_INT);

        if (!$filtered || $filtered < $min || $filtered > $max)
        {
            self::deny($value, "int");
        }

        return $filtered;
    }

    public static function float(string $value, int $min=0, int $max=PHP_INT_MAX) : float | bool
    {
        $filtered = filter_var($value, FILTER_VALIDATE_FLOAT, ['options' => [
                'min_range' => $min,
                'max_range' => $max
            ]
        ]);

        if (!$filtered)
        {
            self::deny($value, "float");
        }

        return $filtered;
    }

    public static function bool(string $value) : bool
    {
        $filtered = filter_var($value, FILTER_VALIDATE_BOOLEAN);

        if ($filtered === null)
        {
            self::deny($value, "bool");
        }

        return $filtered;
    }

    public static function date(string $value) : string
    {
       $exp = '/^(\d{4})-(\d{2})-(\d{2})$/';

        if (!preg_match($exp, $value))
        {
            self::deny($value, "date");
        }

        return $value;
    }
}