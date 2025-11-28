@extends('layouts.master_layout')

@section('content')

    <div class="container mt-5"
        style="display: flex; flex-direction: column; align-items: center; justify-content: flex-start; min-height: 100vh;">
        <div class="text-center mb-4 mt-5"></div>

        <!-- Pie chart container -->
        <div class="d-flex justify-content-center" style="position: relative; width: 80%; max-width: 600px; height: 300px;">
            <canvas id="pieChart"></canvas>
        </div>

        <div class="card p-4 shadow-lg w-100 mt-3"
            style="max-width: 900px; background: rgba(255, 255, 255, 0.95); border-radius: 12px;">
            <!-- Responsive Table Wrapper -->
            <div class="table-responsive">
                <table class="table table-striped table-hover text-center align-middle">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Category</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr class="table-secondary">
                                <td colspan="5" class="fw-bold text-start">
                                    <a href="javascript:void(0);" class="category-toggle d-flex align-items-center"
                                        data-target="#category{{ $category->id }}">
                                        <i class="bi bi-plus-circle me-2"></i>
                                        {{ $category->name }}
                                    </a>
                                </td>
                                <td class="fw-bold text-start">
                                    E£ {{ $category->total_spent }}
                                </td>
                            </tr>

                            <tr id="category{{ $category->id }}" class="collapse">
                                <td colspan="6">
                                    <table class="table table-striped table-hover text-center align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th></th>
                                                <th>Name</th>
                                                <th>Price Per Unit</th>
                                                <th>Quantity</th>
                                                <th>Direction</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($category->normalTransactions as $transaction)
                                                <tr>
                                                    <td class="fw-bold"></td>
                                                    <td class="fw-semibold text-truncate">{{ $transaction->name }}</td>
                                                    <td class="fw-bold">E£ {{ number_format($transaction->price, 2) }}</td>
                                                    <td class="fw-bold">{{ number_format($transaction->quantity, 2) }}</td>
                                                    <td>
                                                        @if ($transaction->direction === 'credit')
                                                            <span class="badge bg-success">
                                                                <i class="bi bi-arrow-down-circle me-1"></i> Credit
                                                            </span>
                                                        @elseif ($transaction->direction === 'debit')
                                                            <span class="badge bg-danger">
                                                                <i class="bi bi-arrow-up-circle me-1"></i> Debit
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td class="fw-bold">E£
                                                        {{ number_format($transaction->quantity * $transaction->price, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Additional Styling for Mobile -->
    <!-- Styles for Responsive Table -->
    <style>
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }

        .card {
            margin-top: 20px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            min-width: 600px;
        }

        th,
        td {
            white-space: nowrap;
            word-wrap: break-word;
            min-width: 100px;
        }

        @media (max-width: 768px) {

            th,
            td {
                min-width: 80px;
            }

            .table thead {
                font-size: 14px;
            }
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Collapse functionality for categories
            const categoryRows = document.querySelectorAll('.category-toggle');
            categoryRows.forEach(row => {
                row.addEventListener('click', function () {
                    const target = document.querySelector(this.dataset.target);
                    const icon = this.querySelector('i');

                    if (target.classList.contains('collapse')) {
                        target.classList.remove('collapse');
                        target.classList.add('collapsing');
                        icon.classList.remove('bi-plus-circle');
                        icon.classList.add('bi-dash-circle');
                        setTimeout(() => target.classList.remove('collapsing'), 300);
                    } else {
                        target.classList.add('collapse');
                        target.classList.remove('collapsing');
                        icon.classList.remove('bi-dash-circle');
                        icon.classList.add('bi-plus-circle');
                    }
                });
            });
        });
    </script>


    <script>
        // Array of 200 curated colors
        const colorPalette = [
            "#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0", "#9966FF", "#FF9F40", "#FFB6C1", "#FF5733", "#D1A2E6", "#9E8E6A",
            "#F6B9E8", "#FFD700", "#7FFFD4", "#8A2BE2", "#D2691E", "#32CD32", "#FF4500", "#2F4F4F", "#BDB76B", "#9ACD32",
            "#FF6347", "#F0E68C", "#C71585", "#A52A2A", "#B8860B", "#8B0000", "#E9967A", "#BDB76B", "#228B22", "#00FF00",
            "#CD5C5C", "#4B0082", "#ADD8E6", "#8FBC8F", "#FA8072", "#DC143C", "#FFFF00", "#F0F8FF", "#D3D3D3", "#B0E0E6",
            "#F5FFFA", "#00FA9A", "#808080", "#E0FFFF", "#FF0000", "#800000", "#008000", "#00FF00", "#008080", "#000080",
            "#FFD700", "#B8860B", "#ADFF2F", "#D3D3D3", "#98FB98", "#AFEEEE", "#FF1493", "#B0C4DE", "#A52A2A", "#5F9EA0",
            "#D2691E", "#CD5C5C", "#F0E68C", "#ADD8E6", "#BC8F8F", "#20B2AA", "#B0E0E6", "#FF4500", "#2F4F4F", "#6A5ACD",
            "#FF6347", "#8A2BE2", "#98FB98", "#8B4513", "#FFD700", "#D2691E", "#E9967A", "#FF6347", "#8B0000", "#9932CC",
            "#00FF7F", "#C71585", "#B0C4DE", "#F0F8FF", "#DA70D6", "#D8BFD8", "#00CED1", "#A52A2A", "#5F9EA0", "#8A2BE2",
            "#32CD32", "#BA55D3", "#800000", "#FF69B4", "#B22222", "#FF4500", "#FF6347", "#E0FFFF", "#8B0000", "#FF0000",
            "#FF4500", "#228B22", "#D3D3D3", "#CD5C5C", "#32CD32", "#FFD700", "#B8860B", "#6A5ACD", "#00FFFF", "#800080",
            "#E9967A", "#00FF00", "#ADFF2F", "#F5F5DC", "#2F4F4F", "#8B4513", "#FF6347", "#7FFF00", "#FF6347", "#000080",
            "#FF1493", "#8B0000", "#00BFFF", "#800000", "#B0E0E6", "#DC143C", "#8FBC8F", "#8A2BE2", "#FFD700", "#7CFC00",
            "#D2691E", "#C71585", "#DB7093", "#7FFF00", "#B22222", "#CD5C5C", "#F0F8FF", "#DA70D6", "#D8BFD8", "#FF6347",
            "#F0E68C", "#00CED1", "#B0C4DE", "#00BFFF", "#FF1493", "#F5FFFA", "#C71585", "#F0F8FF", "#FF0000", "#FFD700",
            "#8A2BE2", "#D3D3D3", "#7B68EE", "#00FF00", "#228B22", "#FF6347", "#FF7F50", "#7FFF00", "#20B2AA", "#FF69B4",
            "#D2691E", "#D3D3D3", "#00FF7F", "#D8BFD8", "#F5F5DC", "#808080", "#FF8C00", "#FFFF00", "#BC8F8F", "#FF4500",
            "#32CD32", "#00BFFF", "#B0E0E6", "#FF6347", "#FF8C00", "#FF00FF", "#F5FFFA", "#20B2AA", "#DA70D6", "#B0C4DE",
            "#FF00FF", "#32CD32", "#B0E0E6", "#F0E68C", "#A52A2A", "#7FFF00", "#FFD700", "#D2691E", "#F0F8FF", "#F0E68C",
            "#C71585", "#E0FFFF", "#D8BFD8", "#8B0000", "#D3D3D3", "#D2691E", "#FF4500", "#00FF00", "#D8BFD8", "#F5F5DC"
        ];

        // Function to assign the colors dynamically
        function generateColors(count) {
            return {
                backgroundColor: colorPalette.slice(0, count), // Use the first "count" colors
                hoverBackgroundColor: colorPalette.slice(0, count) // Use the same for hover
            };
        }

        var ctx = document.getElementById('pieChart').getContext('2d');
        var pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: @json($categorySummary->pluck('category')),
                datasets: [{
                    data: @json($categorySummary->pluck('total_spent')),
                    backgroundColor: generateColors(@json($categorySummary->count())).backgroundColor,
                    hoverBackgroundColor: generateColors(@json($categorySummary->count())).hoverBackgroundColor,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Transaction Summary for {{ $monthYear->year }}-{{ str_pad($monthYear->month, 2, '0', STR_PAD_LEFT) }}',
                        color: '#FFFFFF', // 👈 this sets the title color to white
                        font: {
                            size: 18,
                            weight: 'bold'
                        }
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#FFFFFF' // 👈 this sets legend labels to white
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                return tooltipItem.raw.toFixed(2);
                            }
                        },
                        titleColor: '#FFFFFF',
                        bodyColor: '#FFFFFF'
                    }
                }
            }
        });
    </script>


@endsection