<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class BlogBulkInsert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $file_name;
    protected $file_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $file_name, $file_id)
    {
        $this->user = $user;
        $this->file_name = $file_name;
        $this->file_id = $file_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user_id = $this->user;
        $file_id = $this->file_id;

        $file = \App\Models\BlogBulkInsert::find($file_id);
        $file_name = $file->file_name;

        DB::beginTransaction();
        try {
            // Open the CSV file for reading
            $files = storage_path('app/blogBulkInsert/' . $file_name);
            $file = fopen($files, "r");

            // Skip the first row (header)
            $header = fgetcsv($file);

            // Loop through the remaining rows in the file
            while (($blog = fgetcsv($file)) !== false) {
                // Do something with the row data here
                // For example, you can print out the data:
                \DB::table('blogs')->insertOrIgnore([
                    'user_id' => $user_id,
                    'title' => strip_tags($blog[0]),
                    'body' => strip_tags($blog[1]),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // Close the file
            fclose($file);

            DB::commit();
            unlink($files);
        } catch (\Exception $e) {
            DB::rollback();
        }
    }
}