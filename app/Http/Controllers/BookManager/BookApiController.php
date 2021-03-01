<?php

namespace App\Http\Controllers\BookManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book; //Model for this controller
use App\Models\UserActionLogs; //Model for this controller
use App\Http\Requests\BookRequest; //This file contains adding book validation and sanitization logic
use App\Http\Requests\UserAction; //This file contains user action validation and sanitization logic
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BookManager\BookService;
use App\Http\Controllers\BookManager\UserActionService;

class BookApiController extends Controller
{
    public function addBookToLibrary(BookRequest $request, BookService $service) {
        $inputData = $request->validated();
        if(!$service->isISBNValid($inputData['isbn'])) {
            return response()->json(["message"=>"The given data was invalid.","errors"=>["isbn"=>["Invalid ISBN-10."]]],'422');
        }
        $bookExists = Book::where('isbn', $inputData['isbn'])->first();
        if(!empty($bookExists)) {
            return response()->json(["message"=>"The ISBN is associated with other book."],'200');
        }
        $bookData = $service->addBook($inputData);
        return response()->json(["message"=>"Book added to the library successfully.","data"=>$bookData],'200');
    }
    public function getBooks(Request $request, BookService $service) {
        if($request->has('status')) {
            $inputData = $request->only('status');
            $status = strtoupper($inputData['status']);
           if($status!="AVAILABLE" && $status!="CHECKED_OUT") {
                return response()->json(["message"=>"The given data was invalid.","errors"=>["status"=>["Status should be AVAILABLE or CHECKED_OUT."]]],'422');
           }
           $queryData['status'] = $status;
           $books = $service->getBooks($queryData);
        } else {
            $books = $service->getBooks();
        }
        if($books->isEmpty()) {
            return response()->json(["message"=>"No matching record(s) found."],'200');
        }
        return response()->json(["message"=>$books->count(). " matching record(s) found.","data"=>$books],'200');
    }

    public function getBookDetails($bookId, BookService $service) {
        if(empty($bookId) || !is_numeric($bookId)) {
            return response()->json(["message"=>"The given data was invalid.","errors"=>["bookId"=>["Invalid Book Id"]]],'422');
        }
        $queryData['id'] = $bookId;
        $books = $service->getBooks($queryData);
        if($books->isEmpty()) {
            return response()->json(["message"=>"No matching record found"],'200');
        }
        return response()->json(["message"=>$books->count(). " matching record found","data"=>$books],'200');
    }
    public function getUserCheckedOutBooks(BookService $service) {
        $books = $service->getUserCheckedOutBooks();
        if($books->isEmpty()) {
            return response()->json(["message"=>"No matching record(s) found"],'200');
        }
        return response()->json(["message"=>$books->count(). " matching record(s) found","data"=>$books],'200');
    }

}
