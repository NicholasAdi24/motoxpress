<table>
    <thead>
        <tr>
            <th>Bulan</th>
            <th>Pendapatan</th>
            <th>HPP</th>
            <th>Beban Operasional</th>
            <th>Laba Kotor</th>
            <th>Laba Bersih</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
        <tr>
            <td>{{ $row['bulan'] }}</td>
            <td>{{ $row['pendapatan'] }}</td>
            <td>{{ $row['hpp'] }}</td>
            <td>{{ $row['pengeluaran'] }}</td>
            <td>{{ $row['laba_kotor'] }}</td>
            <td>{{ $row['laba_bersih'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
