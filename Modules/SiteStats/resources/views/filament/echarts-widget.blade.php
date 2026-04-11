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
            expanded: false,

            init() {
                this.$nextTick(() => {
                    this.chart = echarts.init(this.$refs.chartContainer);
                    this.renderChart();
                    window.addEventListener('resize', () => { try { this.chart?.resize(); } catch(e) {} });
                });
            },

            hasData() {
                if (!this.chartData || !this.chartData.visitors) return false;
                return this.chartData.visitors.some(v => v > 0);
            },

            renderChart() {
                if (!this.chart || !this.chartData) return;

                const allZero = !this.hasData();
                const noDataEl = this.$refs.noDataOverlay;
                if (noDataEl) noDataEl.style.display = allZero ? 'flex' : 'none';

                const visitorsGradient = new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                    { offset: 0, color: 'rgba(66, 153, 225, 0.25)' },
                    { offset: 1, color: 'rgba(66, 153, 225, 0.02)' }
                ]);
                const bouncedGradient = new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                    { offset: 0, color: 'rgba(245, 101, 101, 0.15)' },
                    { offset: 1, color: 'rgba(245, 101, 101, 0.02)' }
                ]);

                const showBounced = this.expanded && this.chartData.bounced;
                const series = [{
                    name: 'Visitors',
                    type: 'line',
                    data: this.chartData.visitors,
                    smooth: true,
                    symbol: 'none',
                    lineStyle: { width: 2, color: '#4299e1' },
                    areaStyle: { color: visitorsGradient }
                }];

                if (showBounced) {
                    series.push({
                        name: 'Bounced',
                        type: 'line',
                        data: this.chartData.bounced,
                        smooth: true,
                        symbol: 'none',
                        lineStyle: { width: 2, color: '#f56565', type: 'dashed' },
                        areaStyle: { color: bouncedGradient }
                    });
                }

                const yMax = allZero ? 10 : undefined;

                this.chart.setOption({
                    tooltip: {
                        trigger: 'axis',
                        backgroundColor: 'rgba(255,255,255,0.96)',
                        borderColor: '#dadfe5',
                        borderWidth: 1,
                        textStyle: { color: '#182433', fontSize: 12 },
                        axisPointer: { type: 'line', lineStyle: { color: '#4299e1', width: 1 } }
                    },
                    legend: this.expanded ? {
                        show: true,
                        bottom: 0,
                        textStyle: { color: '#4a5568', fontSize: 12 }
                    } : { show: false },
                    grid: {
                        left: this.expanded ? 50 : 40,
                        right: 40,
                        top: 10,
                        bottom: this.expanded ? 40 : 30,
                        containLabel: true
                    },
                    xAxis: {
                        type: 'category',
                        data: this.chartData.labels,
                        boundaryGap: false,
                        axisLine: { show: this.expanded, lineStyle: { color: '#e0e0e0' } },
                        axisTick: { show: false },
                        axisLabel: {
                            show: true,
                            color: '#a0aec0',
                            fontSize: window.innerWidth < 768 ? 9 : 10,
                            interval: Math.max(0, Math.floor(this.chartData.labels.length / (window.innerWidth < 768 ? 4 : 6)) - 1),
                            rotate: window.innerWidth < 768 ? 45 : 0,
                            formatter: function(value) {
                                if (window.innerWidth < 768) {
                                    var parts = value.split('-');
                                    return parts.length === 3 ? parts[1] + '/' + parts[2] : value;
                                }
                                return value;
                            }
                        }
                    },
                    yAxis: {
                        type: 'value',
                        min: 0,
                        max: yMax,
                        splitNumber: allZero ? 2 : undefined,
                        splitLine: { lineStyle: { color: allZero ? '#f0f0f0' : '#e0e0e0', type: 'solid' } },
                        axisLine: { show: false },
                        axisTick: { show: false },
                        axisLabel: { show: this.expanded, color: '#4a5568', fontSize: 11 }
                    },
                    series: series
                }, true);
            },

            toggleExpanded() {
                this.expanded = !this.expanded;
                this.$nextTick(() => {
                    const height = this.expanded ? 320 : 200;
                    this.$refs.chartContainer.style.height = height + 'px';
                    if (this.chart) {
                        this.chart.dispose();
                        this.chart = echarts.init(this.$refs.chartContainer);
                    }
                    this.renderChart();
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
        <div style="position: relative;">
            <div x-ref="chartContainer" style="width: 100%; height: 200px; transition: height 0.3s ease;"></div>
            <div x-ref="noDataOverlay" style="display: none; position: absolute; inset: 0; align-items: center; justify-content: center; pointer-events: none;">
                <span style="color: #a0aec0; font-size: 13px; background: rgba(255,255,255,0.8); padding: 4px 12px; border-radius: 6px;">No visitor data for this period</span>
            </div>
        </div>

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
                <div class="mw-stats-card-footer-stat" x-show="expanded" title="Bounced sessions" x-cloak>
                    <x-filament::icon icon="heroicon-o-x-circle" class="mw-stats-card-footer-icon" />
                    <span x-text="Number(chartData.totalBounced).toLocaleString()"></span>
                </div>
            </div>
            <div class="mw-stats-card-footer-right">
                <span class="mw-stats-card-show-more" @click="toggleExpanded()" x-text="expanded ? 'Show less' : 'Show more'"></span>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
