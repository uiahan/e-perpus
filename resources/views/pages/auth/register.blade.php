@extends('layouts.main-layout')
@section('title', 'Daftar Akun')
@section('content')
    <div class="min-h-screen flex justify-center items-center py-10">
        <div class="bg-[#19191c] rounded-lg px-10 py-10 w-[35rem]">
            <div>
                <h1 class="font-semibold text-2xl text-center mb-3">E-Perpus</h1>
                <h1 class="text-2xl font-bold text-center">Daftar</h1>
            </div>
            <div class="mt-7">
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div>
                        <label for="">Nama Lengkap</label>
                        <input type="text" name="name"
                            class="w-full border border-gray-600 px-3 rounded-lg mt-1 py-2 bg-[#232326]">
                    </div>
                    <div class="mt-6">
                        <label for="">Email</label>
                        <input type="text" name="email"
                            class="w-full border border-gray-600 px-3 rounded-lg mt-1 py-2 bg-[#232326]">
                    </div>
                    <div class="mt-6">
                        <label for="">Nomor HP</label>
                        <input type="text" name="phone"
                            class="w-full border border-gray-600 px-3 rounded-lg mt-1 py-2 bg-[#232326]">
                    </div>
                    <div class="mt-6">
                        <label for="">Jenis Kelamin</label>
                        <select name="gender"
                            class="w-full border border-gray-600 px-3 rounded-lg mt-1 py-2 bg-[#232326] text-white">
                            <option value="" disabled selected>Pilih Gender</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="mt-6">
                        <label for="">Pekerjaan</label>
                        <input type="text" name="profession"
                            class="w-full border border-gray-600 px-3 rounded-lg mt-1 py-2 bg-[#232326]">
                    </div>
                    <div class="mt-6">
                        <label for="">Alamat</label>
                        <textarea name="address" rows="3" class="w-full border border-gray-600 px-3 rounded-lg mt-1 py-2 bg-[#232326]"></textarea>
                    </div>
                    <div class="mt-6">
                        <label for="">Password</label>
                        <input type="password" name="password"
                            class="w-full border border-gray-600 px-3 rounded-lg mt-1 py-2 bg-[#232326]">
                    </div>
                    <div class="mt-6">
                        <label for="">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                            class="w-full border border-gray-600 px-3 rounded-lg mt-1 py-2 bg-[#232326]">
                    </div>
                    <div class="mt-6">
                        <button type="submit"
                            class="bg-yellow-500 hover:bg-yellow-300 transition-colors text-white w-full rounded-lg py-2 font-semibold">Daftar</button>
                    </div>
                    <div class="mt-2">
                        <small>Sudah punya akun? <a href="{{ route('show.login') }}"
                                class="text-yellow-500 hover:text-yellow-300 transition-colors">Login sekarang</a></small>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
