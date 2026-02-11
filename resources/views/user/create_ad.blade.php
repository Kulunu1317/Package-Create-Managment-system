@extends('layout.app')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Post Ad for {{ $userPackage->package->name }}</h2>
    <form action="{{ route('user.store_ad') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <input type="hidden" name="user_package_id" value="{{ $userPackage->id }}">
        
        <input type="text" name="job_name" placeholder="Job Name" class="w-full border p-2 rounded" required>
        <select name="job_type" class="w-full border p-2 rounded" required>
            <option value="Full Time">Full Time</option>
            <option value="Part Time">Part Time</option>
        </select>
        <input type="text" name="salary" placeholder="Salary" class="w-full border p-2 rounded" required>
        <textarea name="description" placeholder="Description" class="w-full border p-2 rounded h-24" required></textarea>
        
        <div>
            <label class="block text-sm text-gray-600">Company Logo</label>
            <input type="file" name="company_logo" class="w-full border p-2 rounded" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">OK</button>
    </form>
</div>
@endsection