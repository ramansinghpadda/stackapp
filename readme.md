# Stackrapp

Add docs for setting up

Need to have php, mysql and a database client is helpful.

The .env file in the root of this folder should be changed to reflect your localhost settings.

ALTER USER 'root'@'localhost' IDENTIFIED BY 'stackr123'; to change the mysql password so that is matches tha changes in the .env file.


DB Migration and Seeding 

##php artisan migrate:install
##php artisan migrate

This command is created to seed data into laratrust tables. 
##php artisan setup:access-control

Create super admin 
##php artisan new:superadmin

Install SEO tools: https://github.com/artesaos/seotools
##composer require artesaos/seotools
##php artisan vendor:publish --provider="Artesaos\SEOTools\Providers\SEOToolsServiceProvider"

##Install Group permission
php artisan db:seed --class=GroupPermissionSeeder