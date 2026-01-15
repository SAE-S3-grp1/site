# Setup

## Git pull

```bash
cd /var/www/html
sudo git pull https://github.com/SAE-S3-grp1/site.git .
```

## DB setup

```bash
cd /var/www/html
mysql -u etu -psae32024 sae < ./script.sql
# ou avec un prompt interactif : 
# mysql -u etu -p -D sae < ./script.sql
```

## Ownership

www-data doit AU MINIMUM avoir le dossier api/files.
```bash
cd /var/www/html
sudo chmown -R www-data html
```

# infos
Voici les logins des utilisateurs par défaut :

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

