<?php

namespace App\Http\Controllers\UserActionManager;

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
use App\Http\Controllers\UserActionManager\UserActionService;

class UserActionController extends Controller
{
    public function getUserActions(UserActionService $service) {
        $logs = $service->getUserActions();
        return view('userActivityLog')->with('logs',$logs);
    }

    public function performUserAction(UserAction $request, UserActionService $service) {
        $request->validated();
        $bookId = $request->bookId;
        $action = strtoupper($request->action);
        $response = $service->performUserAction($bookId,$action);
        return redirect()->back()->with('response',$response);
    }
}
