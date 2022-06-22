<?php

namespace App\Http\Controllers\Admin;

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
        if (user()->cannot('admin-crud-admin') && user()->cannot('admin-read-admin')) {
            abort(403);
        }
        return redirect(route('companies.index'));
    }
}
