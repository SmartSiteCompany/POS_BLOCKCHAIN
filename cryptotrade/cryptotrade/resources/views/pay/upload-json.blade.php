<!-- resources/views/upload-json.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Cargar archivo JSON de transacciones</h2>
    <form action="{{ route('transactions.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <input type="file" name="json_file" accept=".json" required class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Cargar</button>
    </form>
</div>
@endsection
