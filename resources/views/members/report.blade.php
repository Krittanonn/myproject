@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 700px; margin: auto; padding: 20px;">
    <h1>รายงานจำนวนสมาชิกตามช่วงอายุ</h1>

    <!-- กราฟ -->
    <canvas id="ageChart" width="600" height="400" style="margin-bottom: 30px;"></canvas>

    <!-- ตารางสรุป -->
    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>ช่วงอายุ</th>
                <th>จำนวนสมาชิก</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ageGroups as $group => $count)
            <tr>
                <td style="text-align: center;">{{ $group }} ปี</td>
                <td style="text-align: center;">{{ $count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Chart.js (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('ageChart').getContext('2d');

    // ใช้ key ตรง ๆ เป็น label (เช่น "0-10", "60+")
    const ageLabels = {!! json_encode(array_keys($ageGroups)) !!};
    const ageData   = {!! json_encode(array_values($ageGroups)) !!};

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ageLabels,
            datasets: [{
                label: 'จำนวนสมาชิก',
                data: ageData,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor:   'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0,
                    stepSize: 1
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>
@endsection
