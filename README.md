# Quard Intel Test
Here I am using Sanctum tokens to authenticate requests to the API. Should pass the token in the Authorization header as a Bearer token.

Check ```./Documentation``` folder for API documenation created with API including all postman requests.

## Application
1. Using API authentication with sanctum.
2. Using user roles from spatie. Available roles are admin and client.
3. Used database tables are users, products and orders for this test. (Check documentation folder in this repository for database diagram)
4. Seeding admin user with admin privileges.
5. Use API CRUD for products - admin only
6. Clients registrations via API
7. Placing orders based on products created by admins - client only
8. Add Caching for when retreiving all products from database.
9. Add database indexing for products table to improve product searching.

## How to setup for local environment to test this app.
Clone repository
```bash
 git clone https://github.com/kinsly/quad-test.git
```
Install packages
```bash
cd quad-test
composer install
```
Configure .env file
* Rename .env-example file to .env
* Generate new app key
```bash
mv .env.example .env
php artisan key:generate
```
* Add your mysql database credential to .env file. (Leave as it is to create default SQLite database)
* Make sure app urls are correct in .env file for sanctum to work correctly. For localhost:8000 use below configuration
```
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:3000
```
Create tables
```bash
php artisan migrate
```
Insert default data to database including Admin user and Admin user.

```bash
php artisan db:seed
```
Initiate local server
```bash
php artisan serve
```
## How to use - Postman Setup
* Download and install latest version of postman.
[Download Postman](https://www.postman.com/downloads/)
* Open recently installed postman app and select "import" button on the home screen.
* Import below postman json file
```./Documentation/Quad Intel Test.postman_collection.json```
* Run ```Admin Login``` to login as a Admin
* Go to ```Products - CRUD``` collection and run ```Create Product``` request to create a new product item.
* Go to ```User-client``` collection and run ```Client Register``` 
* Go to ```Orders - Client``` collection and run ```Place Order``` request to place new order to admin created product.

## Tests (PHP Unit Tests)
1. Go to ./tests/Feature folder to view all available test files.
2. Tests under Auth folder contain default Unit tests created for authentication.
3. OrderAPITest and ProductAPITest used to test business logic.
4. Run below artisan command to run test.
```bash
php artisan test
```

### Caching
We can use caching backends like Memcached, Redis, DynamoDB to reduce database load and to improve API response time. Here we are usig  Memcached PECL package. set following configuration config/cache.php. If not leave as it is to use laravel default caching mechanism. 

```php
'memcached' => [
    // ...
 
    'servers' => [
        [
            'host' => env('MEMCACHED_HOST', '127.0.0.1'),
            'port' => env('MEMCACHED_PORT', 11211),
            'weight' => 100,
        ],
    ],
],
```

Check ProductsController.php file for caching example used. 
1. All products data is prementantly cached.
2. Invalidate cache whenever admin create, update or delete products.

### Optimizing database queries for performance - Indexing

Used laravel indexing feature for products table. Add unique index for "name" column of the products table to improve searching. Check

```database/migrations/2024_08_21_111437_update_products_table_indexes```

Please note: Product search api is not implements as it is not necessary. 



Postman configurations
1. check documentation at documenation folder.