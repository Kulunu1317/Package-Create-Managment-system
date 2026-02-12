@extends('layout.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">Admin Panel - Requests</h2>

    @if($notifications->isEmpty())
        <p class="text-gray-500">No pending requests.</p>
    @else
        <div class="space-y-4">
            @foreach($notifications as $notif)
            <div class="bg-white p-6 rounded shadow flex justify-between items-center">
                <div>
                    <p class="font-bold">{{ $notif->message }}</p>
                    <p class="text-sm text-gray-500">{{ $notif->created_at->diffForHumans() }}</p>
                </div>

                <div class="flex space-x-2">
                    @if($notif->type == 'ad_extension_request' && isset($notif->data['ad_id']))
                        
                        <form action="{{ route('admin.approve_extension', $notif->data['ad_id']) }}" method="POST">
                            @csrf
                            <button class="bg-blue-600 text-white px-4 py-2 rounded">Approve</button>
                        </form>

                        <form action="{{ route('admin.reject_extension', $notif->data['ad_id']) }}" method="POST">
                            @csrf
                            <button class="bg-red-500 text-white px-4 py-2 rounded">Reject</button>
                        </form>

                    @elseif($notif->type == 'renewal_request' && isset($notif->data['user_package_id']))
                        <form action="{{ route('admin.approve_renewal', $notif->data['user_package_id']) }}" method="POST">
                            @csrf
                            <button class="bg-green-600 text-white px-4 py-2 rounded">Approve Renewal</button>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection