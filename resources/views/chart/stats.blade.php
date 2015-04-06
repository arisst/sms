
<script src="http://code.highcharts.com/modules/exporting.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<script type="text/javascript">
function statData(data)
{
    $('#container').highcharts({
        chart: {
            type: 'area'
        },
        title: {
            text: 'Statistik trafik SMS'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: [
            	@foreach($data as $key)
            		"{{$key}}",
            	@endforeach 
            ],
            tickmarkPlacement: 'on',
            title: {
                enabled: false
            }
        },
        yAxis: {
            title: {
                text: 'Total SMS'
            },
            labels: {
                formatter: function () {
                    return this.value;// / 10;
                }
            }
        },
        tooltip: {
            shared: true,
            valueSuffix: ' sms'
        },
        plotOptions: {
            area: {
                // stacking: 'normal',
                lineColor: '#666666',
                lineWidth: 1,
                marker: {
                    lineWidth: 1,
                    lineColor: '#666666'
                }
            }
        },
        series: data
    });
}

$(document).ready(function() {
 $.ajax({
    url: "{{url('/')}}",
    type: 'GET',
    async: true,
    dataType: "json",
    success: function (data) {
        statData(data);
    }
  });
 });
</script>