@extends('layout.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-gray-800"><i class="fa-regular fa-bell text-indigo-600 mr-2"></i> My Notifications</h2>

    @if($notifications->isEmpty())
        <div class="text-center py-10 text-gray-500">
            You have no notifications.
        </div>
    @else
        <div class="space-y-3">
            @foreach($notifications as $notif)
            
            @php
                $borderColor = 'border-gray-300';
                $icon = 'fa-info-circle';
                $textColor = 'text-gray-800';
                
                if(str_contains($notif->message, 'Success')) {
                    $borderColor = 'border-green-500';
                    $icon = 'fa-circle-check text-green-500';
                } elseif(str_contains($notif->message, 'Rejected')) {
                    $borderColor = 'border-red-500';
                    $icon = 'fa-circle-xmark text-red-500';
                } elseif(str_contains($notif->message, 'expires')) {
                    $borderColor = 'border-yellow-500';
                    $icon = 'fa-triangle-exclamation text-yellow-500';
                }
            @endphp

            <div class="bg-white p-5 rounded-lg shadow-sm border-l-4 {{ $borderColor }} flex items-start">
                <div class="mr-4 mt-1">
                    <i class="fa-solid {{ $icon }} text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="{{ $textColor }} font-medium">{{ $notif->message }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>

                    @if($notif->type == 'package_expiry' && isset($notif->data['user_package_id']))
                        <form action="{{ route('user.renew_package', $notif->data['user_package_id']) }}" method="POST" class="mt-3">
                            @csrf
                            <button class="bg-indigo-600 text-white px-4 py-1.5 rounded text-sm font-semibold hover:bg-indigo-700 transition">
                                Active Again?
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection