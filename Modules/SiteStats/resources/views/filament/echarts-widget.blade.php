<x-filament-widgets::widget>
    <div class="mw-stats-card" wire:ignore>
        {{-- Header row: icon + title + online count | period tabs --}}
        <div class="mw-stats-card-header">
            <div class="mw-stats-card-header-left">
                <div class="mw-stats-card-icon">
                    <x-filament::icon icon="heroicon-o-chart-bar" class="mw-stats-card-icon-svg" />
                </div>
                <div class="mw-stats-card-header-text">
                    <p class="mw-stats-card-title">Statistics</p>
                    <div class="mw-stats-card-online">
                        <h5 class="mw-stats-card-online-count">{{ $this->getOnlineCount() }}</h5>
                        <span class="mw-stats-card-online-label">Online</span>
                    </div>
                </div>
            </div>
            <div class="mw-stats-card-header-right">
                <label class="mw-stats-card-period-label">
                    <input type="radio" name="mw-chart-period" value="daily" checked>
                    <span>Daily</span>
                </label>
                <label class="mw-stats-card-period-label">
                    <input type="radio" name="mw-chart-period" value="weekly">
                    <span>Weekly</span>
                </label>
                <label class="mw-stats-card-period-label">
                    <input type="radio" name="mw-chart-period" value="monthly">
                    <span>Monthly</span>
                </label>
            </div>
        </div>

        {{-- Chart area --}}
        <div id="mw-echarts-container" style="width: 100%; height: 200px;"></div>

        {{-- Footer: views + visitors counters | Show More --}}
        @php $chartData = $this->getChartData(); @endphp
        <div class="mw-stats-card-footer">
            <div class="mw-stats-card-footer-left">
                <div class="mw-stats-card-footer-stat" title="Views">
                    <x-filament::icon icon="heroicon-o-heart" class="mw-stats-card-footer-icon" />
                    <span>{{ number_format($chartData['totalVisitors']) }}</span>
                </div>
                <div class="mw-stats-card-footer-stat" title="Visitors">
                    <x-filament::icon icon="heroicon-o-user" class="mw-stats-card-footer-icon" />
                    <span>{{ number_format($chartData['totalVisitors']) }}</span>
                </div>
            </div>
            <div class="mw-stats-card-footer-right">
                <span class="mw-stats-card-show-more">Show more</span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
    <script>
        (function() {
            const chartData = @json($chartData);
            const container = document.getElementById('mw-echarts-container');
            if (!container || !chartData) return;

            const chart = echarts.init(container);

            function renderChart(labels, visitors, title) {
                const option = {
                    tooltip: {
                        trigger: 'axis',
                        backgroundColor: 'rgba(255,255,255,0.96)',
                        borderColor: '#dadfe5',
                        borderWidth: 1,
                        textStyle: { color: '#182433', fontSize: 12 },
                        axisPointer: { type: 'line', lineStyle: { color: '#4299e1', width: 1 } }
                    },
                    grid: {
                        left: 0, right: 0, top: 10, bottom: 30,
                        containLabel: false
                    },
                    xAxis: {
                        type: 'category',
                        data: labels,
                        boundaryGap: false,
                        axisLine: { show: false },
                        axisTick: { show: false },
                        axisLabel: { show: false }
                    },
                    yAxis: {
                        type: 'value',
                        splitLine: { lineStyle: { color: '#e0e0e0', type: 'solid' } },
                        axisLine: { show: false },
                        axisTick: { show: false },
                        axisLabel: { show: false }
                    },
                    series: [{
                        name: title,
                        type: 'line',
                        data: visitors,
                        smooth: true,
                        symbol: 'none',
                        lineStyle: { width: 2, color: '#4299e1' },
                        areaStyle: {
                            color: {
                                type: 'linear', x: 0, y: 0, x2: 0, y2: 1,
                                colorStops: [
                                    { offset: 0, color: 'rgba(66, 153, 225, 0.25)' },
                                    { offset: 1, color: 'rgba(66, 153, 225, 0.02)' }
                                ]
                            }
                        }
                    }]
                };

                chart.setOption(option);
            }

            renderChart(chartData.labels, chartData.visitors, chartData.title);

            // Handle window resize
            window.addEventListener('resize', () => chart.resize());

            // Handle period radio buttons
            document.querySelectorAll('input[name="mw-chart-period"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    // Trigger Livewire filter update
                    const widget = container.closest('[wire\\:id]');
                    if (widget) {
                        const componentId = widget.getAttribute('wire:id');
                        if (window.Livewire) {
                            // Dispatch to parent dashboard to update filters
                            window.Livewire.dispatch('updateFilter', { period: this.value });
                        }
                    }
                });
            });
        })();
    </script>
</x-filament-widgets::widget>
