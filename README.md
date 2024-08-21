* My app model
1. Using API authentication with sanctum.
2. Using user roles from spatie. Available roles are - admin, client
3. Used database tables are users, products and orders for this test. (Check documentation folder in this repository for database diagram)
4. Seeding admin user - admin.Cannot create admin with API in this test model.
5. Use API CRUD for products - admin only
6. Clients registrations via API
7. Placing orders based on products created by admins - client only

** How to setup for local environment to test this app.
1. Git clone https://github.com/kinsly/quad-test.git
2. composer install
3. rename env.example file as .env
4. Run php artisan key:generate to generate new app key.
5. Add mysql database credential to .env file
6. Run "php artisan migrate" to create tables on database.
7. Run "php artisan db:seed" to add default data to database including admin user and user roles.
8. Run "php artisan server" to run localhost server.

*** Tests (PHP Unit Tests)
1. Go to ./tests/Feature folder for all available test files.
2. Tests under Auth folder contain default Unit tests created for authentication.
3. OrderAPITest and ProductAPITest used to test business logic.
4. Run "php artisan test" to run all available tests.

* About

For API I am using Laravel's built-in cookie based session authentication services. This approach to authentication provides the benefits of CSRF protection, session authentication, as well as protects against leakage of the authentication credentials via XSS. Every API request should need to have

" Accept: application/json header, XSRF-TOKEN and Origin header."
It is required to make a request to the /sanctum/csrf-cookie endpoint to initialize CSRF protection for the application

** Axios Configuration
Should enable the withCredentials and withXSRFToken options on application's global axios instance. 
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

Postman configurations
1. check documentation at documenation folder.