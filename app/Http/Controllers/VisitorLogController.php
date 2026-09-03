<?php

namespace App\Http\Controllers;

use App\Models\VisitorLog;
use Illuminate\Http\Request;

class VisitorLogController extends Controller
{
    public function index(Request $request)
    {
        $search =
            trim(
                (string) $request->get(
                    'search',
                    ''
                )
            );

        $query =
            VisitorLog::query()
                ->with('user');


        if ($search !== '') {

            $query->where(
                function ($q) use ($search) {

                    $q->where(
                        'ip_address',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'url',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'route_name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'browser',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'device',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'platform',
                        'like',
                        '%' . $search . '%'
                    );

                }
            );
        }


        $visitorLogs =
            $query
                ->orderByDesc(
                    'visited_at'
                )
                ->paginate(25)
                ->withQueryString();


        $totalVisitors =
            VisitorLog::count();


        $todayVisitors =
            VisitorLog::whereDate(
                'visited_at',
                today()
            )
                ->count();


        $uniqueIpToday =
            VisitorLog::whereDate(
                'visited_at',
                today()
            )
                ->distinct(
                    'ip_address'
                )
                ->count(
                    'ip_address'
                );


        return view(
            'visitor-logs.index',
            compact(
                'visitorLogs',
                'search',
                'totalVisitors',
                'todayVisitors',
                'uniqueIpToday'
            )
        );
    }
}