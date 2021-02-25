<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book; //Model for this controller
use App\Models\UserActionLogs; //Model for this controller
use App\Http\Requests\BookRequest; //This file contains request validation and sanitization logic
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class BookController extends Controller
{
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
        $bookData->published_at = $inputData['published_at'];
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
                 ->where([['user_action_logs.user_id', '=', Auth::user()->id],['books.status','=','CHECKED_OUT']]);
        })->get();
        $data['books'] = $books;
        $data['listType'] = 'borrowed';
        return view('listBooks')->with('data', $data);
    }

    public function listuserActivity() {
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

    public function changeBookStatus(BookRequest $request) {
        $inputData = $request->validated();
        //Log::debug(print_r($inputData,true));
        $response = array();
        $bookInfo = new Book;
        $userInfo = new UserActionLogs;
        $bookExistsInDB = $bookInfo->where('id',$inputData['bookId'])->first();
        if($bookExistsInDB->count()<1) {
            $response['status'] = "danger";
            $response['message'] = "Book does not exist";
            goto response;
        }
        if($bookExistsInDB->status==$inputData['changeAction']) {
            $response['status'] = "danger";
            $response['message'] =  ($inputData['changeAction']=="AVAILABLE") ? "Book cannot be returned" : "Book cannot be borrowed";
            goto response;
        }
        /*$userBookInfo = $userInfo->where([['book_id',$inputData['bookId']],['user_id',Auth::user()->id]])->latest();
        if($userBookInfo->count()>0) {
                $response['status'] = "danger";
                $response['message'] = "Book cannot be returned";
                goto response;
        }*/
        /*$currentStatus = ($inputData['changeAction']=="AVAILABLE") ? "CHECKED_OUT" : "AVAILABLE";
        $books = DB::table('books')
        ->join('user_action_logs', function ($join,$inputData,$currentStatus) {
            $join->on('books.id', '=', $inputData['book_id'])
                 ->where([['user_action_logs.user_id', '=', Auth::user()->id],['books.status','=',$currentStatus]]);
        })->first();*/
        $userInfo->book_id = $inputData['bookId'];
        $userInfo->user_id = Auth::user()->id;
        $userInfo->action = ($inputData['changeAction']=="AVAILABLE") ? "CHECKIN" : "CHECKOUT";
        $userInfo->save();
        $bookExistsInDB->status = $inputData['changeAction'];
        $bookExistsInDB->save();
        $response['status'] = "success";
        $response['message'] = ($inputData['changeAction']=="AVAILABLE") ? "Book returned successfully" : "Book borrowed successfully";
        response:
        return redirect()->back()->with('response',$response);
    }

    public function isISBNValid($isbn) {
           $isbnArray =  str_split($isbn);
           $i=10;
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
