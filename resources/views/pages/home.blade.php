@extends('layouts.main-layout')
@section('title', 'Koleksi Buku')
@section('content')
    @include('components.navbar')
    <div class="flex flex-col xl:flex-row xl:justify-between xl:px-60 px-6 min-h-[85vh] items-center justify-center">
        <div>
            <img class="2xl:w-[30rem] md:w-[20rem] w-[15rem]" src="{{ asset('images/book-lover-25.svg') }}" alt="">
        </div>
        <div>
            <h1 class="font-bold xl:text-6xl md:text-5xl text-4xl text-center 2xl:text-start text-white">Temukan <span
                    class="text-yellow-400">Buku</span></h1>
            <h1 class="font-bold xl:text-6xl md:text-5xl text-4xl text-center 2xl:text-start text-white"><span
                    class="text-yellow-400">Favoritmu</span> Di</h1>
            <h1 class="font-bold xl:text-6xl md:text-5xl text-4xl text-center 2xl:text-start text-white">Perpustakaan <span
                    class="text-yellow-400">Kami</span></h1>
        </div>
    </div>
    <div class="xl:px-60 px-6 py-40 bg-neutral-900">
        <h1 class="text-white xl:text-5xl text-3xl font-bold">Koleksi Buku Kami</h1>
        <div class="mt-10 flex items-center space-x-3">

            <div class="flex flex-col sm:flex-row gap-4">
                <select id="categoryFilter"
                    class="bg-neutral-950 text-gray-400 border border-gray-700 px-4 py-2 rounded-md">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                    @endforeach
                </select>
            </div>
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
                    <div class="cursor-pointer" onclick="showDetail('{{ $item->id }}')">
                        @include('components.book-card', ['item' => $item])
                    </div>
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
    <!-- Modal Detail Buku -->
    <div id="bookDetailModal" class="fixed inset-0 bg-black bg-opacity-60 hidden justify-center items-center z-50">
        <div class="bg-neutral-950 text-white rounded-xl p-6 w-full max-w-3xl relative">
            <button id="closeModal" class="absolute top-2 right-3 text-white text-2xl">&times;</button>
            <div class="flex flex-col md:flex-row gap-6">
                <img id="modalCover" src="" alt="Cover" class="w-full md:w-60 rounded-lg object-cover" />
                <div>
                    <h2 id="modalTitle" class="text-2xl font-bold mb-2"></h2>
                    <p id="modalAuthor" class="mb-1 text-sm text-gray-400"></p>
                    <p id="modalPublisher" class="mb-1 text-sm text-gray-400"></p>
                    <p id="modalYear" class="mb-4 text-sm text-gray-400"></p>
                    <p id="modalDescription" class="text-gray-200"></p>
                </div>
            </div>
        </div>
    </div>


    @push('js')
        <script>
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const bookContainer = document.getElementById('bookContainer');

            const modal = document.getElementById('bookDetailModal');
            const closeModal = document.getElementById('closeModal');

            function openModal(data) {
                document.getElementById('modalCover').src = `/storage/${data.cover}`;
                document.getElementById('modalTitle').textContent = data.title;
                document.getElementById('modalAuthor').textContent = `Pengarang: ${data.author}`;
                document.getElementById('modalPublisher').textContent = `Penerbit: ${data.publisher}`;
                document.getElementById('modalYear').textContent = `Tahun: ${data.year}`;
                document.getElementById('modalDescription').textContent = data.description || '-';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            closeModal.addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            function fetchBooks() {
                const keyword = searchInput.value;
                const categoryId = categoryFilter.value;

                const url = new URL('/books/search', window.location.origin);
                url.searchParams.append('q', keyword);
                if (categoryId) {
                    url.searchParams.append('category_id', categoryId);
                }

                fetch(url)
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

                            const card = document.createElement('div');
                            card.className = 'card bg-base-100 w-full shadow-sm cursor-pointer';
                            card.innerHTML = `
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
                            <div class="card-actions justify-end flex-wrap">${categories}</div>
                        </div>
                    `;
                            card.addEventListener('click', () => {
                                fetch(`/books/${item.id}`)
                                    .then(res => res.json())
                                    .then(book => openModal(book));
                            });

                            bookContainer.appendChild(card);
                        });
                    });
            }

            searchInput.addEventListener('input', fetchBooks);
            categoryFilter.addEventListener('change', fetchBooks);
        </script>
        <script>
            function showDetail(id) {
                fetch(`/books/${id}`)
                    .then(res => res.json())
                    .then(data => openModal(data));
            }
        </script>
    @endpush


@endsection
