@extends('layouts.admin_app')

@section('page_title', 'Daftar Pengguna')

@section('content')
<div class="container">
    <div class="card shadow-sm p-4">
        <h4 class="mb-4">Daftar Pengguna</h4>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <a href="{{ route('pengaturan.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>

                            <form action="{{ route('pengaturan.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <a href="{{ route('pengaturan.create') }}" class="btn btn-success mb-3" style="width: fit-content;">+ Tambah Pengguna</a>

        </table>
    </div>
</div>
@endsection
