* My app model
1. Using API authentication with sanctum.
2. Using user roles from spatie. Available roles are - admin, client
3. Used database tables are users, products and orders for this test.
4. Seeding admin user - admin. Cannot create admin with API in this test model.
5. Seeding sample products
5. Use API CRUD to create products - admin only
6. Clients registrations via API
7. Placing orders based on products created by admins - client only




For API I am using Laravel's built-in cookie based session authentication services. This approach to authentication provides the benefits of CSRF protection, session authentication, as well as protects against leakage of the authentication credentials via XSS. Every API request should need to have

Accept: application/json header and either the Referer or Origin header with your request.

1. To authenticate first make a request to the /sanctum/csrf-cookie endpoint to initialize CSRF protection for the application
2. 


Postman configurations
1. Make request to 
2. Create post response script for that request
// Get the XSRF-TOKEN cookie
var xsrfCookie = pm.cookies.get('XSRF-TOKEN');

// Set Collection Varilable XSRF-TOKEN to access all other request
pm.collectionVariables.set('XSRF-TOKEN', xsrfCookie);

3. Create collection variable called XSRF-TOKEN. set any intial value. Then set empty for current value.
