<?php

namespace App\Providers;

use Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobFailed;

/**
 * A provider which handles when jobs start to fail
 */
class ErrorReportingProvider extends ServiceProvider
{
    /**
     * Grabs the list of all failing jobs in the queue and proccesses
     * them
     */
    public function boot()
    {
        Queue::failing([$this, 'processFailedJob']);
    }

    /**
     * Processes a job by sending a notication from slack
     *
     * Warning: If slack fails to send the message, this will _not_
     * raise any exceptions or similar. This is because when the error
     * system errors, laravel basically just gives up and deletes the
     * job
     */
    public function processFailedJob(JobFailed $event) : void
    {
        try {
            $this->sendSlackMessage($event);
        } catch (\Exception $e) {
            // Do nothing, deliberately!
        }
    }

    /**
     * Sends a slack message for the given JobFailed event
     *
     * @param JobFailed $event The failure to notify the devs about
     */
    public function sendSlackMessage(JobFailed $event) : void
    {
        \Slack::send(
            '@channel :red-alert: ' .
            $event->job->resolveName() . ' has failed with message: ' .
            '"' . $event->exception->getMessage() . '"'
        );
    }
}
