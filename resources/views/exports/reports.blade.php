<table>
    <thead>
        <tr>
            <th colspan="6" style="text-align: center; font-weight: bold;">
                Laporan Peminjaman Buku
                @if ($from && $to)
                    dari tanggal {{ \Carbon\Carbon::parse($from)->format('d M Y') }}
                    sampai {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
                @endif
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th>No</th>
            <th>Judul Buku</th>
            <th>Nama Member</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Disetujui Oleh</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->book->title ?? '-' }}</td>
                <td>{{ $item->bookLoan->member->name ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($item->bookLoan->loan_date)->format('d M Y') }}</td>
                <td>
                    {{ $item->return_date ? \Carbon\Carbon::parse($item->return_date)->format('d M Y') : 'Belum Kembali' }}
                </td>
                <td>{{ $item->bookLoan->user->name ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
