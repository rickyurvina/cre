<?php

namespace App\Http\Controllers\Indicators\Observations;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Indicator\Observations\IndicatorObservationsRequest;
use App\Jobs\Indicators\Observations\CreateObservationIndicator;
use App\Jobs\Indicators\Observations\DeleteObservationIndicator;
use App\Models\Indicators\Observations\IndicatorObservations;
use Throwable;


class IndicatorObservationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(IndicatorObservationsRequest $request)
    {
        try {
            $response = $this->ajaxDispatch(new CreateObservationIndicator($request));

            if ($response['success']) {
                $response['redirect'] = route('indicator_units.index');
                $message = trans_choice('messages.success.added', 1, ['type' => trans_choice('general.observation', 1)]);
                flash($message)->success();
                return redirect()->route('indicators.edit', $request->input('indicators_id'));
            } else {
                $response['redirect'] = route('indicator_units.create');
                $message = $response['message'];
                flash($message)->error();
                return redirect()->route('indicators.edit', $request->input('indicators_id'));
            }
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->route('indicators.edit', $request->input('indicators_id'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\IndicatorObservations $indicatorObservations
     * @return \Illuminate\Http\Response
     */
    public function show(IndicatorObservations $indicatorObservations)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\IndicatorObservations $indicatorObservations
     * @return \Illuminate\Http\Response
     */
    public function edit(IndicatorObservations $indicatorObservations)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\IndicatorObservations $indicatorObservations
     * @return \Illuminate\Http\Response
     */
    public function update(IndicatorObservationsRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\IndicatorObservations $indicatorObservations
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $response = $this->ajaxDispatch(new DeleteObservationIndicator($id));
            if ($response['success']) {
                if ($response['data']) {
                    $response['redirect'] = route('indicator_units.index');
                    $message = trans_choice('messages.error.unit_in_indicator', 1, ['type' => trans_choice('general.observation', 1)]);
                    flash($message)->error();
                    return redirect()->back();
                } else {
                    $response['redirect'] = route('indicator_units.index');
                    $message = trans_choice('messages.success.deleted', 1, ['type' => trans_choice('general.observation', 1)]);
                    flash($message)->success();
                    return redirect()->back();
                }
            } else {
                $response['redirect'] = route('indicator_units.index');
                $message = $response['message'];
                flash($message)->error();
                return redirect()->back();
            }
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->back();
        }
    }
}
