@extends('layouts.main-layout')
@section('title', 'Riwayat Peminjaman')

@section('content')
    @include('components.navbar')

    <div class="2xl:px-60 min-h-screen">

        <div class="w-full mt-10 p-5 bg-neutral-900 rounded shadow">
            <h1 class="text-2xl font-bold mb-6">Riwayat Peminjaman Buku</h1>
    
            @if($bookLoans->isEmpty())
                <p class="text-gray-600">Belum ada riwayat peminjaman.</p>
            @else
                @foreach($bookLoans as $loan)
                    <div class="mb-6 border-b pb-4">
                        <h2 class="text-lg font-semibold">Nomor Peminjaman: {{ $loan->loan_num }}</h2>
                        <p><i class="fa-regular fa-calendar"></i> Tanggal Pinjam: {{ $loan->loan_date }}</p>
                        <p><i class="fa-regular fa-calendar"></i> Jatuh Tempo: {{ $loan->due_date }}</p>
                        <p><i class="fa-regular fa-clock"></i> Status: <span class="font-medium">{{ $loan->status }}</span></p>
    
                        <div class="mt-2">
                            <h3 class="font-semibold">Buku:</h3>
                            <ul class="list-disc pl-5">
                                @foreach($loan->items as $item)
                                    <li>
                                        {{ $item->book->title }} - 
                                        Status: <span class="text-sm">{{ $item->status }}</span>
                                        @if ($item->return_date)
                                            (Dikembalikan: {{ $item->return_date }})
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

@endsection

@section('scripts')
    @if(session('not_member'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Akses Ditolak',
                text: 'Hanya pengguna dengan role member yang dapat melihat riwayat peminjaman.',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
@endsection
