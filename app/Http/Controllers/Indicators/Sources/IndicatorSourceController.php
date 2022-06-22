<?php

namespace App\Http\Controllers\Indicators\Sources;

use App\Http\Requests\Indicator\Sources\IndicatorSourceRequest;
use App\Jobs\Indicators\Sources\CreateSource;
use App\Jobs\Indicators\Sources\DeleteSource;
use App\Jobs\Indicators\Sources\UpdateSource;
use App\Models\Indicators\Sources\IndicatorSource;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Abstracts\Http\Controller;

class IndicatorSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $sources = IndicatorSource::orderBy('id','asc')->collect();
        return view('indicator.sources.index', ['sources' => $sources]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('indicator.sources.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param IndicatorSourceRequest $request
     *
     * @return RedirectResponse
     */
    public function store(IndicatorSourceRequest $request)
    {
        $response = $this->ajaxDispatch(new CreateSource($request));

        if ($response['success']) {
            $message = trans_choice('messages.success.added', 1, ['type' => trans_choice('general.sources', 0)]);
            flash($message)->success();
            return redirect()->route('indicator_sources.index');
        } else {
            $message = $response['message'];
            flash($message)->error();
            return redirect()->route('indicator_sources.create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     *
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $sources = IndicatorSource::find($id);
        return view('indicator.sources.show', [
            'source' => $sources
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     *
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $sources = IndicatorSource::find($id);
        return view('indicator.sources.edit', [
            'source' => $sources
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(IndicatorSourceRequest $request, $id)
    {
        $response = $this->ajaxDispatch(new UpdateSource($request, $id));
        if ($response['success']) {
            $message = trans_choice('messages.success.updated', 2, ['type' => trans_choice('general.sources', 0)]);
            flash($message)->success();
            return redirect()->route('indicator_sources.index');
        } else {
            $message = $response['message'];
            flash($message)->error();
            return redirect()->route('indicator_sources.edit');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $response = $this->ajaxDispatch(new DeleteSource($id));
        if ($response['success']) {
            if ($response['data']) {
                $response['redirect'] = route('indicator_sources.index');
                $message = trans_choice('messages.error.source_in_indicator', 1, ['type' => trans_choice('general.sources', 0)]);
                flash($message)->error();
                return redirect()->route('indicator_sources.index');
            } else {
                $response['redirect'] = route('indicator_sources.index');
                $message = trans_choice('messages.success.deleted', 1, ['type' => trans_choice('general.sources', 0)]);
                flash($message)->success();
                return redirect()->route('indicator_sources.index');
            }

        } else {
            $response['redirect'] = route('indicator_sources.index');
            $message = $response['message'];
            flash($message)->error();
            return redirect()->route('indicator_sources.index');
        }
    }
}
