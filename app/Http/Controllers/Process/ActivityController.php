<?php

namespace App\Http\Controllers\Process;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Process\ActivityRequest;
use App\Jobs\Process\CreateActivity;
use App\Models\Process\Activity;
use App\Models\Process\Catalogs\GeneratedService;
use Illuminate\Http\RedirectResponse;

class ActivityController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param ActivityRequest $request
     * @return RedirectResponse
     */
    public function store(ActivityRequest $request): RedirectResponse
    {
        $response = $this->ajaxDispatch(new CreateActivity($request));
        if ($response['success']) {
            flash(trans_choice('messages.success.added', 0, ['type' => trans_choice('general.activities', 1)]))->success();
        } else {
            flash($response['message'])->error();
        }
        return redirect()->route('processes.edit', $request->process_id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Activity $activity, $subMenu, $page)
    {
        $activity->load(['process']);
        $generated_services=GeneratedService::all();
        $process = $activity->process;
        return view('modules.process.activities.edit', ['process' => $process, 'activity' => $activity,'generated_services'=>$generated_services, 'subMenu' => $subMenu, 'page' => $page]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroyActivity($id,string $page)
    {
        $activity = Activity::find($id);
        $response = $this->ajaxDispatch(new \App\Jobs\Process\DeleteActivity($activity));
        if ($response['success']) {
            flash(trans_choice('messages.success.deleted', 0, ['type' => trans_choice('general.activities', 1)]))->success();
            return redirect()->route('process.showActivities', [$activity->process_id, $page]);
        } else {
            flash($response['message'])->error();
            return redirect()->route('process.showActivities', [$activity->process_id, $page]);
        }
    }
}
