@extends('layouts.main-layout')
@section('title', 'Koleksi Buku')
@section('content')
    @include('components.navbar')
    <div class="flex flex-col xl:flex-row xl:justify-between xl:px-60 px-6 min-h-[85vh] items-center justify-center">
        <div>
            <img class="2xl:w-[30rem] w-[15rem]" src="{{ asset('images/book-lover-25.svg') }}" alt="">
        </div>
        <div>
            <h1 class="font-bold xl:text-6xl text-4xl text-center text-white">Temukan <span class="text-yellow-400">Buku</span></h1>
            <h1 class="font-bold xl:text-6xl text-4xl text-center text-white"><span class="text-yellow-400">Favoritmu</span> Di</h1>
            <h1 class="font-bold xl:text-6xl text-4xl text-center text-white">Perpustakaan <span class="text-yellow-400">Kami</span></h1>
        </div>
    </div>
    <div class="xl:px-60 px-6 py-40 bg-neutral-900">
        <h1 class="text-white xl:text-5xl text-3xl font-bold">Koleksi Buku Kami</h1>
        <div class="mt-10">

            <label
                class="input w-full bg-neutral-950 text-white flex items-center gap-2 rounded-md px-4 py-2 border border-gray-700">
                <svg class="h-5 w-5 opacity-50 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                        stroke="currentColor">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </g>
                </svg>
                <input type="search" id="searchInput" placeholder="Cari buku..."
                    class="w-full bg-transparent text-white placeholder-gray-400 focus:outline-none" />
            </label>
        </div>
        @if ($books->count() > 0)
            <div id="bookContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
                @foreach ($books as $item)
                    @include('components.book-card', ['item' => $item])
                @endforeach
            </div>
        @else
            <p class="text-white mt-10">Tidak ada buku yang ditemukan untuk kata kunci
                "<strong>{{ request('q') }}</strong>".
            </p>
        @endif
        <div class="mt-4">
            {{ $books->links() }}
        </div>
    </div>
    @include('components.footer')

    @push('js')
        <script>
            const searchInput = document.getElementById('searchInput');
            const bookContainer = document.getElementById('bookContainer');

            searchInput.addEventListener('input', function() {
                const keyword = this.value;

                fetch(`/books/search?q=${encodeURIComponent(keyword)}`)
                    .then(response => response.json())
                    .then(data => {
                        bookContainer.innerHTML = '';

                        if (data.length === 0) {
                            bookContainer.innerHTML = '<p class="text-white">Tidak ada hasil.</p>';
                            return;
                        }

                        data.forEach(item => {
                            const categories = item.categories.map(cat =>
                                `<div class="badge badge-outline badge-warning">${cat.category_name}</div>`
                            ).join('');

                            const card = `
                        <div class="card bg-base-100 w-full shadow-sm">
                            <figure>
                                <img src="/storage/${item.cover}" class="h-72 object-cover w-full" alt="${item.title}" />
                            </figure>
                            <div class="card-body bg-neutral-950">
                                <h2 class="card-title text-white">
                                    ${item.title}
                                    <div class="badge badge-secondary">${item.year}</div>
                                </h2>
                                <p class="text-gray-200 line-clamp-2">${item.description}</p>
                                <p class="text-gray-200">Pengarang: ${item.author}</p>
                                <div class="card-actions justify-end flex-wrap">
                                    ${categories}
                                </div>
                            </div>
                        </div>
                    `;
                            bookContainer.innerHTML += card;
                        });
                    });
            });
        </script>
    @endpush

@endsection
