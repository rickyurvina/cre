<?php

namespace App\Http\Controllers\Indicators\Thresholds;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Indicator\Threshold\ThresholdRequest;
use App\Jobs\Indicators\Thresholds\CreateThreshold;
use App\Jobs\Indicators\Thresholds\DeleteThreshold;
use App\Jobs\Indicators\Thresholds\UpdateThreshold;
use App\Models\Indicators\Threshold\Threshold;
use Throwable;


class ThresHoldController extends Controller
{
    /**
     * /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        try {
            $thresholds = Threshold::orderBy('id','asc')->collect();
            return view('indicator.threshold.index', ['Thresholds' => $thresholds]);
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
            return view('indicator.threshold.create');
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
    public function store(ThresholdRequest $request)
    {
        try {
            $response = $this->ajaxDispatch(new CreateThreshold($request));
            if ($response['success']) {
                $response['redirect'] = route('thresholds.index');
                $message = trans_choice('messages.success.added', 0, ['type' => trans_choice('general.thresholds', 1)]);
                flash($message)->success();
                return redirect()->route('thresholds.index');
            } else {
                $response['redirect'] = route('thresholds.create');
                $message = $response['message'];
                flash($message)->error();
                return redirect()->route('thresholds.create');
            }
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->route('thresholds.create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $threshold = Threshold::find($id);
            return view('indicator.threshold.show', [
                'threshold' => $threshold,
            ]);
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        try {
            $threshold = Threshold::find($id);
            return view('indicator.threshold.edit', [
                'threshold' => $threshold,
//                'data' => $data
            ]);
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ThresholdRequest $request, $id)
    {
        try {
            $response = $this->ajaxDispatch(new UpdateThreshold($request, $id));
            if ($response['success']) {
                $response['redirect'] = route('thresholds.index');
                $message = trans_choice('messages.success.updated', 0, ['type' => trans_choice('general.thresholds', 1)]);
                flash($message)->success();
                return redirect()->route('thresholds.index');
            } else {
                $response['redirect'] = route('thresholds.edit');
                $message = $response['message'];
                flash($message)->error();
                return redirect()->route('thresholds.edit');
            }
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->route('thresholds.edit');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $response = $this->ajaxDispatch(new DeleteThreshold($id));
            if ($response['success']) {
                if ($response['data']) {
                    $response['redirect'] = route('thresholds.index');
                    $message = trans_choice('messages.error.threshold_in_indicator', 0, ['type' => trans_choice('general.thresholds', 1)]);
                    flash($message)->error();
                    return redirect()->route('thresholds.index');
                }
                else{
                    $response['redirect'] = route('thresholds.index');
                    $message = trans_choice('messages.success.deleted', 0, ['type' => trans_choice('general.thresholds', 1)]);
                    flash($message)->success();
                    return redirect()->route('thresholds.index');
                }

            } else {
                $response['redirect'] = route('thresholds.index');
                $message = $response['message'];
                flash($message)->error();
                return redirect()->route('thresholds.index');
            }
        } catch (Throwable $e) {
            flash($e)->error();
            return redirect()->route('thresholds.index');
        }
    }
}
