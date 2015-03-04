@extends('app')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default">
				<div class="panel-heading">Inbox Create</div>

				<div class="panel-body">

					<!-- SEARCH -->
					<div id="search_block" class="panel-body">
					    <form role="form" class="form-horizontal" method="post" action="{{url('inbox')}}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
					       <div class="form-group">
						    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
						    <div class="col-sm-10">
						      <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
						    <div class="col-sm-10">
						      <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
						    </div>
						  </div>
						  <div class="form-group">
						    <div class="col-sm-offset-2 col-sm-10">
						      <div class="checkbox">
						        <label>
						          <input type="checkbox"> Remember me
						        </label>
						      </div>
						    </div>
						  </div>
						  <div class="form-group">
						    <div class="col-sm-offset-2 col-sm-10">
						      <button type="submit" class="btn btn-default">Sign in</button>
						    </div>
						  </div>
					    </form>
					</div>
					<!-- END SEARCH -->
					</div>
					</div>
					</div>
					</div>
					</div>
@endsection