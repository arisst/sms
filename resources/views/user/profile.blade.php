@extends('app')
@section('content')

<body>
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default">
				<div class="panel-heading">
					Ubah Profil
				</div>

				<div class="panel-body">
					<div id="detail">
						<form class="form-horizontal" id="form-profile">
						  <div class="form-group">
						    <label for="name" class="col-sm-2 control-label">Nama</label>
						    <div class="col-sm-10">
						      <input type="text" name="name" class="form-control input-sm" id="name" placeholder="Nama" value="{{Auth::user()->name}}">
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="email" class="col-sm-2 control-label">Email</label>
						    <div class="col-sm-10">
						      <input type="text" name="email" class="form-control input-sm" id="email" placeholder="Email" value="{{Auth::user()->email}}">
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="username" class="col-sm-2 control-label">Username</label>
						    <div class="col-sm-10">
						      <input type="text" name="username" class="form-control input-sm" id="username" placeholder="Username" value="{{Auth::user()->username}}">
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="password" class="col-sm-2 control-label">Password</label>
						    <div class="col-sm-10">
						      <input type="password" name="password" class="form-control input-sm" id="password" placeholder="Password">
						    </div>
						  </div>
						   <div class="form-group">
						    <label for="passconf" class="col-sm-2 control-label">Konf Password</label>
						    <div class="col-sm-10">
						      <input type="password" name="passconf" class="form-control input-sm" id="passconf" placeholder="Konfirmasi Password">
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="api_key" class="col-sm-2 control-label">Api Key</label>
						    <div class="col-sm-10">
						      <input type="text" name="api_key" class="form-control input-sm" id="api_key" placeholder="Api Key" value="{{Auth::user()->api_key}}">
						      <p class="help-block">Api Key digunakan untuk akses mobile app</p>
						    </div>
						  </div>
						  
						  <div class="form-group">
						    <div class="col-sm-offset-2 col-sm-10">
						      <a id="submit-button" class="btn btn-default btn-sm">Simpan</a>
						    </div>
						  </div>
						</form>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	$('#submit-button').click(function(){
		var name = $('#name').val();
		var email = $('#email').val();
		var username = $('#username').val();
		var password = $('#password').val();
		var passconf = $('#passconf').val();
		var api_key = $('#api_key').val();
		if(name && email && username && (password == passconf)){
			$.post("{{url('profile')}}", {
				name:name,
				email:email,
				username:username,
				password:password,
				api_key:api_key
			}, function(data, status){
				swal({title: "info",text: data['msg'],type: "info" });
				window.setTimeout(function(){location.reload()},1000);
			});
		}
		else
		{
			swal({title: "Lengkapi data anda!",text: "Cek setiap inputan yang anda masukkan.",type: "error" });
			$("#form-profile")[0].reset();
		}
	});

</script>

@endsection