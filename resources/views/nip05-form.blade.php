<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register NIP-05 Identifier</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold mb-4 text-center">Register Your NIP-05 Identifier</h1>

            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('nip05.submit') }}">
                @csrf

                <div class="mb-4">
                    <label for="local_part" class="block text-gray-700">Identifier</label>
                    <input type="text" name="local_part" id="local_part" value="{{ old('local_part') }}" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
                    <small class="text-gray-500">Allowed characters: letters, numbers, dashes, underscores.</small>
                </div>

                <div class="mb-4">
                    <label for="pubkey" class="block text-gray-700">Public Key</label>
                    <input type="text" name="pubkey" id="pubkey" value="{{ old('pubkey') }}" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
                    <small class="text-gray-500">64-character hexadecimal string.</small>
                </div>

                <!-- CAPTCHA Placeholder -->
                <div class="mb-4">
                    <!-- CAPTCHA widget will go here -->
                    {!! NoCaptcha::renderJs() !!}
                    {!! NoCaptcha::display() !!}
                    @error('g-recaptcha-response')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Register</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
