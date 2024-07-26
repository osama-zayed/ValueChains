<!DOCTYPE html>
<html lang="ar">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>تقرير الإجراءات</title>
    <style>
        html,
        body {
            margin: 10px;
            padding: 15px;
            direction: rtl;
            font-family: 'Arial', sans-serif;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
        }

        .left,
        .center,
        .right {
            flex: 1;
            text-align: center;
        }

        .left p,
        .right p {
            margin: 0;
            padding: 0;
        }

        .center img {
            max-height: 50px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px !important;
        }

        table thead th {
            text-align: center;
            font-size: 10px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 8px;
            font-size: 10px;
        }

        .order-details thead tr th {
            background-color: #1f4e78;
            color: #fff;
            text-align: right;
        }

        .text-start {
            text-align: left;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="left">
            <p>الرقم<br>التاريخ<br>الموافق</p>
        </div>
        <div class="center">
            <img src="{{ asset('image/Screenshot 2024-07-26 161803.png') }}" alt="Logo">
            <p>بسم الله الرحمن الرحيم</p>
        </div>
        <div class="right">
            <p>الجمهورية اليمنية<br>وزارة الزراعة والري<br>قطاع الإنتاج النباتي</p>
            <p>البرنامج الوطني لتحسين وتطوير سلامة المنتج المحلي للمحاصيل الزراعية</p>
        </div>
    </div>
    <table class="order-details">
        <thead>
            <tr>
                <td colspan="18"
                    style="text-align: center;border: solid 1px black;font-size: 18px;font-weight: bold;background-color: #ddebf7">
                    مصفوفة الخطة التنفيذية الربعية لبرنامج سلاسل القيمة من العام الهجري 1446 ه </td>
            </tr>
            <tr>
                <th>م</th>
                <th>اسم السلسلة</th>
                <th>المجال</th>
                <th>الاهداف</th>
                <th>المشروع</th>
                <th>الانشطة</th>
                <th>القيمة المستهدفة</th>
                <th>مؤاشر القيمة المستهدفة</th>
                <th>وزن النشاط</th>
                <th>الاجراءات</th>
                <th>وزن الإجراء</th>
                <th>مدة الإجراء (يوم)</th>
                <th>بداية تنفيذ الإجراء</th>
                <th>نهاية تنفيذ الإجراء</th>
                <th>التكلفة</th>
                <th>مصدر التمويل</th>
                <th>الحالة</th>
                <th>المعني بتنفيذ النشاط/الاجراء</th>

                {{-- <th>تاريخ الإنشاء بالهجري</th> --}}
                {{-- <th>الملف المرفق</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->chain->name }}</td>
                    <td>{{ $item->domain->name }}</td>
                    <td>{{ $item->chain->Goals }}</td>
                    <td>{{ $item->project->name }}</td>
                    <td>{{ $item->activity->name }}</td>
                    <td>{{ $item->activity->target_value }}</td>
                    <td>{{ $item->activity->target_indicator }}</td>
                    <td>{{ $item->activity->activity_weight }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->procedure_weight }}</td>
                    <td>{{ $item->procedure_duration_days }}</td>
                    <td>{{ $item->procedure_start_date }}</td>
                    <td>{{ $item->procedure_end_date }}</td>
                    <td>{{ $item->cost }}</td>
                    <td>{{ $item->funding_source }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->user->name }}</td>

                    {{-- <td>{{ $item->hijri_created_at }}</td>
                <td>{{ $item->attached_file }}</td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
