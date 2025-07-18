@extends('layouts.main-layout')
@section('title', 'Beranda')
@section('content')
    @include('components.navbar')
    <div class="flex justify-between xl:px-60 px-4 min-h-[85vh] items-center">
        <div>
            <img class="w-[30rem]" src="{{ asset('images/book-lover-25.svg') }}" alt="">
        </div>
        <div>
            <h1 class="font-bold text-6xl text-white">Temukan <span class="text-yellow-400">Buku</span></h1>
            <h1 class="font-bold text-6xl text-white"><span class="text-yellow-400">Favoritmu</span> Di</h1>
            <h1 class="font-bold text-6xl text-white">Perpustakaan <span class="text-yellow-400">Kami</span></h1>
        </div>
    </div>
    <div class="xl:px-60 px-4 py-40 bg-neutral-900">
        <h1 class="text-white text-5xl font-bold">Koleksi Buku Kami</h1>
        <div class="grid grid-cols-3 mt-10">
            @foreach ($books as $item)
                <div class="card bg-base-100 w-96 shadow-sm">
                    <figure>
                        <img src="{{ asset('storage/' . $item->cover) }}" class="h-72 object-cover w-full"
                            alt={{ $item->title }} />
                    </figure>
                    <div class="card-body bg-neutral-950">
                        <h2 class="card-title text-white">
                            {{ $item->title }}
                            <div class="badge badge-secondary">{{ $item->year }}</div>
                        </h2>
                        <p class="text-gray-200 line-clamp-2">{{ $item->description }}</p>
                        <p class="text-gray-200">Pengarang : {{ $item->author }}</p>
                        <div class="card-actions justify-end">
                            @foreach ($item->categories as $category)
                                <div class="badge badge-outline badge-warning">{{ $category->category_name }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
