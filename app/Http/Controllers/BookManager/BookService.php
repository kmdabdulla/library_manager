<?php
namespace App\Http\Controllers\BookManager;

use App\Models\Book; //Model for this controller
use App\Models\UserActionLogs; //Model for this controller
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookService {

    public function addBook($bookDetails) {
        $bookData = new Book;
        $bookData->title = $bookDetails['title'];
        $bookData->isbn = $bookDetails['isbn'];
        $bookData->published_at = $bookDetails['publishedDate'];
        $bookData->status = "AVAILABLE";
        $bookData->save();
        return $bookData;
    }

    public function getBooks($queryData=NULL) {
        if(isset($queryData)) {
            $column = array_key_first($queryData);
            $books = Book::where($column,$queryData[$column])->get();
        } else {
            $books = Book::all();
        }
        return $books;
    }

    public function getUserCheckedOutBooks() {
        $books = DB::table('books')
                ->join('user_action_logs', function ($join) {
                    $join->on('books.id', '=', 'user_action_logs.book_id')
                ->where([['user_action_logs.user_id', '=', Auth::user()->id],['user_action_logs.action','=',"CHECKOUT"],['books.status','=',"CHECKED_OUT"]]);})
                ->select('books.id','books.title', 'books.isbn', 'books.published_at','books.status')
                ->groupBy('books.id')
                ->get();
        return $books;
    }

    public function isISBNValid($isbn) {
        if(!is_numeric(substr($isbn,0,-1))) {
           return false;
         }
        $isbnArray =  str_split($isbn);
        $i=10;
        if(!is_numeric($isbnArray[9])) {
             if($isbnArray[9]=='X') {
                 $isbnArray[9] = 10;
             } else {
                 return false;
             }
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

