<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        * {
            font-family: "DejaVu Sans", sans-serif;
            direction: rtl;
            unicode-bidi: embed;
            text-align: right;
        }

        body {
            direction: rtl;
            unicode-bidi: embed;
        }

        h1, h3 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            direction: rtl;
            unicode-bidi: embed;
        }

        th, td {
            border: 1px solid #333;
            padding: 8px;
            font-size: 13px;
            direction: rtl;
            unicode-bidi: embed;
        }

        th {
            background: #eee;
        }

        .status-new { color: blue; }
        .status-in_progress { color: orange; }
        .status-resolved { color: green; }
        .status-rejected { color: red; }
    </style>

</head>
<body>

<h1>تقرير الشكاوى</h1>
<h3>تاريخ التصدير: {{ now()->format('Y-m-d') }}</h3>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>رقم المرجع</th>
        <th>المستخدم</th>
        <th>الجهة الحكومية</th>
        <th>الحالة</th>
        <th>تاريخ الإنشاء</th>
    </tr>
    </thead>
    <tbody>
    @foreach($complaints as $index => $complaint)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $complaint->reference_number }}</td>
            <td>{{ $complaint->user->name }}</td>
            <td>{{ $complaint->agency->name }}</td>
            <td class="status-{{ $complaint->status }}">
                {{ __($complaint->status) }}
            </td>
            <td>{{ $complaint->created_at->format('Y-m-d') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
