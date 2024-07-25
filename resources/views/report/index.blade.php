<!-- resources/views/procedure_report.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>تقرير الإجراءات</title>
    <style type="text/css">
    body{
        direction: rtl
    }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">مصفوفة الخطة التنفيذية الربعية لبرنامج سلاسل القيمة من العام الهجري 1446 </div>
    <table>
        <thead>
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
                    <td>{{ $item->project->name}}</td>
                    <td>{{ $item->activity->name}}</td>
                    <td>{{ $item->activity->target_value}}</td>
                    <td>{{ $item->activity->target_indicator}}</td>
                    <td>{{ $item->activity->activity_weight}}</td>
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