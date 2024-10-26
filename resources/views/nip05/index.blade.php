<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIP-05 Identifier Generator</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-gray-300 min-h-screen flex items-center justify-center p-4">
    <div class="bg-gray-800 p-8 rounded-lg shadow-2xl w-full max-w-md border border-gray-700">
        <h1 class="text-3xl font-extrabold mb-6 text-center text-green-500">Create Your NIP-05 Identifier</h1>
        <form id="nip05-form" class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-400 mb-1">Your Identifier</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="text" id="name" name="name" required
                           class="block w-full pr-24 pl-3 py-2 bg-gray-700 border border-gray-600 rounded-md leading-5 text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm"
                           placeholder="yourname">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">@npublish.me</span>
                    </div>
                </div>
                <p class="mt-1 text-xs text-gray-500">This will be your NIP-05 identifier</p>
            </div>
            <div>
                <label for="public_key" class="block text-sm font-medium text-gray-400 mb-1">Your Nostr Public Key</label>
                <input type="text" id="public_key" name="public_key" required
                       class="block w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm"
                       placeholder="npub1...">
                <p class="mt-1 text-xs text-gray-500">Enter your Nostr public key (starts with npub1...)</p>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-400 mb-1">Email (optional)</label>
                <input type="email" id="email" name="email"
                       class="block w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm"
                       placeholder="your@email.com">
                <p class="mt-1 text-xs text-gray-500">Used for updates and deletion</p>
            </div>
            <div>
                <label for="passkey" class="block text-sm font-medium text-gray-400 mb-1">Passkey (optional)</label>
                <input type="password" id="passkey" name="passkey"
                       class="block w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm"
                       placeholder="••••••••">
                <p class="mt-1 text-xs text-gray-500">Set a passkey to manage your identifier later</p>
            </div>
            <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-black bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                Create NIP-05 Identifier
            </button>
        </form>
        <div id="result" class="mt-4 text-center text-sm font-medium"></div>
        <div class="mt-6 text-center">
            <a href="/nip05/manage" class="text-green-500 hover:text-green-400 text-sm">Manage existing identifier</a>
        </div>
    </div>

    <script>
        document.getElementById('nip05-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            try {
                const response = await fetch('/nip05', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const result = await response.json();
                const resultElement = document.getElementById('result');
                resultElement.textContent = result.message;
                resultElement.className = response.ok
                    ? 'mt-4 text-center text-sm font-medium text-green-600'
                    : 'mt-4 text-center text-sm font-medium text-red-600';
                if (response.ok) {
                    form.reset();
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('result').textContent = 'An error occurred. Please try again.';
                document.getElementById('result').className = 'mt-4 text-center text-sm font-medium text-red-600';
            }
        });
    </script>
</body>
</html>
