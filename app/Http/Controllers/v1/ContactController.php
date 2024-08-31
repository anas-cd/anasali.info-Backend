<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\contact\SendEmailRequest;
use App\Http\Resources\v1\ContactResource;
use App\Http\Resources\v1\ContactsCollection;
use App\Mail\NewClientMessage;
use App\Models\Contact;
use App\Http\Requests\v1\contact\StoreContactRequest;
use App\Http\Requests\v1\contact\UpdateContactRequest;
use App\Traits\APIResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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

        // --- sending email ---
        Mail::to('anas.cd.97@gmail.com')->queue(new NewClientMessage($validated));

        return response()->json(['message' => 'Email sent successfully']);
    }
}
