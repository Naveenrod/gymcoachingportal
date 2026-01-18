<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class CheckInController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('last_name')->orderBy('first_name')->get();
        return view('checkin.index', compact('clients'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'loom_link' => 'nullable|url|max:500',
            'package' => 'nullable|string|max:100',
            'check_in_frequency' => 'nullable|string|max:50',
            'check_in_day' => 'nullable|string|max:50',
            'submitted' => 'nullable|in:Submitted,',
            'rank' => 'nullable|string|max:50',
        ]);

        // Only update provided fields
        $updateData = array_filter($validated, function($value) {
            return $value !== null && $value !== '';
        });
        
        $client->update($updateData);

        return redirect()->route('checkin.index')->with('success', 'Check-in data updated successfully.');
    }
}
