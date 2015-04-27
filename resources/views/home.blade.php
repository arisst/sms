@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">
				Dashboard
					<div class="pull-right">
						Credit
						<span data-toggle="popover" data-html="true" title="Tentang Aplikasi" data-content="Aplikasi ini dikembangkan oleh <a href='http://airputih.or.id' target='_blank'>AirPutih</a><br>Programmer: <a href='http://arisst.com' target='_blank'>Aris</a><br>Source code: <a href='https://github.com/arisst/sms' target='_blank'>Github</a>" class="glyphicon glyphicon-question-sign"></span>
					</div>
				</div>
  <script src="http://code.highcharts.com/highcharts.js"></script>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-4">
							@include('chart.signal', ['data'=>$data['signal']])
						</div>
						<div class="col-md-8">
							@include('chart.stats', ['data'=>$data['category']])
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
