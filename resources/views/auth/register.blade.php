@extends('layout.app')
@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Register</h2>
    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
        @csrf
        <input type="text" name="name" placeholder="Name" class="w-full border p-2 rounded" required>
        <input type="email" name="email" placeholder="Email" class="w-full border p-2 rounded" required>
        <input type="text" name="telephone" placeholder="Telephone" class="w-full border p-2 rounded" required>
        <input type="date" name="birthday" class="w-full border p-2 rounded" required>
        <input type="file" name="profile_photo" class="w-full border p-2 rounded" required>
        <input type="password" name="password" placeholder="Password" class="w-full border p-2 rounded" required>
        <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full border p-2 rounded" required>
        <button class="w-full bg-blue-600 text-white py-2 rounded">Register</button>
    </form>
</div>
@endsection