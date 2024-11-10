@extends('Layout.Layout')

@section('title', 'ویرایش کاربر')

@section('content')
<div class="container mx-auto py-10">
    <h2 class="text-2xl font-semibold mb-6 text-center">ویرایش کاربر</h2>

    @if ($errors->any())
        <div class="mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="text-red-500">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.updateUser', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4 text-center">
            <label for="profile_image" class="block text-gray-700">عکس پروفایل:</label>
            @if ($user->profile_image)
            <img id="profile_image_preview" src="{{ asset('storage/' . $user->profile_image) }}" class="mt-2 max-w-full h-40 w-40 border rounded-full mx-auto">
            @else
            <img id="profile_image_preview" class="mt-2 max-w-full h-40 w-40 border rounded-full mx-auto" style="display: none;">
            @endif
            <input type="file" name="profile_image" id="profile_image" class="border rounded py-2 px-4 w-auto mx-auto" onchange="previewProfileImage(event)">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label for="name" class="block text-gray-700">نام:</label>
                <input type="text" name="name" id="name" value="{{ $user->name }}" class="border rounded py-2 px-4 w-full">
            </div>

            <div class="mb-4">
                <label for="lastname" class="block text-gray-700">نام خانوادگی:</label>
                <input type="text" name="lastname" id="lastname" value="{{ $user->lastname }}" class="border rounded py-2 px-4 w-full">
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-gray-700">شماره تلفن:</label>
                <input type="text" name="phone" id="phone" value="{{ $user->phone }}" class="border rounded py-2 px-4 w-full">
            </div>

            <div class="mb-4">
                <label for="address" class="block text-gray-700">آدرس:</label>
                <input type="text" name="address" id="address" value="{{ $user->address }}" class="border rounded py-2 px-4 w-full">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700">ایمیل:</label>
                <input type="email" name="email" id="email" value="{{ $user->email }}" class="border rounded py-2 px-4 w-full">
            </div>

            <div class="mb-4">
                <label for="role" class="block text-gray-700">نقش:</label>
                <select name="role" id="role" class="border rounded py-2 px-4 w-full">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="age" class="block text-gray-700">سن:</label>
                <input type="number" name="age" id="age" value="{{ $user->age }}" class="border rounded py-2 px-4 w-full">
            </div>
        </div>

        <div class="mb-4 text-center">
            <label for="national_id_image" class="block text-gray-700">عکس کارت ملی:</label>
            @if ($user->national_id_image)
            <img id="national_id_image_preview" src="{{ asset('storage/' . $user->national_id_image) }}" class="mt-2 max-w-full h-40 w-40 border rounded-full mx-auto">
            @else
            <img id="national_id_image_preview" class="mt-2 max-w-full h-40 w-40 border rounded-full mx-auto" style="display: none;">
            @endif
            <input type="file" name="national_id_image" id="national_id_image" class="border rounded py-2 px-4 w-auto mx-auto" onchange="previewNationalIdImage(event)">
        </div>

        <div class="flex justify-center mt-6">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded-full shadow-lg transform transition-transform duration-200 hover:scale-105">بروزرسانی</button>
        </div>
    </form>
</div>

<script>
    function previewNationalIdImage(event) {
        const input = event.target;
        const reader = new FileReader();

        reader.onload = function() {
            const image = document.getElementById('national_id_image_preview');
            image.src = reader.result;
            image.style.display = 'block';
        };

        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewProfileImage(event) {
        const input = event.target;
        const reader = new FileReader();

        reader.onload = function() {
            const image = document.getElementById('profile_image_preview');
            image.src = reader.result;
            image.style.display = 'block';
        };

        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
