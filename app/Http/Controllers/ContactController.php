<?php

namespace App\Http\Controllers;

use DB;
use Datatables;
use Carbon\Carbon;
use App\Models\Contact;
use Twilio\Rest\Client;
use App\Mail\SendMailable;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\NotificationLog;
use App\Models\NotificationType;
use App\Jobs\SendSms;
use App\Jobs\SendEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        if ($request->ajax()) {
            $contacts = Contact::select('contacts.*', 'provinces.name AS province')
                    ->join('provinces', 'contacts.province_id', '=', 'provinces.id')
                    ->get();
            $provinces = Province::all();
            $province_options = [];
            foreach ($provinces AS $province) {
                $province_options[] = ["value" => $province->id, "label" => $province->name];
            }

            foreach ($contacts as $contact) {
                $data[] = $this->processData($contact);
            }

            return response()->json(["data" => $data, "options" => ["province_id" => $province_options]]);


            /*
              foreach ($data as $contact) {
              // Test SMS
              $sid = env('TWILIO_SID');
              $token = env('TWILIO_TOKEN');
              $client = new Client($sid, $token);

              $client->messages->create(
              $contact->mobile_number, [
              'from' => env('TWILIO_FROM'),
              'body' => "Hello from twilio...",]
              );

              $name = $contact->name;
              // Test Emails
              Mail::to($contact->email)->send(new SendMailable($name));
              }


              // test SendEmail Job
              foreach ($data as $contact) {
              $email_type = NotificationType::where('slug', NotificationType::EMAIL)->first();
              $log = new NotificationLog();
              $log->type_id = $email_type->id;
              $log->contact_id = $contact->id;
              $log->status = NotificationLog::PENDING;
              $log->reason = "Creating email job...";
              $log->save();

              $job = (new SendEmail($contact, $log))->onQueue('emails');
              $this->dispatch($job);
              }
             * */
        }
        return view('contacts');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage. TODO: Validation for COntact
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $entries = $request->input('data');
        $entry = current($entries);
        DB::beginTransaction();
        try {
            $contact = new Contact();
            $contact->name = $entry['name'];
            $contact->province_id = $entry['province_id'];
            $contact->surname = $entry['surname'];
            $contact->mobile_number = $entry['mobile_number'];
            $contact->email = $entry['email'];
//$contact->active = $entry['active']; TODO: Add active indicator in case of unsubscriptions?
            (!$contact->save() ? DB::rollback() : DB::commit());
            return response()->json($this->processData($contact));
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json(['error' => $ex->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $entries = $request->input('data');
        $entry = current($entries);
        $entry_id = substr(key($entries), 4);
        $contact = Contact::find($entry_id);
        try {
            DB::beginTransaction();
            if ($entry['email'] != $contact->email) {
                $contact->email = $entry['email'];
            }
            $contact->name = $entry['name'];
            $contact->surname = $entry['surname'];
            $contact->mobile_number = $entry['mobile_number'];
            $contact->province_id = $entry['province_id'];
            if (!$contact->save()) {
                throw new Exception('Failed to update Contact details');
            }
            DB::commit();
            return response()->json(["data" => $this->processData($contact)]);
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json(['error' => $ex->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact) {
        DB::beginTransaction();
        try {
            if (!$contact->delete()) {
                throw new Exception('Failed to delete the selected Contact');
            }
            DB::commit();
            return response()->json([]);
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json(['error' => $ex->getMessage()]);
        }
    }

    /**
     * Send out notification email to client
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Request  $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function notify(Request $request, $id) {
        $contact = Contact::find($id);
        DB::beginTransaction();
        try {
            $type = NotificationType::where('slug', ($request->notification_type ? NotificationType::SMS : NotificationType::EMAIL))->first();            
            $log = new NotificationLog();
            $log->type_id = $type->id;
            $log->contact_id = $contact->id;
            $log->status = NotificationLog::PENDING;
            $log->reason = "Creating job to send out notification...";
            $log->save();
            // Create random token for $contact
            $token = Hash::make($contact->id . $contact->name);
            $contact->auth_code = $token;
            $contact->save();            
            $job = ($request->notification_type ? (new SendSms($contact, $log)) : (new SendEmail($contact, $log))->onQueue('emails'));
            $this->dispatch($job);
            DB::commit();
            // Flash success message to user
             return back();
        } catch (Exception $ex) {
            DB::rollback();
            return back();
            // Flash error to user
        }
    }

    /*
     * Format data for sending to datatables 
     */

    private function processData($contact) {
        return [
            "DT_RowId" => "row_" . $contact->id,
            "id" => $contact->id,
            "province_id" => $contact->province_id,
            "province" => $contact->province,
            "name" => $contact->name,
            "surname" => $contact->surname,
            "email" => $contact->email,
            "mobile_number" => $contact->mobile_number,
            "opt_in" => $contact->opt_in,
            "opt_in_status" => ($contact->opt_in ? 'Yes' : 'No'),
        ];
    }

}
