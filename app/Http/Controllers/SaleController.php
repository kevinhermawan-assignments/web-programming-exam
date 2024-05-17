<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function store(Request $request, Contact $contact)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric'
        ]);

        $contact->sales()->create($validatedData);

        return redirect()->route('contacts.show', $contact);
    }

    public function destroy(Contact $contact, Sale $sale)
    {
        $sale->delete();

        return redirect()->route('contacts.show', $contact);
    }
}
