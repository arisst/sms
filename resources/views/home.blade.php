@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Home</div>
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
