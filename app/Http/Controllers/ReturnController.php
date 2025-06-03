<?php

namespace App\Http\Controllers;

use App\Models\TemporaryIssueLog;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    public function returnItem(Request $request)
    {
        $request->validate([
            'temporary_issue_id' => 'required|exists:temporaryissuelog,id_TemporaryIssueLog',
        ]);

        $user = Auth::guard('api')->user();

        $log = TemporaryIssueLog::where('id_TemporaryIssueLog', $request->temporary_issue_id)
            ->where('fkUserid_User', $user->id_User)
            ->whereNull('ReturnedDate')
            ->firstOrFail();

        $item = $log->item;
        $item->Quantity += $log->Quantity;;
        $item->save();

        $log->ReturnedDate = now();
        $log->save();

        return response()->json(['message' => 'Daiktas grąžintas.']);
    }
}
