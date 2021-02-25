
# Library Management System

The application provides basic library management service. Basic user authentication is provided. Currently, the application supports adding books to library, listing the available/borrowed books, borrow/return functionality. User Activity log is also shown. 

Application can be accessed [here](https://www.books.mohamedabdulla.com)

## Built With

* PHP 7.4
* Nginx 1.4.6
* MySql 5.1
* Laravel 8.16.1
* Arch Linux

## Source Files Description
1. app/Controllers/LoginController.php - handles user authentication and new account creation.
2. app/Models/User.php - Provides the model for LoginController.
3. app/Requests/UserLogin.php - Provides the required validation and sanitizations for methods declared in LoginController.php
4. app/Controllers/BookController.php - handles book management operaions such as add, borrow, return and listing etc.
5. app/Models/Book.php - Provides the model for BookController.
6. app/Requests/BookRequest.php - Provides the required validation and sanitizations for methods declared in BookController.php
7. All the views for the application can be found resources folder. 
8. All the database related files can be found in database folder.


## Author
Mohamed Abdulla

LinkedIn: [www.linkedin.com/in/kmdabdulla](https://www.linkedin.com/in/kmdabdulla)  
Website: [www.mohamedabdulla.com](https://www.mohamedabdulla.com)

## Acknowledgments
* Laravel Support Forums
* Stack OverFlow Community
