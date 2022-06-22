<?php

namespace App\Http\Controllers\Strategy;

use App\Abstracts\Http\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class HomeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        if (user()->cannot('strategy-crud-strategy') && user()->cannot('strategy-read-strategy')) {
            abort(403);
        }

        return view('modules.strategy.home.report');
    }
}
