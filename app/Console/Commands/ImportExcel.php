<?php

namespace App\Console\Commands;

use DB;
use Exception;
use Carbon\Carbon;
use App\Models\Contact;
use League\Csv\Reader;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

class ImportExcel extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:excel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Excel Records into Contacts table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        try {
            $this->info("Starting command...");
            $directory = 'import/contacts/';
            $files = Storage::files($directory);
            $this->info(count($files) . " files found." . (count($files) > 0 ? " Importing files...." : " Exiting..."));
            foreach ($files as $file) {
                $path = storage_path() . '/app/' . $file;
                $this->loadContacts($path); // ? Storage::delete($file) : false; //send email when batch load fails
            }
        } catch (Exception $ex) {
            // TODO: If import rocess fails, send email with error?
            $this->error($ex->getMessage());
        }
    }

    private function loadContacts($path) {
        $reader = Reader::createFromPath($path);
        $results = $reader->fetch(); //Could change to not being needed, if upgraded to League: 9.1
        try {
            DB::beginTransaction();
            foreach ($results as $row) {
                dd($row);
                //Log::info($row);
                $contact = new Contact();
                $contact->name = $row[0];
                $contact->surname = $row[1];
                $contact->email = $row[2];
                $contact->mobile_number = $row[3];
                $province = \App\Models\Province::where('name', $row[4])->first();
                if (is_null($province)){
                    throw new Exception("Invalid Province found (" . $row[4] . ") for contact:" . $contact->name . ' ' . $contact->surname);
                }
                $contact->province_id = $row[4];
                if (!$contact->save()) {
                    $this->info('Failed to save contact :' . $contact->name . ' ' . $contact->surname);
                    Log::useDailyFiles(storage_path() . '/logs/command-import-contacts-' . Carbon::now()->toDateString() . '.log');
                    Log::info('Contact: ' . $contact->name . ' ' . $contact->surname . ' with error: ' . $contact->getError(), $contact->getFieldErrors());
                } else {
                    $this->info('Saved contact: ' . $contact->name . ' ' . $contact->surname);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            $this->error($e);
            DB::rollBack();
            Log::info($e);
        }
    }

}
