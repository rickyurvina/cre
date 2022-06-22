<?php

namespace App\Traits;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Throwable;

trait Jobs
{
    /**
     * Dispatch a job to its appropriate handler.
     *
     * @param mixed $job
     * @return mixed
     */
    public function dispatch($job)
    {
        $function = $this->getDispatchFunction();

        return $function($job);
    }

    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * @param mixed $job
     * @param mixed $handler
     * @return mixed
     */
    public function dispatchNow($job, $handler = null)
    {
        return dispatch_now($job, $handler);
    }

    /**
     * Dispatch a job to its appropriate handler and return a response array for ajax calls.
     *
     * @param mixed $job
     * @return array
     */
    public function ajaxDispatch($job): array
    {
        $response = [
            'success' => true,
            'error' => false,
            'data' => '',
            'message' => '',
        ];
        try {
            $data = $this->dispatch($job);
            $response['data'] = $data;
            $response['message'] = $data;
        } catch (Exception | \Throwable $e) {
            $response['success'] = false;
            $response['error'] = true;
            $response['message'] = str_replace(array('\'', '"'), '',
                preg_replace("[\n|\r|\n\r]", "", $e->getMessage()));
        } finally {
            return $response;
        }

    }

    public function getDispatchFunction()
    {
        $config = config('queue.default');

        return ($config == 'sync') ? 'dispatch_now' : 'dispatch';
    }
}
