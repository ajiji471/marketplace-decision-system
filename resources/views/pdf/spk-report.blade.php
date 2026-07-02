<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan SPK - Marketplace Decision System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 11px;
            color: #666;
        }

        .info-box {
            background: #f5f5f5;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .info-box table {
            width: 100%;
        }

        .info-box td {
            padding: 3px 0;
            font-size: 11px;
        }

        .info-box td:first-child {
            width: 150px;
            font-weight: bold;
        }

        h2 {
            font-size: 14px;
            margin: 25px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
        }

        th {
            background: #333;
            color: #fff;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .rank {
            display: inline-block;
            width: 24px;
            height: 24px;
            line-height: 24px;
            text-align: center;
            border-radius: 50%;
            font-weight: bold;
            font-size: 11px;
        }

        .rank-1 { background: #ffd700; color: #333; }
        .rank-2 { background: #c0c0c0; color: #333; }
        .rank-3 { background: #cd7f32; color: #fff; }
        .rank-other { background: #e0e0e0; color: #333; }

        .score {
            font-family: monospace;
            font-size: 11px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
            font-style: italic;
        }

        .weights-list {
            font-size: 10px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Perhitungan SPK</h1>
        <p>Marketplace Decision System</p>
        <p>Generated: {{ $generatedAt }}</p>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td>Metode</td>
                <td>: {{ strtoupper($method) }}</td>
            </tr>
            <tr>
                <td>Kategori</td>
                <td>: {{ $category }}</td>
            </tr>
            <tr>
                <td>Total Produk</td>
                <td>: {{ $totalProducts }}</td>
            </tr>
            <tr>
                <td>Bobot Kriteria</td>
                <td>:
                    <span class="weights-list">
                        @foreach($weights as $key => $value)
                            {{ $key }}={{ number_format($value, 2) }};
                        @endforeach
                    </span>
                </td>
            </tr>
        </table>
    </div>

    @if(!empty($sawResults))
        <h2>Hasil SAW (Simple Additive Weighting)</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">Rank</th>
                    <th>Produk</th>
                    <th style="text-align: right;">Score</th>
                    <th style="text-align: right;">Margin</th>
                    <th style="text-align: right;">Terjual</th>
                    <th style="text-align: right;">Rating</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sawResults as $item)
                    <tr>
                        <td style="text-align: center;">
                            <span class="rank {{ $item['rank'] <= 3 ? 'rank-' . $item['rank'] : 'rank-other' }}">
                                {{ $item['rank'] }}
                            </span>
                        </td>
                        <td>{{ $item['product']['name'] ?? 'Produk #' . $item['product_id'] }}</td>
                        <td style="text-align: right;" class="score">{{ number_format($item['score'], 4) }}</td>
                        <td style="text-align: right;">{{ number_format($item['details']['margin_percent'] ?? 0, 1) }}%</td>
                        <td style="text-align: right;">{{ number_format($item['details']['sold_count'] ?? 0, 0, ',', '.') }}</td>
                        <td style="text-align: right;">{{ number_format($item['details']['rating'] ?? 0, 1) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if(!empty($wpResults))
        <h2>Hasil Weighted Product</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">Rank</th>
                    <th>Produk</th>
                    <th style="text-align: right;">Score</th>
                    <th style="text-align: right;">Margin</th>
                </tr>
            </thead>
            <tbody>
                @foreach($wpResults as $item)
                    <tr>
                        <td style="text-align: center;">
                            <span class="rank {{ $item['rank'] <= 3 ? 'rank-' . $item['rank'] : 'rank-other' }}">
                                {{ $item['rank'] }}
                            </span>
                        </td>
                        <td>{{ $item['product']['name'] ?? 'Produk #' . $item['product_id'] }}</td>
                        <td style="text-align: right;" class="score">{{ number_format($item['score'], 6) }}</td>
                        <td style="text-align: right;">{{ number_format($item['details']['margin_percent'] ?? 0, 1) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if(empty($sawResults) && empty($wpResults))
        <div class="no-data">
            Tidak ada data hasil perhitungan.
        </div>
    @endif

    <div class="footer">
        <p>Marketplace Decision System &copy; {{ date('Y') }}</p>
        <p>Dokumen ini digenerate secara otomatis dari sistem.</p>
    </div>
</body>
</html>