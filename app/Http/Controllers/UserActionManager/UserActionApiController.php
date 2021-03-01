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

class UserActionApiController extends Controller
{
    public function getUserActions(Request $request, UserActionService $service) {
        if($request->has('action')) {
            $inputData = $request->only('action');
            $action = strtoupper($inputData['action']);
            if($action!="CHECKIN" && $action!="CHECKOUT") {
                return response()->json(["message"=>"The given data was invalid.","errors"=>["action"=>["Action should be CHECKIN or CHECKOUT"]]],'422');
            }
           $logs = $service->getUserActions($action);
        } else {
           $logs = $service->getUserActions();
        }
        if($logs->isEmpty()) {
            return response()->json(["message"=>"No matching record(s) found"],'200');
        }
        return response()->json(["message"=>$logs->count(). " matching record(s) found","data"=>$logs],'200');
    }
    public function performUserAction(UserAction $request, UserActionService $service) {
        $request->validated();
        $bookId = $request->bookId;
        $action = strtoupper($request->action);
        $response = $service->performUserAction($bookId,$action);
        return response()->json(["message"=>$response['message']],$response['status_code']);
    }
}
