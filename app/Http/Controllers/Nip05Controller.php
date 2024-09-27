<?php

namespace App\Http\Controllers;

use App\Models\Nip05Mapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Nip05Controller extends Controller
{
    public function showForm()
    {
        return view('nip05-form');
    }

    public function submitForm(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'local_part' => 'required|alpha_dash|unique:nip05_mappings,local_part',
            'pubkey' => 'required|size:64|regex:/^[a-f0-9]+$/i|unique:nip05_mappings,pubkey',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        if ($validator->fails()) {
            return redirect()->route('nip05.form')
                ->withErrors($validator)
                ->withInput();
        }

        // Store the mapping
        Nip05Mapping::create([
            'local_part' => strtolower($request->input('local_part')),
            'pubkey' => strtolower($request->input('pubkey')),
        ]);

        return redirect()->route('nip05.form')->with('success', 'Your NIP-05 identifier has been registered!');
    }

    public function nip05Endpoint(Request $request)
    {
        $name = strtolower($request->query('name'));

        if (!$name) {
            return response()->json(['error' => 'Name parameter is required.'], 400);
        }

        $mapping = Nip05Mapping::where('local_part', $name)->first();

        if (!$mapping) {
            // Return an empty 'names' object as per NIP-05 when not found
            return response()->json(['names' => (object) []], 200, [
                'Content-Type' => 'application/json',
                'Access-Control-Allow-Origin' => '*',
            ]);
        }

        $response = [
            'names' => [
                $mapping->local_part => $mapping->pubkey,
            ],
        ];

        return response()->json($response, 200, [
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
        ]);
    }
}
