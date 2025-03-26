<?php

namespace App\Jobs;

use App\Events\GotMessage;
use App\Models\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessage implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly Message $message)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        broadcast(new GotMessage([
            'id' => $this->message->id,
            'text' => $this->message->text,
            'user' => $this->message->user,
            'time' => $this->message->time,
        ]))->toOthers();
    }
}
