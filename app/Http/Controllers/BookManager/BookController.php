<?php

namespace App\Http\Controllers\BookManager;

use App\Http\Controllers\Controller;
use App\Models\Book; //Model for this controller
use App\Http\Requests\BookRequest; //This file contains request validation and sanitization logic for Book management related requests
use App\Http\Controllers\BookManager\BookService;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); //check if user is authenticated to access this class methods.
    }
     /**
     * Handles adding book to database. Performs required validation and returns relevant messages.
     * On success, returns success message to the user.
     */
    public function addBookToLibrary(BookRequest $request, BookService $service) {
        $inputData = $request->validated();
        if(!$service->isISBNValid($inputData['isbn'])) {
            return redirect()->back()->withErrors('Invalid ISBN-10.');
        }
        $bookExists = Book::where('isbn', $inputData['isbn'])->first();
        if(!empty($bookExists)) {
            return redirect()->back()->withErrors('The ISBN is associated with other book.');
        }
        $service->addBook($inputData);
        return redirect()->back()->with('success','Book added to the library successfully.');
    }
     /**
     * Returns available books to the user.
     */
    public function getAvailableBooks(BookService $service) {
        $queryData['status'] = "AVAILABLE";
        $books = $service->getBooks($queryData);
        $data['books'] = $books;
        $data['listType'] = 'available';
        return view('listBooks')->with('data', $data);
    }
     /**
     * Returns user checked out books.
     */
    public function getUserCheckedOutBooks(BookService $service) {
        $books = $service->getUserCheckedOutBooks();
        $data['books'] = $books;
        $data['listType'] = 'checkedOut';
        return view('listBooks')->with('data', $data);
    }




}
