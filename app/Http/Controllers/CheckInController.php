<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCheckInRequest;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckInController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('last_name')->orderBy('first_name')->paginate(25);
        return view('checkin.index', compact('clients'));
    }

    public function update(UpdateCheckInRequest $request, Client $client)
    {
        $validated = $request->validated();

        $updateData = array_filter($validated, function ($value) {
            return $value !== null && $value !== '';
        });

        DB::transaction(function () use ($client, $updateData) {
            $client->update($updateData);
        });

        Log::info('Check-in updated', ['client_id' => $client->id, 'user_id' => Auth::id()]);

        return redirect()->route('checkin.index')->with('success', 'Check-in data updated successfully.');
    }
}
