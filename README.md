
# Library Management System

The application provides a basic library management service with user authentication. Currently, the application supports adding books to the library, listing the available/checked out books, check-in in/checkout functionality. The user Activity log is also available in the application. 

API documentation can be accessed [here](https://documenter.getpostman.com/view/14768038/TWDdkaDb).

## Built With

* PHP 7.4
* Nginx 1.4.6
* MySql 5.1
* Laravel 8.16.1
* PHPUnit 
* Postman for API testing and documentation
* Arch Linux

## Source Files Description
1. app/Controllers/AuthManager - This folder contains controllers to handle user authentication and new account creation.
2. app/Controllers/BookManager - This folder contains controllers to handle book management operations such as adding a new book and getting book details.
3. app/Controllers/UserActionManager - This folder contains controllers to handle user actions such as book checking out/checking in and retrieving user action logs.
5. app/Models- This directory contains various models needed for the application.
6. app/Requests - This directory contains various request validators. It provides the required validation and sanitizations logic for different operations performed by the application.
7. The different views used in the application can be found in the resources folder. 
8. Database-related files (migrations, factories, database seeding) can be found in the database folder.
9. Unit tests for various operations can be found in the Tests/Feature folder ("php artisan test" command can be used to quickly run the tests.).
