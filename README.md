# Setup

## Droits
```bash
# ajout de l'utilisateur actuel au groupe www-data pour avoir les droits sur le dossier /var/www/html
sudo usermod -a -G www-data "$USER"
```

## Git clone

```bash
cd /var/www/html
rm index.html
git clone https://github.com/SAE-S3-grp1/site.git .
```

## DB setup

```bash
cd /var/www/html
# mot de passe avec prompt interactif
mysql -u etu -p -D sae < ./script.sql

# ou avec le mot de passe directement (-p[motdepasse]) :
# mysql -u etu -psae32024 sae < ./script.sql
```

## Ownership

www-data doit AU MINIMUM posséder le dossier api/files.
```bash
cd /var/www/html
sudo chown -R www-data .
sudo chgrp -R www-data .
```

## Configuration

Afin de lier la DB au code source, il faut éditer les fichiers :
- `api/DB.php`
- `database.php`

Et modifier les champs comme ci-dessous.
```php
class DB
{
    private $host = 'localhost';
    private $port = '3306';
    private $db = 'sae'; // <- ici
    private $db_user = 'etu'; // <- ici
    private $db_pass = '[VOTRE_MDP]'; // <- ici
```

# infos db
## Logins des utilisateurs par défaut 

| email | mot_de_passe |
|---|---|
| gemino.ruffault@example.com | password1 |
| axelle.hannier@example.com | password1 |
| julien.dauvergne@example.com | password1 |
| baptiste.delahay@example.com | password1 |
| nathalie.vieillard@example.com | password1 |
| barnabe.havard@example.com | password1 |
| theo.fevrier@example.com | password1 |
| tom.gouin@example.com | password1 |
| evann.congnard@example.com | password1 |
| erwan.lecoz@example.com | password1 |

## Roles

## Attribution 

| id_membre | nom | prenom | role |
|----------:|--------------------|---------|-------------------|
| 1 | RUFFAULT--RAVENEL | Gemino   | Administrateur |
| 2 | HANNIER            | Axelle   | Membre |
| 3 | DAUVERGNE          | Julien   | Bureau |
| 4 | DELAYE             | Baptiste | Modérateur |
| 5 | VIEILLARD          | Nathalie | Responsable com |
| 6 | HAVARD             | Barnabe  | Bureau |
| 7 | FEVRIER            | Theo     | Membre |
| 8 | GOUIN              | Tom      | Membre |
| 9 | CONGNARD           | Evann    | Modérateur |
|10 | LE COZ             | Erwan    | Membre |

## Permissions

| nom_role        | p_log | p_boutique | p_reunion | p_utilisateur | p_grade | p_roles | p_actualite | p_evenements | p_comptabilite | p_achats | p_moderation |
|-----------------|-------|------------|-----------|---------------|---------|---------|-------------|--------------|----------------|----------|--------------|
| Administrateur  | 1     | 1          | 1         | 1             | 1       | 1       | 1           | 1            | 1              | 1        | 1            |
| Bureau          | 1     | 1          | 1         | 0             | 0       | 0       | 1           | 1            | 1              | 1        | 0            |
| Responsable com | 0     | 0          | 0         | 0             | 0       | 0       | 0           | 0            | 1              | 1        | 0            |
| Modérateur      | 0     | 0          | 0         | 1             | 0       | 0       | 1           | 0            | 0              | 0        | 1            |
| Membre          | 0     | 0          | 0         | 0             | 0       | 0       | 0           | 0            | 0              | 0        | 0            |
