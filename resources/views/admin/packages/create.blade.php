@extends('layout.app')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Create New Package</h2>
    <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <input type="text" name="name" placeholder="Package Name" class="w-full border p-2 rounded" required>
        <input type="number" name="price" placeholder="Price" class="w-full border p-2 rounded" required>
        <input type="number" name="ad_limit" placeholder="Max Ads Allowed" class="w-full border p-2 rounded" required>
        <input type="number" name="validity_days" placeholder="Validity (Days)" class="w-full border p-2 rounded" required>
        <textarea name="description" placeholder="Description" class="w-full border p-2 rounded" required></textarea>
        
        <div>
            <label class="block text-sm text-gray-600">Package Image</label>
            <input type="file" name="image" class="w-full border p-2 rounded" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Create Package</button>
    </form>
</div>
@endsection