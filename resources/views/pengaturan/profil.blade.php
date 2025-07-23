@extends('layouts.admin_app')

@section('page_title', 'Pengaturan Akun')

@section('content')
<div class="container">
    <div class="card shadow-sm p-4">
        <h4 class="mb-4">Ganti Email & Password</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('pengaturan.updateProfile') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Password Baru (opsional)</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="mb-3">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <button class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
