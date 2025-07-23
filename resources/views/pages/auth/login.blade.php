@extends('layouts.main-layout')
@section('title', 'Masuk')
    @section('content')
        <div class="min-h-screen flex justify-center items-center">
            <div class="bg-[#19191c] rounded-lg px-10 py-10 w-[35rem]">
                <div>
                    <h1 class="font-semibold text-2xl text-center mb-3">E-Perpus</h1>
                    <h1 class="text-2xl font-bold text-center">Masuk</h1>
                </div>
                <div class="mt-7">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mt-6">
                            <label for="">Email</label>
                            <input type="text" name="email" class="w-full border border-gray-600 px-3 rounded-lg mt-1 py-2 bg-[#232326]">
                        </div>
                        <div class="mt-6">
                            <label for="">Password</label>
                            <input type="text" name="password" class="w-full border border-gray-600 px-3 rounded-lg mt-1 py-2 bg-[#232326]">
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-300 transition-colors text-white w-full rounded-lg py-2 font-semibold">Masuk</button>
                        </div>
                        <div class="mt-2">
                            <small>Belum punya akun? <a href="{{ route('show.register') }}" class="text-yellow-500 hover:text-yellow-300 transition-colors">Daftar sekarang</a></small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection