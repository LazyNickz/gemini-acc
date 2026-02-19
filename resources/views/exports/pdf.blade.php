<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Helvetica Neue', sans-serif;
            font-size: 12px;
            color: #333;
        }

        h1 {
            font-size: 18px;
            margin-bottom: 4px;
            color: #1e1b4b;
        }

        p.subtitle {
            font-size: 11px;
            color: #666;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #6366f1;
            color: #fff;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 7px 10px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }

        tr:nth-child(even) td {
            background: #f8fafc;
        }

        .footer {
            margin-top: 20px;
            font-size: 10px;
            color: #999;
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>{{ $title }}</h1>
    <p class="subtitle">Generated on {{ now()->format('M d, Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                @foreach($headings as $h)
                    <th>{{ $h }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ config('app.name') }} &middot; Export Report
    </div>
</body>

</html>