<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book; //Model for this controller
use App\Models\UserActionLogs; //Model for this controller
use App\Http\Requests\BookRequest; //This file contains request validation and sanitization logic
use App\Http\Requests\UserAction; //This file contains user action validation and sanitization logic
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); //check if user is authenticated to access this class methods.
    }

    public function addBookToLibrary(BookRequest $request) {
        $inputData = $request->validated();
        if(!$this->isISBNValid($inputData['isbn'])) {
            return redirect()->back()->withErrors('Invalid ISBN-10');
        }
        $bookExists = Book::where('isbn', $inputData['isbn'])->first();
        if(!empty($bookExists)) {
            return redirect()->back()->withErrors('Book already exists');
        }
        $bookData = new Book;
        $bookData->title = $inputData['title'];
        $bookData->isbn = $inputData['isbn'];
        $bookData->published_at = $inputData['publishedDate'];
        $bookData->status = "AVAILABLE";
        $bookData->save();
        return redirect()->back()->with('success','Book added to the library successfully');
    }
    public function listAvailableBooks() {
        $books = Book::where('status',"AVAILABLE")->get();
        $data['books'] = $books;
        $data['listType'] = 'available';
        return view('listBooks')->with('data', $data);
    }

    public function listBorrowedBooks() {
        $books = DB::table('books')
        ->join('user_action_logs', function ($join) {
            $join->on('books.id', '=', 'user_action_logs.book_id')
            ->where([['user_action_logs.user_id', '=', Auth::user()->id],['user_action_logs.action','=',"CHECKOUT"],['books.status','=',"CHECKED_OUT"]]);})
            ->select('books.id','books.title', 'books.isbn', 'books.published_at')
            ->groupBy('books.id')
            ->get();
        $data['books'] = $books;
        $data['listType'] = 'checkedOut';
        return view('listBooks')->with('data', $data);
    }

    public function listUserActivity() {
        $logs = DB::table('books')
        ->join('user_action_logs', function ($join) {
            $join->on('books.id', '=', 'user_action_logs.book_id')
                 ->where('user_action_logs.user_id', '=', Auth::user()->id);
        })
        ->select('books.title', 'books.isbn', 'user_action_logs.action','user_action_logs.created_at')
        ->latest('user_action_logs.created_at')
        ->get();
        return view('userActivityLog')->with('logs',$logs);
    }

    public function changeBookStatus(UserAction $request) {
        $request->validated();
        $bookId = $request->bookId;
        $action = strtoupper($request->action);
        $response = array();
        $changeBookStatusTo = ($action=="CHECKIN") ? "AVAILABLE" : "CHECKED_OUT";
        $book = new Book;
        $userBook = new UserActionLogs;
        $bookInfo = $book->where('id',$bookId)->first();
        if($bookInfo->count()<1) {
            $response['status'] = 'danger';
            $response['message'] = "Book does not exist";
            goto response;
        }
        if($bookInfo->status==$changeBookStatusTo) {
            $response['status'] = 'danger';
            $response['message'] =  ($action=="CHECKIN") ? "Book cannot be checked in!" : "Book cannot be checked out!";
            goto response;
        }
        if($action=="CHECKIN") {
            $userBookInfo = $userBook->where([['book_id',$bookId],['user_id',Auth::user()->id],['action',"CHECKOUT"]])->latest('user_action_logs.created_at')->first();
            if(empty($userBookInfo)) {
                $response['status'] = 'danger';
                $response['message'] = "Not Permitted to check in the book!";
                goto response;
            }
        }
        $userBook->user_id = Auth::user()->id;
        $userBook->book_id = $bookId;
        $userBook->action = $action;
        $userBook->save();
        $bookInfo->status = $changeBookStatusTo;
        $bookInfo->save();
        $response['status'] = 'success';
        $response['message'] = ($action=="CHECKIN") ? "Book checked in successfully!" : "Book checked out successfully!";
        response:
        return redirect()->back()->with('response',$response);
    }

    public function isISBNValid($isbn) {
           if(!is_numeric(substr($isbn,0,-1))) {
              return false;
            }
           $isbnArray =  str_split($isbn);
           $i=10;
           if($isbnArray[9]=='X') {
                $isbnArray[9] = 10;
           }
           foreach($isbnArray as $key => $val) {
                $isbnArray[$key] = $val * $i;
                $i--;
           }
           if(array_sum($isbnArray)%11==0) {
               return true;
           }
           return false;
    }
}
