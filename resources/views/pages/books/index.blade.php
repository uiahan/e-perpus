@extends('layouts.main-layout')

@section('title', 'Koleksi Buku')

@section('content')
    @include('components.navbar')

    <!-- Hero Section -->
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

    <!-- Book Collection Section -->
    <div class="xl:px-60 px-4 py-40 bg-neutral-900">
        <h1 class="text-white text-5xl font-bold">Koleksi Buku Kami</h1>

        <!-- Search Form -->
        <div class="mt-10">
            <form method="GET" action="{{ route('books.index') }}">
                <label
                    class="input w-full bg-neutral-950 text-white flex items-center gap-2 rounded-md px-4 py-2 border border-gray-700">
                    <svg class="h-5 w-5 opacity-50 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                            stroke="currentColor">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </g>
                    </svg>
                    <input type="search" name="q" value="{{ request('q') }}" required placeholder="Cari buku..."
                        class="w-full bg-transparent text-white placeholder-gray-400 focus:outline-none" />
                </label>
            </form>
        </div>

        <!-- Book Cards -->
        @if ($books->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
                @foreach ($books as $item)
                    <div class="card bg-base-100 w-full shadow-sm">
                        <figure>
                            <img src="{{ asset('storage/' . $item->cover) }}"
                                class="h-72 object-cover w-full" alt="{{ $item->title }}" />
                        </figure>
                        <div class="card-body bg-neutral-950">
                            <h2 class="card-title text-white">
                                {{ $item->title }}
                                <div class="badge badge-secondary">{{ $item->year }}</div>
                            </h2>
                            <p class="text-gray-200 line-clamp-2">{{ $item->description }}</p>
                            <p class="text-gray-200">Pengarang: {{ $item->author }}</p>
                            <div class="card-actions justify-end flex-wrap">
                                @foreach ($item->categories as $category)
                                    <div class="badge badge-outline badge-warning">{{ $category->category_name }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-10">
                {{ $books->links() }}
            </div>
        @else
            <!-- No Results -->
            <p class="text-white mt-10">Tidak ada buku yang ditemukan untuk kata kunci
                "<strong>{{ request('q') }}</strong>".</p>
        @endif
    </div>

    @include('components.footer')
@endsection
