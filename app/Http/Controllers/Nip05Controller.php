<?php

namespace App\Http\Controllers;

use App\Models\Nip05Identifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Nip05Controller extends Controller
{
    public function index()
    {
        return view('nip05.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:nip05_identifiers',
            'public_key' => 'required|string|starts_with:npub1',
            'email' => 'required|email',
            'passkey' => 'required|string',
        ]);

        try {
            $hexPublicKey = $this->npubToHex($request->public_key);

            Nip05Identifier::create([
                'name' => $request->name,
                'public_key' => $hexPublicKey,
                'email' => $request->email,
                'passkey' => $request->passkey,
            ]);

            return response()->json(['message' => 'NIP-05 identifier created successfully'], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    private function npubToHex($npub)
    {
        if (!str_starts_with($npub, 'npub1')) {
            throw new \InvalidArgumentException("Invalid npub format: must start with 'npub1'");
        }

        $npub = substr($npub, 5); // Remove 'npub1'
        $decoded = $this->bech32Decode($npub);

        if (false === $decoded) {
            throw new \InvalidArgumentException('Invalid npub format: could not decode');
        }

        return bin2hex($decoded);
    }

    private function bech32Decode($string)
    {
        $alphabet = 'qpzry9x8gf2tvdw0s3jn54khce6mua7l';
        $data = [];

        for ($i = 0; $i < strlen($string); ++$i) {
            $chr = $string[$i];
            $pos = strpos($alphabet, $chr);
            if (false === $pos) {
                return false;
            }
            $data[] = $pos;
        }

        $decoded = '';
        for ($i = 0; $i < count($data); $i += 8) {
            $chunk = array_slice($data, $i, 8);
            $value = 0;
            foreach ($chunk as $bit) {
                $value = ($value << 5) | $bit;
            }
            $decoded .= pack('C*',
                ($value >> 32) & 0xFF,
                ($value >> 24) & 0xFF,
                ($value >> 16) & 0xFF,
                ($value >> 8) & 0xFF,
                $value & 0xFF
            );
        }

        return substr($decoded, 0, -6); // Remove the last 6 bytes (checksum)
    }

    public function showManageForm()
    {
        return view('nip05.manage');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|alpha_dash',
            'email' => 'required|email',
            'passkey' => 'required',
            'new_public_key' => 'nullable|starts_with:npub1|size:63',
        ]);

        $identifier = Nip05Identifier::where('name', $request->name)
                                     ->where('email', $request->email)
                                     ->first();

        if (!$identifier || !Hash::check($request->passkey, $identifier->passkey)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($request->new_public_key) {
            $identifier->public_key = $this->npubToHex($request->new_public_key);
            $identifier->save();

            return response()->json(['message' => 'NIP-05 identifier updated successfully']);
        }

        return response()->json(['message' => 'No changes were made']);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'name' => 'required|alpha_dash',
            'email' => 'required|email',
            'passkey' => 'required',
        ]);

        $identifier = Nip05Identifier::where('name', $request->name)
                                     ->where('email', $request->email)
                                     ->first();

        if (!$identifier || !Hash::check($request->passkey, $identifier->passkey)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $identifier->delete();

        return response()->json(['message' => 'NIP-05 identifier deleted successfully']);
    }

    public function serveNostrJson(Request $request)
    {
        $name = $request->query('name');
        $names = [];

        if ($name) {
            $identifier = Nip05Identifier::where('name', $name)->first();
            if ($identifier) {
                $names[$name] = $identifier->public_key;
            }
        }

        return response()->json([
            'names' => $names,
        ], 200);
    }
}
