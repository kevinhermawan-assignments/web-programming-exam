<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->get();

        return view('contacts.index', [
            'contacts' => $contacts,
        ]);
    }

    public function show(Contact $contact)
    {
        return view('contacts.show', [
            'contact' => $contact,
            'interactions' => $contact->interactions()->orderBy('created_at', 'desc')->get(),
            'sales' => $contact->sales()->orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'company' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
        ]);

        Contact::create($request->all());

        return redirect()->route('contacts.index');
    }

    public function update(Request $request, Contact $contact)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'company' => 'required|string',
            'email' => 'required|string|email|unique:contacts,email,' . $contact->id,
            'phone' => 'required|string',
        ]);

        $contact->update($validatedData);

        return redirect()->route('contacts.index');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('contacts.index');
    }
}
