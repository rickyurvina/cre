<?php

namespace App\Http\Controllers\Admin;

use App\Abstracts\Http\Controller;
use App\Jobs\Admin\CreatePerspective;
use App\Jobs\Admin\DeletePerspective;
use App\Jobs\Admin\UpdatePerspective;
use App\Models\Admin\Perspective;
use Illuminate\Http\Request;
use Throwable;

class PerspectiveController extends Controller
{
    public function index()
    {
        try {
            $perspectives = Perspective::orderBy('id', 'asc')->get();
            return view('modules.admin.perspectives.index', ['perspectives' => $perspectives]);
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->back();
        }
    }

    public function create()
    {
        try {
            return view('modules.admin.perspectives.create');
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        try {

            $response = $this->ajaxDispatch(new CreatePerspective($request));
            if ($response['success']) {
                $response['redirect'] = route('perspectives.index');
                $message = trans_choice('messages.success.added', 1, ['type' => trans_choice('general.perspectives', 1)]);
                flash($message)->success();
                return redirect()->route('perspectives.index');
            } else {
                $response['redirect'] = route('perspective.create');
                $message = $response['message'];
                flash($message)->error();
                return redirect()->route('perspective.create');
            }
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->route('perspectives.create');
        }
    }

    public function show($id)
    {
        try {
            $perspective = Perspective::find($id);
            return view('modules.admin.perspectives.show', [
                'perspective' => $perspective
            ]);
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->route('perspectives.index');
        }
    }

    public function edit($id)
    {
        try {
            $perspective = Perspective::find($id);
            return view('modules.admin.perspectives.edit', [
                'perspective' => $perspective
            ]);
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->route('perspectives.index');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $response = $this->ajaxDispatch(new UpdatePerspective($request, $id));
            if ($response['success']) {
                $response['redirect'] = route('perspectives.index');
                $message = trans_choice('messages.success.updated', 2, ['type' => trans_choice('general.perspective', 1)]);
                flash($message)->success();
                return redirect()->route('perspectives.index');
            } else {
                $response['redirect'] = route('perspectives.edit');
                $message = $response['message'];
                flash($message)->error();
                return redirect()->route('perspectives.edit');
            }
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->route('perspectives.edit');
        }
    }

    public function destroy($id)
    {
        try {
            $response = $this->ajaxDispatch(new DeletePerspective($id));
            if ($response['success']) {
                $message = trans_choice('messages.success.deleted', 1, ['type' => trans_choice('general.perspective', 1)]);
                flash($message)->success();
                return redirect()->route('perspectives.index');
            } else {
                $message = $response['message'];
                flash($message)->error();
                return redirect()->route('perspectives.index');
            }
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->route('perspectives.index');
        }
    }
}
