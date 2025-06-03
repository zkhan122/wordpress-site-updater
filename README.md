# wordpress-site-updater
a script to automatically update plugins and site version of wordpress sites

first, install WP-CLI: wp-cli.org

** Note: currently only works on unix based systems (ubuntu/mac os)

to run (include sudo command if needed):

```php wordpress-plugin-site-updater.php /DEVEL_WEB_STORAGE_UBUNTU/{username}/{sitename}```

```e.g. php wordpress-plugin-site-updater.php /DEVEL_WEB_STORAGE_UBUNTU/{username}/omidb```

cron jobs works locally, being deployed globally