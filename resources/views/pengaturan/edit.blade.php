@extends('layouts.admin_app')

@section('page_title', 'Edit Pengguna')

@section('content')
<div class="container">
    <div class="card shadow-sm p-4">
        <h4 class="mb-4">Edit Data Pengguna</h4>

        <form action="{{ route('pengaturan.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Password Baru</label>
                <input type="password" name="password" class="form-control">
                <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
            </div>

            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
