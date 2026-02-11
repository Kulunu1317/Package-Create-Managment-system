@extends('layout.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg mt-10">
    <div class="flex flex-col items-center">
        <div class="relative w-32 h-32 mb-4">
            @if($user->profile_photo)
                <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                     alt="Profile Photo" 
                     class="w-32 h-32 rounded-full object-cover border-4 border-blue-500 shadow-sm">
            @else
                <div class="w-32 h-32 rounded-full bg-gray-300 flex items-center justify-center text-gray-500">
                    No Photo
                </div>
            @endif
        </div>

        <h2 class="text-3xl font-bold text-gray-800">{{ $user->name }}</h2>
        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full mt-2">
            {{ ucfirst($user->role) }}
        </span>
    </div>

    <div class="mt-8 border-t pt-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm text-gray-500">Email</label>
                <p class="font-semibold text-gray-800">{{ $user->email }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Telephone</label>
                <p class="font-semibold text-gray-800">{{ $user->telephone }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Birthday</label>
                <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($user->birthday)->format('d M Y') }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Account Created</label>
                <p class="font-semibold text-gray-800">{{ $user->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-center">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded shadow hover:bg-red-600 transition">
                Logout
            </button>
        </form>
    </div>
</div>
@endsection