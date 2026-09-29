<?php $__env->startSection('title', 'Reports'); ?>

<?php $__env->startSection('css'); ?>
<style>
    .chart-card {
        border-radius: 12px;
        border: 1px solid #e9ecef;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
        overflow: hidden;
    }
    .chart-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.10);
        transform: translateY(-2px);
    }
    .chart-label-badge {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .chart-type-tag {
        font-size: 10px;
        background: #f1f3f5;
        color: #6c757d;
        padding: 2px 8px;
        border-radius: 10px;
        font-weight: 500;
    }
    .chart-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 8px;
    }
    .no-data-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 180px;
        color: #adb5bd;
        font-size: 13px;
        flex-direction: column;
        gap: 8px;
    }
    .load-more-sentinel {
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .chart-wrapper {
        min-height: 200px;
    }
    .skeleton-card {
        border-radius: 12px;
        border: 1px solid #e9ecef;
        overflow: hidden;
        padding: 20px;
    }
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.4s infinite;
        border-radius: 6px;
    }
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    .fade-in {
        animation: fadeInUp 0.4s ease both;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .reports-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .total-badge {
        background: #e8f4fd;
        color: #0d6efd;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main_content'); ?>
<section x-data="ReportComponent()">
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3><?php echo e(__('Report')); ?></h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('admin.dashboard')); ?>">
                                <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-home')); ?>"></use>
                                </svg>
                            </a>
                        </li>
                        <li class="breadcrumb-item"><?php echo e(__('ECC')); ?></li>
                        <li class="breadcrumb-item active"><?php echo e(__('Chart Report')); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">

        <!-- Header bar -->
        <div class="reports-header">
            <div>
                
                <small class="text-muted" x-show="!isLoading">
                    Showing <span x-text="visibleCharts.length"></span> of <span x-text="chartItems.length"></span> charts
                </small>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <span class="total-badge" x-show="!isLoading" x-text="`${chartItems.length} configured`"></span>
                <!-- Refresh button -->
                <button class="btn btn-outline-secondary btn-sm" @click="refresh()" :disabled="isLoading">
                    <i class="fa-solid fa-rotate-right" :class="{ 'fa-spin': isLoading }"></i>
                </button>
            </div>
        </div>

        <!-- Error state -->
        

        <!-- Skeleton loaders (shown while loading) -->
        <div x-show="isLoading" class="row g-3">
            <template x-for="i in 4" :key="i">
                <div class="col-md-6">
                    <div class="skeleton-card">
                        <div class="skeleton mb-2" style="height:14px; width:60%;"></div>
                        <div class="skeleton mb-3" style="height:10px; width:30%;"></div>
                        <div class="skeleton" style="height:180px;"></div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty state -->
        <div x-show="!isLoading && !hasError && chartItems.length === 0" class="text-center py-5">
            <i class="fa-regular fa-chart-bar fa-3x text-muted mb-3"></i>
            <p class="text-muted">No chart reports configured yet.</p>
            <a href="<?php echo e(route('admin.settings.index')); ?>" class="btn btn-primary btn-sm">
                Configure Charts
            </a>
        </div>

        <!-- Charts Grid -->
        <div x-show="!isLoading && chartItems.length > 0" class="row g-3">
            <template x-for="(item, index) in visibleCharts" :key="item.question_id">
                <div class="col-md-6 fade-in" :style="`animation-delay: ${(index % 2) * 0.08}s`">
                    <div class="card chart-card mb-0">
                        <div class="card-body">
                            <!-- Card header -->
                            <div class="chart-meta">
                                <!-- Label badge -->
                                <template x-if="item.label">
                                    <span class="chart-label-badge"
                                          :style="`background-color: ${item.label.color}22; color: ${item.label.color};`"
                                          x-text="item.label.label">
                                    </span>
                                </template>
                                <!-- Chart type tag -->
                                <span class="chart-type-tag" x-text="item.chart_type.replace('_', ' ').toUpperCase()"></span>
                                <!-- Response count -->
                                <span class="ms-auto text-muted" style="font-size:12px;">
                                    <i class="fa-regular fa-user me-1"></i>
                                    <span x-text="(item.report?.total_responses ?? 0) + ' responses'"></span>
                                </span>
                            </div>

                            <!-- Question title -->
                            <h6 class="fw-semibold mb-3" style="font-size:13px; line-height:1.4;" x-text="item.question"></h6>

                            <!-- Average (for single choice / numeric) -->
                            <template x-if="item.report?.average !== null && item.report?.average !== undefined">
                                <p class="text-muted mb-2" style="font-size:12px;">
                                    Average: <strong x-text="item.report.average"></strong>
                                </p>
                            </template>

                            <!-- No data placeholder -->
                            <template x-if="!item.report || item.report.total_responses === 0">
                                <div class="no-data-placeholder">
                                    <i class="fa-regular fa-chart-bar fa-2x"></i>
                                    <span>No responses yet</span>
                                </div>
                            </template>

                            <!-- Chart container (only if there's data) -->
                            <template x-if="item.report && item.report.total_responses > 0">
                                <div class="chart-wrapper"
                                     :id="`chart-${item.question_id}`"
                                     :data-question-id="item.question_id"
                                     :style="['pie','donut'].includes(item.chart_type) ? 'min-height: 380px; width: 100%;' : 'min-height: 220px;'">
                                </div>
                            </template>

                            <!-- Text answers list -->
                            <template x-if="item.chart_type === 'text_list' && item.report?.answers?.length > 0">
                                <div class="mt-2" style="max-height:200px; overflow-y:auto;">
                                    <template x-for="(ans, ai) in item.report.answers" :key="ai">
                                        <div class="border-bottom py-1" style="font-size:12px;" x-text="ans"></div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Load more sentinel (intersection observer target) -->
        <div class="load-more-sentinel mt-3" x-ref="sentinel" x-show="!isLoading && hasMore">
            <div class="spinner-border spinner-border-sm text-primary" role="status" x-show="isLoadingMore">
                <span class="visually-hidden">Loading more...</span>
            </div>
            <span class="text-muted" style="font-size:12px;" x-show="!isLoadingMore && hasMore">Scroll for more</span>
        </div>

        <!-- All loaded indicator -->
        <div class="text-center py-3" x-show="!isLoading && !hasMore && chartItems.length > 0">
            <small class="text-muted">
                <i class="fa-solid fa-check-circle me-1 text-success"></i>
                All <span x-text="chartItems.length"></span> charts loaded
            </small>
        </div>

    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('assets/js/chart/apex-chart/apex-chart.js')); ?>"></script>

<script>
function ReportComponent() {
    return {
        isLoading: false,
        isLoadingMore: false,
        hasError: false,
        errorMessage: '',

        // All merged chart data (questionCharts + reportData joined)
        chartItems: [],

        // Pagination
        pageSize: 4,
        currentPage: 1,

        // Rendered apex chart instances (to avoid duplicates)
        renderedCharts: {},

        // Intersection observer for infinite scroll
        observer: null,

        get visibleCharts() {
            return this.chartItems.slice(0, this.currentPage * this.pageSize);
        },

        get hasMore() {
            return this.visibleCharts.length < this.chartItems.length;
        },

        async init() {
            await this.loadAll();
            this.$nextTick(() => {
                this.setupIntersectionObserver();
            });
        },

        async loadAll() {
            this.isLoading = true;
            this.hasError = false;

            try {
                // Fetch both in parallel
                const [qcRes, reportRes] = await Promise.all([
                    fetch('/reports/question-charts'),
                    fetch('/reports/get-chart-report'),
                ]);

                const [qcJson, reportJson] = await Promise.all([
                    qcRes.json(),
                    reportRes.json(),
                ]);

                if (!qcJson.success)    throw new Error(qcJson.message || 'Failed to load question charts');
                if (!reportJson.success) throw new Error(reportJson.message || 'Failed to load report data');

                // Build a lookup map: question_id → report data
                const reportMap = {};
                (reportJson.data || []).forEach(r => {
                    reportMap[r.question_id] = r;
                });

                // Merge: use questionCharts order, enrich with report data + chart_type
                this.chartItems = (qcJson.data || [])
                    .filter(qc => reportMap[qc.question_id]) // only those that have report data
                    .map(qc => {
                        const report = reportMap[qc.question_id];
                        return {
                            question_id: qc.question_id,
                            question:    report.question,
                            type:        report.type,
                            label:       report.label,
                            chart_type:  qc.chart_type,  // from user's configuration
                            report:      report.report,
                        };
                    });

                // Render the first batch of charts after DOM updates
                this.$nextTick(() => {
                    setTimeout(() => this.renderVisibleCharts(), 150);
                });

            } catch (err) {
                console.error('Report load error:', err);
                this.hasError = true;
                this.errorMessage = err.message;
            } finally {
                this.isLoading = false;
            }
        },

        refresh() {
            this.chartItems = [];
            this.currentPage = 1;
            this.renderedCharts = {};
            this.loadAll();
        },

        // ─── Intersection Observer for infinite scroll ────────────────────────
        setupIntersectionObserver() {
            if (this.observer) this.observer.disconnect();

            const sentinel = this.$refs.sentinel;
            if (!sentinel) return;

            this.observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && this.hasMore && !this.isLoadingMore) {
                        this.loadNextPage();
                    }
                });
            }, { threshold: 0.1 });

            this.observer.observe(sentinel);
        },

        async loadNextPage() {
            if (!this.hasMore || this.isLoadingMore) return;

            this.isLoadingMore = true;
            // Small delay for UX feel
            await new Promise(r => setTimeout(r, 300));
            this.currentPage++;
            this.isLoadingMore = false;

            // Render newly visible charts
            this.$nextTick(() => {
                setTimeout(() => this.renderVisibleCharts(), 100);
            });
        },

        // ─── Chart rendering ─────────────────────────────────────────────────
        renderVisibleCharts() {
            this.visibleCharts.forEach(item => {
                if (item.report && item.report.total_responses > 0) {
                    this.renderChart(item);
                }
            });
        },

        // renderChart(item) {
        //     const containerId = `chart-${item.question_id}`;

        //     // Don't re-render if already done
        //     if (this.renderedCharts[item.question_id]) return;

        //     const el = document.getElementById(containerId);
        //     if (!el) return;

        //     const chartType = item.chart_type;
        //     let options = null;

        //     switch (chartType) {
        //         case 'bar':
        //             options = this.buildBarOptions(item);
        //             break;
        //         case 'pie':
        //             options = this.buildPieOptions(item);
        //             break;
        //         case 'donut':
        //             options = this.buildDonutOptions(item);
        //             break;
        //         case 'line':
        //             options = this.buildLineOptions(item);
        //             break;
        //         case 'radialBar':
        //             options = this.buildRadialBarOptions(item);
        //             break;
        //         case 'area':
        //             options = this.buildAreaOptions(item);
        //             break;
        //         case 'text_list':
        //             // Rendered via Alpine template, no ApexChart needed
        //             return;
        //         default:
        //             // Fallback to bar
        //             options = this.buildBarOptions(item);
        //     }

        //     if (options) {
        //         try {
        //             const chart = new ApexCharts(el, options);
        //             chart.render();
        //             this.renderedCharts[item.question_id] = chart;
        //         } catch (e) {
        //             console.warn(`Chart render failed for question ${item.question_id}:`, e);
        //         }
        //     }
        // },

        renderChart(item) {
            const containerId = `chart-${item.question_id}`;
            if (this.renderedCharts[item.question_id]) return;

            const el = document.getElementById(containerId);
            if (!el) return;

            // ── NEW: normalize rating_with_text into a shape the builders understand ──
            let normalizedItem = item;
            if (item.type === 'rating_with_text' && item.report?.answers?.length) {
                const { labels, counts, average, min, max } = this._getAnswersAggregated(item);
                normalizedItem = {
                    ...item,
                    report: {
                        ...item.report,
                        options: labels.map((text, i) => ({
                            option_id: i,
                            text,
                            count: counts[i],
                            percentage: parseFloat(((counts[i] / item.report.total_responses) * 100).toFixed(2)),
                        })),
                        average: parseFloat(average?.toFixed(2) ?? 0),
                        min_val: min,
                        max_val: max,
                        avg_val: parseFloat(average?.toFixed(2) ?? 0),
                    },
                };
            }

            const chartType = normalizedItem.chart_type;
            let options = null;

            switch (chartType) {
                case 'bar':      options = this.buildBarOptions(normalizedItem);      break;
                case 'pie':      options = this.buildPieOptions(normalizedItem);      break;
                case 'donut':    options = this.buildDonutOptions(normalizedItem);    break;
                case 'line':     options = this.buildLineOptions(normalizedItem);     break;
                case 'radialBar':options = this.buildRadialBarOptions(normalizedItem);break;
                case 'area':     options = this.buildAreaOptions(normalizedItem);     break;
                case 'text_list': return;
                default:         options = this.buildBarOptions(normalizedItem);
            }

            if (options) {
                try {
                    const chart = new ApexCharts(el, options);
                    chart.render();
                    this.renderedCharts[item.question_id] = chart;
                } catch (e) {
                    console.warn(`Chart render failed for question ${item.question_id}:`, e);
                }
            }
        },

        // Chart answer Builder
        _getAnswersAggregated(item) {
            const answers = item.report?.answers ?? [];
            
            // Separate numeric answers from text answers
            const numericAnswers = answers
                .map(a => parseFloat(a))
                .filter(a => !isNaN(a));
            
            if (!numericAnswers.length) return { labels: [], counts: [], average: null };

            // Build a frequency map: value → count
            const freq = {};
            numericAnswers.forEach(val => {
                freq[val] = (freq[val] || 0) + 1;
            });

            // Sort by numeric value
            const sortedKeys = Object.keys(freq).map(Number).sort((a, b) => a - b);

            return {
                labels: sortedKeys.map(String),
                counts: sortedKeys.map(k => freq[k]),
                average: numericAnswers.reduce((s, v) => s + v, 0) / numericAnswers.length,
                min: Math.min(...numericAnswers),
                max: Math.max(...numericAnswers),
            };
        },

        // ─── Chart option builders ────────────────────────────────────────────

        _getOptionLabelsAndCounts(item) {
            const options = item.report?.options ?? [];
            return {
                labels: options.map(o => o.text || `Option ${o.option_id}`),
                counts: options.map(o => o.count),
                percentages: options.map(o => o.percentage),
            };
        },

        buildBarOptions(item) {
            const { labels, counts } = this._getOptionLabelsAndCounts(item);

            // For numeric/rating questions
            if (!labels.length && item.report?.average !== undefined) {
                return this.buildNumericBarOptions(item);
            }

            return {
                chart: { type: 'bar', height: 220, toolbar: { show: false }, sparkline: { enabled: false } },
                series: [{ name: 'Responses', data: counts }],
                xaxis: { categories: labels, labels: { style: { fontSize: '11px' } } },
                yaxis: { labels: { formatter: v => Math.round(v) } },
                colors: ['#0d6efd'],
                dataLabels: { enabled: true, formatter: (val, opts) => {
                    const pct = item.report.options[opts.dataPointIndex]?.percentage ?? 0;
                    return `${val} (${pct}%)`;
                }},
                plotOptions: { bar: { borderRadius: 4, columnWidth: '55%' } },
                tooltip: { y: { formatter: (val, opts) => {
                    const pct = item.report.options[opts.dataPointIndex]?.percentage ?? 0;
                    return `${val} responses (${pct}%)`;
                }}},
            };
        },

        buildNumericBarOptions(item) {
            const { avg_val, min_val, max_val } = item.report;
            return {
                chart: { type: 'bar', height: 220, toolbar: { show: false } },
                series: [{ name: 'Value', data: [min_val, avg_val, max_val] }],
                xaxis: { categories: ['Min', 'Average', 'Max'] },
                colors: ['#198754'],
                plotOptions: { bar: { borderRadius: 4, columnWidth: '40%' } },
                dataLabels: { enabled: true },
            };
        },

        buildPieOptions(item) {
            const { labels, counts } = this._getOptionLabelsAndCounts(item);
            return {
                chart: { type: 'pie', height: 220, toolbar: { show: false } },
                series: counts,
                labels: labels,
                legend: { position: 'bottom', fontSize: '11px' },
                dataLabels: { formatter: (val, opts) => {
                    return `${opts.w.globals.labels[opts.seriesIndex]}: ${val.toFixed(1)}%`;
                }},
            };
        },

        buildPieOptions(item) {
            const { labels, counts } = this._getOptionLabelsAndCounts(item);
            return {
                chart: { type: 'pie', height: 350, toolbar: { show: false } },  // ← 220 → 350
                series: counts,
                labels: labels,
                legend: { position: 'bottom', fontSize: '11px' },
                dataLabels: { formatter: (val, opts) => {
                    return `${opts.w.globals.labels[opts.seriesIndex]}: ${val.toFixed(1)}%`;
                }},
            };
        },

        // buildDonutOptions(item) {
        //     const { labels, counts } = this._getOptionLabelsAndCounts(item);
        //     return {
        //         chart: { type: 'donut', height: 220, toolbar: { show: false } },
        //         series: counts,
        //         labels: labels,
        //         legend: { position: 'bottom', fontSize: '11px' },
        //         plotOptions: { pie: { donut: { size: '65%', labels: {
        //             show: true,
        //             total: { show: true, label: 'Total', formatter: () => item.report.total_responses }
        //         }}}},
        //     };
        // },

        buildLineOptions(item) {
            const { labels, counts } = this._getOptionLabelsAndCounts(item);

            // For numeric: show avg as a single point; fallback gracefully
            const series = labels.length
                ? [{ name: 'Responses', data: counts }]
                : [{ name: 'Average', data: [item.report?.average ?? 0] }];

            const categories = labels.length ? labels : ['Average'];

            return {
                chart: { type: 'line', height: 220, toolbar: { show: false }, zoom: { enabled: false } },
                series,
                xaxis: { categories, labels: { style: { fontSize: '11px' } } },
                colors: ['#0dcaf0'],
                stroke: { curve: 'smooth', width: 3 },
                markers: { size: 5 },
                dataLabels: { enabled: false },
            };
        },

        buildAreaOptions(item) {
            const { labels, counts } = this._getOptionLabelsAndCounts(item);
            return {
                chart: { type: 'area', height: 220, toolbar: { show: false }, zoom: { enabled: false } },
                series: [{ name: 'Responses', data: counts }],
                xaxis: { categories: labels, labels: { style: { fontSize: '11px' } } },
                colors: ['#6f42c1'],
                stroke: { curve: 'smooth', width: 2 },
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } },
                dataLabels: { enabled: false },
            };
        },

        buildRadialBarOptions(item) {
            // Best for single metric / average as percentage
            const avg = item.report?.average ?? 0;
            const max = item.report?.max ?? 10;
            const pct = max ? Math.round((avg / max) * 100) : 0;

            return {
                chart: { type: 'radialBar', height: 220, toolbar: { show: false } },
                series: [pct],
                labels: ['Average'],
                plotOptions: { radialBar: {
                    hollow: { size: '55%' },
                    dataLabels: {
                        name: { fontSize: '12px' },
                        value: { fontSize: '18px', fontWeight: 700, formatter: () => avg },
                    }
                }},
                colors: ['#fd7e14'],
            };
        },
    };
}
</script>

<script src="<?php echo e(asset('assets/js/counter/custom-counter1.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/tooltip-init.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ecc-pain-new\resources\views/reports/chart-report.blade.php ENDPATH**/ ?>