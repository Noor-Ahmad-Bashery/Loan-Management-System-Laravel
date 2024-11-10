@extends('Layout.Layout')

@section('title', 'صفحه ثبت کاربر')

@section('content')

<div class="container mx-auto py-10">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold">ثبت کاربر</h2>
        <img id="profile_image_preview" class="max-w-full h-20 w-20 border rounded-full" style="display: none;">
    </div>
    <form action="{{ route('admin.postRegister') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-8">
        @csrf

        <div class="grid grid-rows-3 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="col-span-1 mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم کاربر</label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-span-1 mb-4">
                <label for="lastname" class="block text-gray-700 text-sm font-bold mb-2">تخلص</label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="lastname" name="lastname" value="{{ old('lastname') }}" required>
                @error('lastname')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-span-1 mb-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">شماره تماس</label>
                <input type="tel" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="phone" name="phone" value="{{ old('phone') }}" required>
                @error('phone')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-span-1 mb-4">
                <label for="address" class="block text-gray-700 text-sm font-bold mb-2">آدرس</label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="address" name="address" value="{{ old('address') }}" required>
                @error('address')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-span-1 mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">ایمیل</label>
                <input type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-span-1 mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">رمز</label>
                <input type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" required>
                @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-span-1 mb-4">
                <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">ضامن </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="user_id" name="user_id">
                    <option value="">انتخاب ضامن</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('user_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-span-1 mb-4">
                <label for="role" class="block text-gray-700 text-sm font-bold mb-2">سطح دسترسی</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="role" name="role" required>
                    <option value="">انتخاب سطح دسترسی</option>
                    @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-span-1 mb-4">
                <label for="age" class="block text-gray-700 text-sm font-bold mb-2">سن</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="age" name="age" required onchange="checkAge(this.value)">
                    <option value="">انتخاب سن</option>
                    @for ($i = 1; $i <= 100; $i++)
                    <option value="{{ $i }}" {{ old('age') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                @error('age')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div id="referenceContainer" class="col-span-1 mb-4" style="display: none;">
                <label for="age_reference" class="block text-gray-700 text-sm font-bold mb-2">ضامن</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="age_reference" name="age_reference">
                    <option value="">انتخاب ضامن</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('age_reference') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('age_reference')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-span-1 mb-4">
                <label for="national_id_image" class="block text-gray-700 text-sm font-bold mb-2">تصویر تذکره</label>
                <input type="file" class="block w-full text-gray-700 py-2 px-3 border rounded" id="national_id_image" name="national_id_image" accept="image/jpg image/png" required onchange="previewNationalIdImage(event)">
                @error('national_id_image')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-span-1 mb-4">
                <label for="profile_image" class="block text-gray-700 text-sm font-bold mb-2">تصویر پروفایل</label>
                <input type="file" class="block w-full text-gray-700 py-2 px-3 border rounded" id="profile_image" name="profile_image" accept="image/jpg image/png" required onchange="previewProfileImage(event)">
                @error('profile_image')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-span-1 mb-4 flex items-center justify-center">
                <img id="national_id_image_preview" class="max-w-full h-40 w-40 border rounded-full" style="display: none;">
            </div>
        </div>

        <div class="flex items-center justify-center mt-6">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">ثبت کاربر</button>
        </div>
    </form>
</div>

<script>
    function checkAge(age) {
        const referenceContainer = document.getElementById('referenceContainer');
        if (age < 15) {
            referenceContainer.style.display = 'block';
        } else {
            referenceContainer.style.display = 'none';
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

    document.addEventListener('DOMContentLoaded', function() {
        checkAge(document.getElementById('age').value);
    });
</script>
@endsection
