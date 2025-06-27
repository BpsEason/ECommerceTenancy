<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\QueueUpdated;

class QueueController extends Controller
{
    /**
     * Get the current public queue status.
     * This endpoint is public and does not require authentication.
     */
    public function publicQueue()
    {
        // Fetch the queue status. This is a simplified example.
        // In a real app, you would fetch the next 5-10 numbers.
        $next_numbers = DB::table('queues')
                          ->where('status', 'waiting')
                          ->orderBy('created_at', 'asc')
                          ->limit(10)
                          ->get(['id', 'queue_number', 'status']); // Select specific columns for public view

        // Fetch the currently serving number, if any
        $serving_number = DB::table('queues')
                            ->where('status', 'serving')
                            ->orderBy('updated_at', 'desc')
                            ->first(['id', 'queue_number', 'status']);

        return response()->json([
            'currently_serving' => $serving_number ? $serving_number->queue_number : null,
            'waiting_list' => $next_numbers,
        ]);
    }

    /**
     * A method to advance the queue. (Example - requires authentication)
     */
    public function advanceQueue(Request $request)
    {
        // Logic to move a number from 'waiting' to 'serving'
        // For demonstration, let's assume we move the first waiting number.
        DB::transaction(function () {
            $nextWaiting = DB::table('queues')
                             ->where('status', 'waiting')
                             ->orderBy('created_at', 'asc')
                             ->first();

            if ($nextWaiting) {
                // Set the current serving number to 'completed' or a similar status
                DB::table('queues')->where('status', 'serving')->update(['status' => 'completed']);
                // Update the next waiting number to 'serving'
                DB::table('queues')->where('id', $nextWaiting->id)->update(['status' => 'serving']);

                // Broadcast the update to all listeners
                $newServing = DB::table('queues')->where('id', $nextWaiting->id)->first();
                $waitingList = DB::table('queues')->where('status', 'waiting')->orderBy('created_at', 'asc')->limit(10)->get();
                broadcast(new QueueUpdated([
                    'currently_serving' => $newServing->queue_number,
                    'waiting_list' => $waitingList
                ]))->toOthers(); // Don't broadcast to the originator

            }
        });

        return response()->json(['message' => 'Queue advanced.']);
    }

    /**
     * A method to add a new number to the queue. (Example)
     */
    public function addNumber(Request $request)
    {
        $newNumber = rand(100, 999); // Generate a random queue number for demo
        $queueEntry = DB::table('queues')->insert([
            'queue_number' => $newNumber,
            'status' => 'waiting',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return response()->json(['message' => 'Number added to queue.', 'number' => $newNumber]);
    }
}
