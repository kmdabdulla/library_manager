<?php

namespace App\Http\Controllers\BookManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book; //Model for this controller
use App\Models\UserActionLogs; //Model for this controller
use App\Http\Requests\BookRequest; //This file contains request validation and sanitization logic
use App\Http\Requests\UserAction; //This file contains user action validation and sanitization logic
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BookManager\BookService;
use App\Http\Controllers\BookManager\UserActionService;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); //check if user is authenticated to access this class methods.
    }

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
    public function getAvailableBooks(BookService $service) {
        $queryData['status'] = "AVAILABLE";
        $books = $service->getBooks($queryData);
        $data['books'] = $books;
        $data['listType'] = 'available';
        return view('listBooks')->with('data', $data);
    }

    public function getUserCheckedOutBooks(BookService $service) {
        $books = $service->getUserCheckedOutBooks();
        $data['books'] = $books;
        $data['listType'] = 'checkedOut';
        return view('listBooks')->with('data', $data);
    }




}
