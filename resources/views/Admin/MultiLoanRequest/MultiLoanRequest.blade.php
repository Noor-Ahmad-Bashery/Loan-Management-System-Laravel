@extends('Layout.Layout')

@section('title', 'اخذ قرضه')

@section('content')

<div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-6 text-center">اخذ قرضه</h1>
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">خطا!</strong>
            <span class="block sm:inline">{{ $errors->first() }}</span>
        </div>
    @endif
    <form action="{{ route('admin.postMultiLoanRequest') }}" method="POST" onsubmit="disableSubmitButton()">
        @csrf

        <div class="mb-4">
            <label for="number" class="block text-gray-700 font-semibold mb-2">مقدار اخذ:</label>
            <input type="number" id="number" name="number" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            @error('number')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        @foreach (range(1, 3) as $index)
        <div class="col-span-1 mb-4">
            <label for="user_id_{{ $index }}" class="block text-gray-700 text-sm font-bold mb-2">انتخاب قرض-- گیرنده {{ $index }}</label>
            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="user_id_{{ $index }}" name="user_id_{{ $index }}">
                <option value="">انتخاب قرض-- گیرنده {{ $index }}</option>
                @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }} || {{ $user->lastname }}</option>
                @endforeach
            </select>
            @error('user_id_{{ $index }}')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        @endforeach

        <div class="mt-6">
            <button type="submit" id="submit-button" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                ثبت مقدار
            </button>
        </div>
    </form>
</div>


<script>
    function disableSubmitButton() {
        const submitButton = document.getElementById('submit-button');
        submitButton.disabled = true;
        submitButton.innerText = 'در حال ارسال...'; // Optional: Change button text to indicate the form is being submitted
    }
</script>
@endsection