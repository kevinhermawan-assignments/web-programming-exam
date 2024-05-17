<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Interaction;
use Illuminate\Http\Request;

class InteractionController extends Controller
{
    public function store(Request $request, Contact $contact)
    {
        $validatedData = $request->validate([
            'type' => 'required|string',
            'notes' => 'required|string',
        ]);

        $contact->interactions()->create($validatedData);

        return redirect()->route('contacts.show', $contact);
    }

    public function destroy(Contact $contact, Interaction $interaction)
    {
        $interaction->delete();

        return redirect()->route('contacts.show', $contact);
    }
}
