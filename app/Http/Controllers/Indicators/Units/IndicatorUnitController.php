<?php

namespace App\Http\Controllers\Indicators\Units;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Indicator\Units\IndicatorUnitsRequest;
use App\Jobs\Indicators\Units\CreateUnitIndicator;
use App\Jobs\Indicators\Units\DeleteUnitIndicator;
use App\Jobs\Indicators\Units\UpdateUnitIndicator;
use App\Models\Indicators\Units\IndicatorUnits;
use Throwable;

class IndicatorUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        try {
            $units = IndicatorUnits::orderBy('id','asc')->collect();
            return view('indicator.units.index', ['units' => $units]);
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            return view('indicator.units.create');
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(IndicatorunitsRequest $request)
    {
        try {
            $response = $this->ajaxDispatch(new CreateUnitIndicator($request));
            if ($response['success']) {
                $response['redirect'] = route('indicator_units.index');
                $message = trans_choice('messages.success.added', 1, ['type' => trans_choice('general.units', 1)]);
                flash($message)->success();
                return redirect()->route('indicator_units.index');
            } else {
                $response['redirect'] = route('indicator_units.create');
                $message = $response['message'];
                flash($message)->error();
                return redirect()->route('indicator_units.create');
            }
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->route('indicator_units.create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Indicators\units\IndicatorUnits $indicatorCategorie
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $units = IndicatorUnits::find($id);
            return view('indicator.units.show', [
                'unit' => $units
            ]);
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->route('indicator_units.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        try {
            $units = IndicatorUnits::find($id);
            return view('indicator.units.edit', [
                'unit' => $units
            ]);
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->route('indicator_units.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(IndicatorunitsRequest $request, $id)
    {
        try {
            $response = $this->ajaxDispatch(new UpdateUnitIndicator($request, $id));
            if ($response['success']) {
                $response['redirect'] = route('indicator_units.index');
                $message = trans_choice('messages.success.updated', 2, ['type' => trans_choice('general.units', 1)]);
                flash($message)->success();
                return redirect()->route('indicator_units.index');
            } else {
                $response['redirect'] = route('indicator_units.edit');
                $message = $response['message'];
                flash($message)->error();
                return redirect()->route('indicator_units.edit');
            }
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->route('indicator_units.edit');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Indicators\units\IndicatorUnits $indicatorCategorie
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $response = $this->ajaxDispatch(new DeleteUnitIndicator($id));
            if ($response['success']) {
                if ($response['data']) {
                    $response['redirect'] = route('indicator_units.index');
                    $message = trans_choice('messages.error.unit_in_indicator', 1, ['type' => trans_choice('general.units', 1)]);
                    flash($message)->error();
                    return redirect()->route('indicator_units.index');
                }
                else{
                    $response['redirect'] = route('indicator_units.index');
                    $message = trans_choice('messages.success.deleted', 1, ['type' => trans_choice('general.units', 1)]);
                    flash($message)->success();
                    return redirect()->route('indicator_units.index');
                }
            } else {
                $response['redirect'] = route('indicator_units.index');
                $message = $response['message'];
                flash($message)->error();
                return redirect()->route('indicator_units.index');
            }
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->route('indicator_units.index');
        }
    }
}
