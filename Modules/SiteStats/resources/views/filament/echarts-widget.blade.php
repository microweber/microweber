<x-filament-widgets::widget>
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.5.1/dist/echarts.min.js" integrity="sha256-6EJwvQzVvfYP78JtAMKjkcsugfTSanqe4WGFpUdzo88=" crossorigin="anonymous"></script>

    @php $chartData = $this->getChartData(); @endphp

    <div
        class="mw-stats-card"
        wire:ignore
        x-data="{
            period: @js($this->period),
            chartData: @js($chartData),
            onlineCount: {{ $this->getOnlineCount() }},
            chart: null,
            loading: false,

            init() {
                this.$nextTick(() => {
                    this.chart = echarts.init(this.$refs.chartContainer);
                    this.renderChart();
                    window.addEventListener('resize', () => this.chart?.resize());
                });
            },

            renderChart() {
                if (!this.chart || !this.chartData) return;
                this.chart.setOption({
                    tooltip: {
                        trigger: 'axis',
                        backgroundColor: 'rgba(255,255,255,0.96)',
                        borderColor: '#dadfe5',
                        borderWidth: 1,
                        textStyle: { color: '#182433', fontSize: 12 },
                        axisPointer: { type: 'line', lineStyle: { color: '#4299e1', width: 1 } }
                    },
                    grid: { left: 0, right: 0, top: 10, bottom: 30, containLabel: false },
                    xAxis: {
                        type: 'category',
                        data: this.chartData.labels,
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
                        name: this.chartData.title,
                        type: 'line',
                        data: this.chartData.visitors,
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
                });
            },

            async changePeriod(newPeriod) {
                if (this.period === newPeriod || this.loading) return;
                this.period = newPeriod;
                this.loading = true;
                try {
                    this.chartData = await $wire.updatePeriod(newPeriod);
                    this.renderChart();
                } finally {
                    this.loading = false;
                }
            }
        }"
    >
        {{-- Header row: icon + title + online count | period tabs --}}
        <div class="mw-stats-card-header">
            <div class="mw-stats-card-header-left">
                <div class="mw-stats-card-icon">
                    <x-filament::icon icon="heroicon-o-chart-bar" class="mw-stats-card-icon-svg" />
                </div>
                <div class="mw-stats-card-header-text">
                    <p class="mw-stats-card-title">Statistics</p>
                    <div class="mw-stats-card-online">
                        <span class="mw-stats-card-online-count" x-text="onlineCount"></span>
                        <span class="mw-stats-card-online-label">Online</span>
                    </div>
                </div>
            </div>
            <div class="mw-stats-card-header-right">
                <label class="mw-stats-card-period-label" :class="{ 'active': period === 'daily' }">
                    <input type="radio" name="mw-chart-period" value="daily" :checked="period === 'daily'" @change="changePeriod('daily')">
                    <span>Daily</span>
                </label>
                <label class="mw-stats-card-period-label" :class="{ 'active': period === 'weekly' }">
                    <input type="radio" name="mw-chart-period" value="weekly" :checked="period === 'weekly'" @change="changePeriod('weekly')">
                    <span>Weekly</span>
                </label>
                <label class="mw-stats-card-period-label" :class="{ 'active': period === 'monthly' }">
                    <input type="radio" name="mw-chart-period" value="monthly" :checked="period === 'monthly'" @change="changePeriod('monthly')">
                    <span>Monthly</span>
                </label>
            </div>
        </div>

        {{-- Chart area --}}
        <div x-ref="chartContainer" style="width: 100%; height: 200px;"></div>

        {{-- Loading overlay --}}
        <div x-show="loading" x-cloak class="mw-stats-card-loading" style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.6); z-index: 10;">
            <x-filament::loading-indicator class="h-6 w-6" />
        </div>

        {{-- Footer: visitors + bounce rate | Show More --}}
        <div class="mw-stats-card-footer">
            <div class="mw-stats-card-footer-left">
                <div class="mw-stats-card-footer-stat" title="Visitors">
                    <x-filament::icon icon="heroicon-o-user" class="mw-stats-card-footer-icon" />
                    <span x-text="Number(chartData.totalVisitors).toLocaleString()"></span>
                </div>
                <div class="mw-stats-card-footer-stat" title="Bounce rate">
                    <x-filament::icon icon="heroicon-o-arrow-uturn-left" class="mw-stats-card-footer-icon" />
                    <span x-text="chartData.bouncePercent + '%'"></span>
                </div>
            </div>
            <div class="mw-stats-card-footer-right">
                <span class="mw-stats-card-show-more">Show more</span>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
