<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contact.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required',
            'message' => 'required',
        ]);

        $data['email'] = $request->input('email');
        $data['message'] = $request->input('message');

        // If you have a model, you can use it to create a new record
        Contact::create($data);

        // return view('home.contact');
        return redirect()->route('home')->with('message', 'Contact successfully added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        $contacts=Contact::all();
        return view('admin.contactshow',['contacts'=>$contacts]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact, $id)
    {
        $contact=Contact::find($id);
        $contact->delete();
        return redirect('/admin/contact/show');
    }

    /**
 * Update the status of the contact with the specified ID to complete.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function updateStatus($id)
{
    // Find the contact by ID
    $contact = Contact::find($id);

    if (!$contact) {
        return redirect()->route('contacts.show', $id)
            ->with('error', 'Contact not found');
    }

    // Update the status to 'complete'
    $contact->status = 'complete';
    $contact->save();

    return redirect()->route('contact.show', $id)
        ->with('success', 'Contact status updated to complete');
}

}
