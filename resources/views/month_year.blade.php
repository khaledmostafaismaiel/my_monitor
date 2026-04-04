@extends('layouts.master_layout')

@section('content')

@php
function renderMonthYearCategoryRow($category, $level = 0, $collapsed = false) {
    // debug comment
    echo '<!--FUNC:'.$category->name.'-->';
    $display = $collapsed ? 'display:none;' : '';
    $hasChildren = $category->children && $category->children->count() > 0;
    $chevronClass = $hasChildren ? ($collapsed ? 'bi-chevron-right' : 'bi-chevron-down') : '';
    $cursorStyle = $hasChildren ? 'cursor:pointer;' : '';
    $iconClass = $hasChildren ? 'text-warning' : 'text-muted';
    $rowClass = $hasChildren ? 'parent-row' : '';
    
    $parentId = $category->parent_id ?? '';
    echo '<tr class="group-row child-row ' . $rowClass . '" data-category-id="' . $category->id . '" data-parent-id="' . $parentId . '" data-children-parent-id="' . $category->id . '" style="' . $display . '">';
    echo '<td colspan="5" class="fw-bold text-start"><div class="d-flex flex-column">';
    echo '<a href="javascript:void(0);" class="category-toggle d-flex align-items-center" data-target="#category' . $category->id . '" data-level="' . $level . '">';
    if ($hasChildren) {
        echo '<i class="bi ' . $chevronClass . ' toggle-icon me-2" style="cursor:pointer;"></i>';
    } else {
        echo '<span style="width:16px;display:inline-block;" class="me-2"></span>';
    }
    echo '<i class="bi bi-folder ' . $iconClass . ' me-2"></i>';
    echo '<span class="fw-semibold" style="padding-left: ' . ($level * 12) . 'px">' . ucfirst($category->name) . '</span>';
    if ($hasChildren) {
        echo '<span class="text-muted small ms-2">(' . $category->children->count() . ')</span>';
    }
    echo '</a>';
    
    if($category->limit) {
        $spent = abs($category->total_spent ?? 0);
        $percentage = $category->limit > 0 ? ($spent / $category->limit) * 100 : 0;
        $overAmount = $spent - $category->limit;
        $progressColor = $overAmount > 0 ? 'danger' : 'success';
        
        echo '<div class="mt-2 ms-4"><div class="d-flex justify-content-between align-items-center mb-1"><small class="text-muted"><i class="bi bi-speedometer2"></i> Limit: E£ ' . number_format($category->limit, 2) . '</small><small class="fw-semibold text-' . $progressColor . '">' . number_format($percentage, 1) . '%</small></div><div class="progress" style="height: 8px;"><div class="progress-bar bg-' . $progressColor . '" role="progressbar" style="width: ' . min($percentage, 100) . '%" aria-valuenow="' . $percentage . '" aria-valuemin="0" aria-valuemax="100"></div></div></div>';
    } else {
        echo '<small class="text-muted mt-1 ms-4"><i class="bi bi-infinity"></i> No limit set</small>';
    }
    
    echo '</div></td>';
    echo '<td class="fw-bold text-start"><div class="d-flex flex-column align-items-start">';
    echo '<span class="mb-1">E£ ' . number_format($category->total_spent ?? 0, 2) . '</span>';
    if($category->limit) {
        $spent = abs($category->total_spent ?? 0);
        $percentage = $category->limit > 0 ? ($spent / $category->limit) * 100 : 0;
        $overAmount = $spent - $category->limit;
        if($overAmount > 0) {
            echo '<span class="badge bg-danger"><i class="bi bi-exclamation-circle"></i> Over Budget</span>';
        } else {
            echo '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Within Budget</span>';
        }
    }
    echo '</div></td></tr>';
    
    if ($hasChildren) {
        foreach ($category->children as $child) {
            renderMonthYearCategoryRow($child, $level + 1, $collapsed);
        }
    }
}
@endphp

    <div class="container mt-5"
        style="display: flex; flex-direction: column; align-items: center; justify-content: flex-start; min-height: 100vh;">

        <!-- Month/Year Header with Navigation -->
        <div class="w-100 mb-4" style="max-width: 900px;">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        @if($prevMonthYear)
                            <a href="{{ route('month_years.show', $prevMonthYear->id) }}"
                                class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-chevron-left"></i> Previous
                            </a>
                        @else
                            <button class="btn btn-outline-secondary btn-sm" disabled>
                                <i class="bi bi-chevron-left"></i> Previous
                            </button>
                        @endif

                        <h3 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-calendar3"></i>
                            {{ date('F Y', strtotime($monthYear->year . '-' . str_pad($monthYear->month, 2, '0', STR_PAD_LEFT))) }}
                        </h3>

                        @if($nextMonthYear)
                            <a href="{{ route('month_years.show', $nextMonthYear->id) }}"
                                class="btn btn-outline-primary btn-sm">
                                Next <i class="bi bi-chevron-right"></i>
                            </a>
                        @else
                            <button class="btn btn-outline-secondary btn-sm" disabled>
                                Next <i class="bi bi-chevron-right"></i>
                            </button>
                        @endif
                    </div>

                    <!-- Summary Cards -->
                    <div class="row g-3 mt-2">
                        @php
                            $totalSpent = $categories->sum('total_spent');
                            $totalLimit = $categories->where('limit', '>', 0)->sum('limit');
                            $categoriesWithLimit = $categories->where('limit', '>', 0)->count();
                            $overBudgetCount = $categories->filter(function ($cat) {
                                return $cat->limit && (abs($cat->total_spent) - $cat->limit) > 0;
                            })->count();
                        @endphp

                        <!-- Total Spent Card -->
                        <div class="col-md-4">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-cash-stack text-primary fs-2 mb-2"></i>
                                    <h6 class="text-muted mb-1">Total Spent</h6>
                                    <h4 class="fw-bold mb-0">E£ {{ number_format($totalSpent, 2) }}</h4>
                                </div>
                            </div>
                        </div>

                        <!-- Categories Card -->
                        <div class="col-md-4">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-tags text-info fs-2 mb-2"></i>
                                    <h6 class="text-muted mb-1">Categories</h6>
                                    <h4 class="fw-bold mb-0">{{ $categories->count() }}</h4>
                                    @if($categoriesWithLimit > 0)
                                        <small class="text-muted">{{ $categoriesWithLimit }} with limits</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Budget Status Card -->
                        <div class="col-md-4">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body text-center">
                                    @if($overBudgetCount > 0)
                                        <i class="bi bi-exclamation-triangle text-danger fs-2 mb-2"></i>
                                        <h6 class="text-muted mb-1">Budget Status</h6>
                                        <h4 class="fw-bold text-danger mb-0">{{ $overBudgetCount }}</h4>
                                        <small class="text-danger">Over Budget</small>
                                    @elseif($totalLimit > 0)
                                        <i class="bi bi-check-circle text-success fs-2 mb-2"></i>
                                        <h6 class="text-muted mb-1">Budget Status</h6>
                                        <h4 class="fw-bold text-success mb-0">On Track</h4>
                                        <small class="text-success">All within limits</small>
                                    @else
                                        <i class="bi bi-info-circle text-secondary fs-2 mb-2"></i>
                                        <h6 class="text-muted mb-1">Budget Status</h6>
                                        <h4 class="fw-bold text-secondary mb-0">No Limits</h4>
                                        <small class="text-muted">Set limits to track</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie chart container -->
        <div class="d-flex justify-content-center mb-4"
            style="position: relative; width: 80%; max-width: 600px; height: 350px;">
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
                        <?php foreach($rootCategories as $cat): ?>
                            <?php $hasChildren = $cat->children && $cat->children->count() > 0; ?>
                            <tr class="table-secondary parent-row" data-category-id="<?php echo e($cat->id); ?>">
                                <td colspan="5" class="fw-bold text-start">
                                    <div class="d-flex flex-column">
                                        <a href="javascript:void(0);" class="category-toggle d-flex align-items-center">
                                            <?php if($hasChildren): ?>
                                                <i class="bi bi-chevron-right toggle-icon me-2"></i>
                                            <?php else: ?>
                                                <span style="width:16px;display:inline-block;" class="me-2"></span>
                                            <?php endif; ?>
                                            <i class="bi bi-folder<?php echo $hasChildren ? '-fill' : ''; ?> text-primary me-2"></i>
                                            <span><?php echo e(ucfirst($cat->name)); ?></span>
                                            <?php if($hasChildren): ?>
                                                <span class="text-muted small ms-2">(<?php echo $cat->children->count(); ?>)</span>
                                            <?php endif; ?>
                                        </a>
                                        <?php if($cat->limit): ?>
                                            <?php $spent = abs($cat->total_spent ?? 0); ?>
                                            <?php $percentage = $cat->limit > 0 ? ($spent / $cat->limit) * 100 : 0; ?>
                                            <div class="mt-2 ms-4">
                                                <small class="text-muted">Limit: E£ <?php echo number_format($cat->limit, 2); ?></small>
                                                <div class="progress mt-1" style="height:8px;">
                                                    <div class="progress-bar bg-<?php echo $percentage > 100 ? 'danger' : 'success'; ?>" style="width: <?php echo min($percentage, 100); ?>%"></div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <small class="text-muted mt-1 ms-4">No limit set</small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="fw-bold text-start">E£ <?php echo number_format($cat->total_spent ?? 0, 2); ?></td>
                            </tr>
                            <?php if($hasChildren): ?>
                                <?php foreach($cat->children as $child): ?>
                                    <tr class="child-row" data-parent-id="<?php echo e($cat->id); ?>" style="display:none;">
                                        <td colspan="5" class="fw-bold text-start">
                                            <div class="d-flex align-items-center" style="padding-left: 48px;">
                                                <i class="bi bi-folder text-warning me-2"></i>
                                                <span><?php echo e(ucfirst($child->name)); ?></span>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-start">E£ <?php echo number_format($child->total_spent ?? 0, 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
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

        /* Custom soft amber/orange colors for better eye comfort */
        .text-warning {
            color: #f59e0b !important;
            /* Softer amber instead of bright yellow */
        }

        .bg-warning {
            background-color: #fbbf24 !important;
            /* Soft amber background */
        }

        .progress-bar.bg-warning {
            background-color: #f59e0b !important;
            /* Amber progress bar */
        }

        .badge.bg-warning {
            background-color: #fbbf24 !important;
            /* Soft amber badge */
            color: #78350f !important;
            /* Dark brown text for better contrast */
        }

        /* Enhanced UX Animations and Transitions */
        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
        }

        .category-toggle {
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .category-toggle:hover {
            color: #0d6efd !important;
            transform: translateX(5px);
        }

        .category-toggle i {
            transition: transform 0.3s ease;
        }

        .category-toggle:hover i {
            transform: scale(1.2);
        }

        .table-secondary {
            transition: background-color 0.3s ease;
        }

        .table-secondary:hover {
            background-color: rgba(108, 117, 125, 0.15) !important;
        }

        .progress {
            transition: all 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            transition: width 0.6s ease;
        }

        .badge {
            transition: all 0.2s ease;
        }

        .badge:hover {
            transform: scale(1.05);
        }

        .btn {
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Smooth collapse animation */
        .collapse {
            display: none;
        }

        .collapsing {
            position: relative;
            height: 0;
            overflow: hidden;
            transition: height 0.35s ease;
        }

        /* Loading animation for chart */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card,
        .table,
        canvas {
            animation: fadeIn 0.5s ease;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .card-body {
                padding: 1rem !important;
            }

            h3 {
                font-size: 1.25rem;
            }
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Collapse functionality for categories using event delegation
            document.addEventListener('click', function(e) {
                const toggleLink = e.target.closest('.category-toggle');
                if (toggleLink) {
                    const row = toggleLink.closest('tr');
                    const categoryId = row.dataset.categoryId;
                    const icon = toggleLink.querySelector('.toggle-icon');
                    
                    // Find all child rows for this category
                    const childRows = document.querySelectorAll('.child-row[data-parent-id="' + categoryId + '"]');
                    
                    childRows.forEach(function(childRow) {
                        if (childRow.style.display === 'none') {
                            childRow.style.display = '';
                            if (icon) {
                                icon.classList.remove('bi-chevron-right');
                                icon.classList.add('bi-chevron-down');
                            }
                        } else {
                            childRow.style.display = 'none';
                            if (icon) {
                                icon.classList.remove('bi-chevron-down');
                                icon.classList.add('bi-chevron-right');
                            }
                        }
                    });
                }
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
                        color: '#000000',
                        font: {
                            size: 18,
                            weight: 'bold'
                        }
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#000000'
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