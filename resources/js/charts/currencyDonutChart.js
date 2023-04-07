import ApexCharts from 'apexcharts';

const currencyDonutChart = () => {
  return {
    chart: null,

    init() {
      setTimeout(() => {
        this.drawChart(this.$wire);
      }, 0);
    },

    drawChart(component) {
      if (this.chart) {
        this.chart.destroy();
      }

      const title = component.get('pieChartModel.title');
      const animated = component.get('pieChartModel.animated') || false;
      const dataLabels = component.get('pieChartModel.dataLabels') || {};
      const onSliceClickEventName = component.get('pieChartModel.onSliceClickEventName');
      const data = component.get('pieChartModel.data');
      const sparkline = component.get('pieChartModel.sparkline');

      const options = {
        series: data.map(item => item.value),

        chart: {
          height: '100%',
          type: 'donut',

          ...sparkline,

          animations: { enabled: animated },

          events: {
            dataPointSelection: function (event, chartContext, config) {
              if (!onSliceClickEventName) {
                return;
              }

              const { dataPointIndex } = config;
              const slice = data[dataPointIndex];
              component.call('onSliceClick', slice);
            },
          }
        },

        labels: data.map(item => item.title),

        dataLabels: dataLabels,

        colors: data.map(item => item.color),

        fill: {
          opacity: component.get('pieChartModel.opacity'),
        },

        title: {
          text: title,
          align: 'center',
        },

        responsive: [
          {
            breakpoint: 600,
            options: {
              legend: {
                position: 'bottom'
              }
            }
          }
        ],

        legend: component.get('pieChartModel.legend') || {},

        tooltip: {
          y: {
            formatter: function (value, series) {
              return data[series.dataPointIndex].extras.tooltip || value;
            }
          }
        },
        plotOptions: {
          pie: {
            donut: {
              labels: {
                show: true,
                name: {
                  show: true,
                  offsetY: -10
                },
                value: {
                  show: true,
                  offsetY: 10,
                  formatter: function (val) {
                    return new Intl.NumberFormat('pt-BR', {
                      style: 'currency',
                      currency: 'BRL',
                    }).format(val);
                  }
                },
                total: {
                  show: true,
                  label: 'Total',
                  color: '#333333',
                  formatter: function (w) {
                    let total = w.globals.seriesTotals.reduce((a, b) => {
                      return a + b;
                    }, 0);

                    return new Intl.NumberFormat('pt-BR', {
                      style: 'currency',
                      currency: 'BRL',
                    }).format(total);
                  }
                }
              }
            }
          }
        },
      };

      const colors = component.get('pieChartModel.colors');

      if (colors && colors.length > 0) {
        options['colors'] = colors;
      }

      this.chart = new ApexCharts(this.$refs.container, options);
      this.chart.render();
    }
  };
};

export default currencyDonutChart;
