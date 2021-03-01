<?php
namespace App\Http\Controllers\UserActionManager;

use App\Models\Book; //Model for this controller
use App\Models\UserActionLogs; //Model for this controller
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class UserActionService {

    public function getUserActions($action = NULL) {
        if(isset($action)) {
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
        return $logs;
    }

    public function performUserAction($bookId, $action) {
        $response = array();
        if($action!="CHECKIN" && $action!="CHECKOUT") {
            $response['status_code'] = '422';
            $response['message'] = "Action should be CHECKIN or CHECKOUT.";
            goto response;
        }
        $changeBookStatusTo = ($action=="CHECKIN") ? "AVAILABLE" : "CHECKED_OUT";
        $book = new Book;
        $userBook = new UserActionLogs;
        $bookInfo = $book->where('id',$bookId)->first();
        if($bookInfo->count()<1) {
            $response['status_code'] = '200';
            $response['status'] = "danger";
            $response['message'] = "Book does not exist";
            goto response;
        }
        if($bookInfo->status==$changeBookStatusTo) {
            $response['status_code'] = '200';
            $response['status'] = "danger";
            $response['message'] =  ($action=="CHECKIN") ? "Book cannot be checked in." : "Book cannot be checked out.";
            goto response;
        }
        if($action=="CHECKIN") {
            $userBookInfo = $userBook->where([['book_id',$bookId],['user_id',Auth::user()->id],['action',"CHECKOUT"]])->latest('user_action_logs.created_at')->first();
            if(empty($userBookInfo)) {
                $response['status_code'] = '200';
                $response['status'] = "danger";
                $response['message'] = "Not Permitted to check in the book.";
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
        $response['status'] = "success";
        $response['message'] = ($action=="CHECKIN") ? "Book checked in successfully." : "Book checked out successfully.";
        response:
        return $response;
    }
}
