
  <script src="http://code.highcharts.com/highcharts-more.js"></script>

  <script src="http://code.highcharts.com/modules/solid-gauge.js"></script>


  <div style="width: 600px; height: 400px; margin: 0 auto">
    <div id="container-signal" style="width: 300px; height: 200px; float: left"></div>
  </div>
  <script type="text/javascript">
      $(function () {
        var gaugeOptions = {
            chart: {
                type: 'solidgauge'
            },
            title: null,
            pane: {
                center: ['50%', '85%'],
                size: '140%',
                startAngle: -90,
                endAngle: 90,
                background: {
                    backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
                    innerRadius: '60%',
                    outerRadius: '100%',
                    shape: 'arc'
                }
            },
            tooltip: {
                enabled: false
            },
            // the value axis
            yAxis: {
                stops: [
                    [0.1, '#DF5353'], // green
                    [0.5, '#DDDF0D'], // yellow
                    [0.9, '#55BF3B'] // red
                ],
                lineWidth: 0,
                minorTickInterval: null,
                tickPixelInterval: 100,
                tickWidth: 0,
                title: {
                    y: -70
                },
                labels: {
                    y: 16
                }
            },
            plotOptions: {
                solidgauge: {
                    dataLabels: {
                        y: 5,
                        borderWidth: 0,
                        useHTML: true
                    }
                }
            }
        };
        // The speed gauge
        $('#container-signal').highcharts(Highcharts.merge(gaugeOptions, {
            yAxis: {
                min: 0,
                max: 100,
                title: {
                    text: 'Kekuatan Sinyal'
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Signal',
                data: [{{$data}}],
                dataLabels: {
                    format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                        ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' +
                           '<span style="font-size:12px;color:silver">Persen</span></div>'
                },
                tooltip: {
                    valueSuffix: ' %'
                }
            }]
        }));
    });
  </script>
