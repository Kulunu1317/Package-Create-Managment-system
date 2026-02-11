@extends('layout.app')

@section('content')
<div class="container mx-auto">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Pending Advertisements</h2>

    @if($ads->isEmpty())
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4">
            <p>No pending advertisements to review.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($ads as $ad)
            <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
                
                <div class="flex items-center mb-4">
                    <img src="{{ asset('storage/' . $ad->company_logo) }}" 
                         alt="Logo" 
                         class="w-14 h-14 rounded-full border mr-3 object-cover">
                    <div>
                        <h3 class="font-bold text-lg">{{ $ad->job_name }}</h3>
                        <span class="text-xs bg-gray-200 px-2 py-1 rounded text-gray-700">{{ $ad->job_type }}</span>
                    </div>
                </div>

                <div class="space-y-2 mb-4">
                    <p class="text-gray-600 text-sm line-clamp-3">{{ $ad->description }}</p>
                    <p class="text-green-600 font-semibold">Salary: {{ $ad->salary }}</p>
                    <p class="text-xs text-gray-500">Posted by: {{ $ad->user->name }} ({{ $ad->user->email }})</p>
                    <p class="text-xs text-gray-400">Date: {{ $ad->created_at->format('d M Y, h:i A') }}</p>
                </div>

                <div class="flex space-x-2 mt-4 pt-4 border-t">
                    <form action="{{ route('admin.ads.approve', $ad->id) }}" method="POST" class="w-1/2">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600 transition">
                            Approve
                        </button>
                    </form>

                    <form action="{{ route('admin.ads.reject', $ad->id) }}" method="POST" class="w-1/2">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600 transition">
                            Reject
                        </button>
                    </form>
                </div>

            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection