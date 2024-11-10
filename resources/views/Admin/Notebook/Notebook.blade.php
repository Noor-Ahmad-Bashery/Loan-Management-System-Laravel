@extends('Layout.Layout')

@section('title', 'کتابچه')

@section('content')
<div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-6 text-center">ثبت پول کتابچه</h1>

    <form action="{{ route('admin.notebook') }}" method="POST" onsubmit="disableSubmitButton(event)">
        @csrf

        <!-- Input for number -->
        <div class="mb-4">
            <label for="number" class="block text-gray-700 font-semibold mb-2">مقدار پول کتابچه:</label>
            <input type="number" id="number" name="number" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            @error('number')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-span-1 mb-4">
            <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">انتخاب کاربر</label>
            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="user_id" name="user_id">
                <option value="">انتخاب کاربر</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} || {{$user->lastname}}</option>
                @endforeach
            </select>
            @error('user_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Submit button -->
        <div class="mt-6">
            <button id="submit-button" type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                ثبت مقدار
            </button>
        </div>
    </form>
</div>

<script>
    function disableSubmitButton(event) {
        event.preventDefault(); // Prevent the default form submission
        const submitButton = document.getElementById('submit-button');
        submitButton.disabled = true;
        submitButton.innerText = 'در حال ارسال...'; // Optional: Change button text to indicate the form is being submitted
        event.target.submit(); // Submit the form after disabling the button
    }
</script>
@endsection
