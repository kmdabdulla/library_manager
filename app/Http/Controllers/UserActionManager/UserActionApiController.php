<?php

namespace App\Http\Controllers\UserActionManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserAction; //This file contains user action validation and sanitization logic
use App\Http\Controllers\UserActionManager\UserActionService;

class UserActionApiController extends Controller
{
    /**
     * Retrieves all user actions.
     * action value can be used to filter the results.
     */
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
    /**
     * Performs user CHECKIN/CHECKOUT action.
     * returns relevant message and http code.
     */
    public function performUserAction(UserAction $request, UserActionService $service) {
        $request->validated();
        $bookId = $request->bookId;
        $action = strtoupper($request->action);
        $response = $service->performUserAction($bookId,$action);
        return response()->json(["message"=>$response['message']],$response['status_code']);
    }
}
