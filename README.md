For API I am using Laravel's built-in cookie based session authentication services. This approach to authentication provides the benefits of CSRF protection, session authentication, as well as protects against leakage of the authentication credentials via XSS. Every API request should need to have

Accept: application/json header and either the Referer or Origin header with your request.

