<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book; //Model for this controller
use App\Models\UserActionLogs; //Model for this controller
use App\Http\Requests\BookRequest; //This file contains adding book validation and sanitization logic
use App\Http\Requests\UserAction; //This file contains user action validation and sanitization logic
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BookController;

class BookApiController extends Controller
{
    public function addBookToLibrary(BookRequest $request) {
        $inputData = $request->validated();
        $book = new BookController();
        if(!$book->isISBNValid($inputData['isbn'])) {
            return response()->json(["message"=>"Invalid ISBN-10!"],'422');
        }
        $bookExists = Book::where('isbn', $inputData['isbn'])->first();
        if(!empty($bookExists)) {
            return response()->json(["message"=>"Book already exists!"],'422');
        }
        $bookData = new Book;
        $bookData->title = $inputData['title'];
        $bookData->isbn = $inputData['isbn'];
        $bookData->published_at = $inputData['publishedDate'];
        $bookData->status = "AVAILABLE";
        $bookData->save();
        return response()->json([["message"=>"Book added to the library successfully!"],['data',$bookData]],'200');
    }
    public function listBooks(Request $request) {
        if($request->has('status')) {
            $inputData = $request->only('status');
            $inputData['status'] = strtoupper($inputData['status']);
           if($inputData['status']!="AVAILABLE" && $inputData['status']!="CHECKED_OUT") {
                return response()->json(["message"=>"status should be AVAILABLE or CHECKED_OUT"],'422');
           }
            $books = Book::where('status',$inputData['status'])->get();
        } else {
            $books = Book::all();
            //return response()->json(["message"=>"only status query field is allowed"],'422');
        }
        if($books->isEmpty()) {
            return response()->json(["message"=>"No matching record(s) found"],'200');
        }
        return response()->json([["message"=>$books->count(). " matching record(s) found"],["data"=>$books]],'200');
    }

    public function getBookDetails(Request $request, $bookId) {
        Log::debug(print_r($request->header(),true));
        Log::debug($request->bearerToken());
        if(empty($bookId) || !is_numeric($bookId)) {
            return response()->json(["message"=>"Invalid Book Id"],'422');
        }
        $books = Book::where('id',$bookId)->get();
        if($books->isEmpty()) {
            return response()->json(["message"=>"No matching record found"],'200');
        }
        return response()->json([["message"=>$books->count(). " matching record found"],["data"=>$books]],'200');
    }
    public function listUserBorrowedBooks(Request $request) {
        $books = DB::table('books')
                ->join('user_action_logs', function ($join) {
                    $join->on('books.id', '=', 'user_action_logs.book_id')
                ->where([['user_action_logs.user_id', '=', Auth::user()->id],['user_action_logs.action','=',"CHECKOUT"],['books.status','=',"CHECKED_OUT"]]);})
                ->select('books.id','books.title', 'books.isbn', 'books.published_at','books.status')
                ->groupBy('books.id')
                ->get();
        if($books->isEmpty()) {
            return response()->json(["message"=>"No matching record(s) found"],'200');
        }
        return response()->json([["message"=>$books->count(). " matching record(s) found"],["data"=>$books]],'200');
    }
    public function listUserActivity(Request $request) {
        if($request->has('action')) {
            $inputData = $request->only('action');
            $action = strtoupper($inputData['action']);
            if($action!="CHECKIN" && $action!="CHECKOUT") {
                return response()->json(["message"=>"Action should be CHECKIN or CHECKOUT"],'422');
            }
            $logs = DB::table('books')
                        ->join('user_action_logs', function ($join) use ($action) {
                            $join->on('books.id', '=', 'user_action_logs.book_id')
                        ->where([['user_action_logs.user_id', '=', Auth::user()->id],['user_action_logs.action','=',$action]]);
                        })
                        ->select('books.title', 'books.isbn', 'user_action_logs.action','user_action_logs.created_at')
                        ->latest('user_action_logs.created_at')
                        ->get();
        } else {
            $logs = DB::table('books')
            ->join('user_action_logs', function ($join) {
                $join->on('books.id', '=', 'user_action_logs.book_id')
                     ->where('user_action_logs.user_id', '=', Auth::user()->id);
            })
            ->select('books.title', 'books.isbn', 'user_action_logs.action','user_action_logs.created_at')
            ->latest('user_action_logs.created_at')
            ->get();
        }
        if($logs->isEmpty()) {
            return response()->json(["message"=>"No matching record(s) found"],'200');
        }
        return response()->json([["message"=>$logs->count(). " matching record(s) found"],["data"=>$logs]],'200');
    }
    public function performUserAction(UserAction $request) {
        $request->validated();
        $bookId = $request->bookId;
        $action = strtoupper($request->action);
        $response = array();
        if($action!="CHECKIN" && $action!="CHECKOUT") {
            $response['status_code'] = '422';
            $response['message'] = "Action should be CHECKIN or CHECKOUT";
            goto response;
        }
        $changeBookStatusTo = ($action=="CHECKIN") ? "AVAILABLE" : "CHECKED_OUT";
        $book = new Book;
        $userBook = new UserActionLogs;
        $bookInfo = $book->where('id',$bookId)->first();
        if($bookInfo->count()<1) {
            $response['status_code'] = '200';
            $response['message'] = "Book does not exist";
            goto response;
        }
        if($bookInfo->status==$changeBookStatusTo) {
            $response['status_code'] = '200';
            $response['message'] =  ($action=="CHECKIN") ? "Book cannot be checked in!" : "Book cannot be checked out!";
            goto response;
        }
        if($action=="CHECKIN") {
            $userBookInfo = $userBook->where([['book_id',$bookId],['user_id',Auth::user()->id],['action',"CHECKOUT"]])->latest('user_action_logs.created_at')->first();
            if(empty($userBookInfo)) {
                $response['status_code'] = '200';
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
        $response['status_code'] = '200';
        $response['message'] = ($action=="CHECKIN") ? "Book checked in successfully!" : "Book checked out successfully!";
        response:
        return response()->json(["message"=>$response['message']],$response['status_code']);
    }
}
