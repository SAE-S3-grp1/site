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

