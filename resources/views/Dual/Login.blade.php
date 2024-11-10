@extends('Layout.Layout')

@section('title', 'Login')

@section('content')
<div>
<center>
    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-900 text-center">Login</h2>
        <!-- Add CSRF Token -->
        <form action="{{ route('login.post') }}" method="POST" onsubmit="disableSubmitButton()">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="text" name="email" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" name="password" required>
            </div>
            <div class="flex items-center justify-between">
                <button id="submit-button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Login
                </button>
         
            </div>
        </form>
    </div>
</center>

<script>
    function disableSubmitButton() {
        const submitButton = document.getElementById('submit-button');
        submitButton.disabled = true;
        submitButton.innerText = 'در حال ارسال...'; // Optional: Change button text to indicate the form is being submitted
    }
</script>
    </div>
@endsection
