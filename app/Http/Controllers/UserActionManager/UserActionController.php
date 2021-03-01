<?php

namespace App\Http\Controllers\UserActionManager;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserAction; //This file contains user action validation and sanitization logic
use App\Http\Controllers\UserActionManager\UserActionService;

class UserActionController extends Controller
{
    /**
     * Retrieves all user actions.
     */
    public function getUserActions(UserActionService $service) {
        $logs = $service->getUserActions();
        return view('userActivityLog')->with('logs',$logs);
    }
    /**
     * Performs user CHECKIN/CHECKOUT action.
     * returns relevant message and status for the user.
     */
    public function performUserAction(UserAction $request, UserActionService $service) {
        $request->validated();
        $bookId = $request->bookId;
        $action = strtoupper($request->action);
        $response = $service->performUserAction($bookId,$action);
        return redirect()->back()->with('response',$response);
    }
}
