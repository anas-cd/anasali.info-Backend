<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\contact\SendEmailRequest;
use App\Http\Resources\v1\ContactResource;
use App\Http\Resources\v1\ContactsCollection;
use App\Jobs\SendFormMessageJob;
use App\Models\Contact;
use App\Http\Requests\v1\contact\StoreContactRequest;
use App\Http\Requests\v1\contact\UpdateContactRequest;
use App\Traits\APIResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Log;
use Request;

class ContactController extends Controller
{
    use APIResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        // --- validated request ---
        $validated = $request->validated();

        // --- saving in db ---
        $contact = Contact::create([
            "user_id" => Auth::user()->id,
            "contactMethod" => $validated["contactMethod"],
            "type" => $validated["type"]

        ]);

        // --- logging ---
        Log::stack(['single', 'devLog', 'activityLog'])
            ->debug(
                'contact record created',
                [
                    'user-id' => Auth::user()->id,
                    'record-id' => $contact->id
                ]
            );

        // --- returning new eduation data ---
        return $this->success(ContactResource::make($contact), 200, 'contact record successfully created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function showAll(Request $request)
    {
        /**
         * NOTE: this is not linked to any user since there is only one user.
         */
        $contact = Contact::get();

        // --- logging ---
        Log::stack(['single', 'devLog', 'activityLog'])
            ->debug(
                'contact info fetched'
            );

        return $this->success(new ContactsCollection($contact), 200, "success");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, Contact $contact, string $id)
    {
        // --- validated request ---
        $validated = $request->validated();

        // --- look up contact record of user with specified major ---
        $contact = Contact::where("id", $id)->first();

        // --- update record ---
        $contact->update($validated);

        // --- logging ---
        Log::stack(['single', 'devLog', 'activityLog'])
            ->debug(
                'contact record updated',
                [
                    'user-id' => Auth::user()->id,
                    'record-id' => $contact->id,
                    'columns-updated' => array_keys($validated)
                ]
            );

        return $this->success(ContactResource::make($contact), 200, "contact record updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        //
    }

    /**
     * Sending Client Message
     */
    public function sendEmail(SendEmailRequest $request)
    {
        // --- validated request ---
        $validated = $request->validated();



        // --- logging ---
        // -- logging short format data --
        Log::stack(['single', 'devLog', 'activityLog', 'appLog'])
            ->debug(
                'mailable job dispatched to `emails` queue',
                [
                    'email' => $validated['clientEmail']
                ]
            );

        // -- logging long format data (with message) --
        Log::channel('mailablesLog')
            ->debug(
                'mailable job dispatched to `emails` queue',
                [
                    'email' => $validated['clientEmail'],
                    'message' => $validated['message']
                ]
            );

        // -- preparing context for presistance --
        $context = ['email' => $validated['clientEmail']];

        // --- dispatching job send email ---
        SendFormMessageJob::dispatch($validated, $context);
        return response()->json(['message' => 'Email sent successfully']);
    }
}
