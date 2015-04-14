@extends('app')
@section('content')

<body onload="firstLoad(window.location.hash.substring(1))">
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default">
				<div class="panel-heading">
					User
					<div class="pull-right">
						<a class="btn-sm" title="Tambah User Baru" href="#" onclick="firstLoad()"><span style="color:green" class="glyphicon glyphicon-plus"></span>Tambah Baru</a>
					</div>
				</div>

				<div class="panel-body">
					
				<div class="row">
					<div class="col-md-4">
					<input type="search" id="search" class="form-control input-sm" placeholder="Pencarian: masukkan nama">
					<div id="listarea" style="height:470px;overflow-x:hidden;overflow-y:auto">
						<div class="list-group" id="listcontact"></div>
						<div id="pagination" align="center">
						  <ul class="pagination pagination-sm">
						    <li><a id="prev" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
						    <li><a id="next" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
						  </ul>
						</div>
					</div>
					<a  id="checkdel" class="btn btn-danger" href="#" onClick="Hapus()">Hapus yang dipilih?</a>
					</div>

					<div class="col-md-8">
						<div class="panel panel-default">
							<div id="title" class="panel-heading"></div>
							<div name="detail" id="description" class="panel-body" style="height:458px;overflow-x:hidden;overflow-y:auto">
								<div id="detail">
									<form class="form-horizontal">
									  <div class="form-group">
									    <label for="name" class="col-sm-2 control-label">Nama</label>
									    <div class="col-sm-10">
									      <input type="text" name="name" class="form-control input-sm" id="name" placeholder="Nama">
									    </div>
									  </div>
									  <div class="form-group">
									    <label for="email" class="col-sm-2 control-label">Email</label>
									    <div class="col-sm-10">
									      <input type="text" name="email" class="form-control input-sm" id="email" placeholder="Email">
									    </div>
									  </div>
									  <div class="form-group">
									    <label for="username" class="col-sm-2 control-label">Username</label>
									    <div class="col-sm-10">
									      <input type="text" name="username" class="form-control input-sm" id="username" placeholder="Username">
									    </div>
									  </div>
									  <div class="form-group">
									    <label for="group" class="col-sm-2 control-label">Group Level</label>
									    <div class="col-sm-10">
									      <select class="form-control input-sm" id="group" name="group">
									      	<option value="0">--Pilih level--</option>
									      	<option value="1">Administrator</option>
									      	<option value="2">User</option>
									      	<option value="3">Unconfirm</option>
									      </select>
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
									      <input type="text" name="api_key" class="form-control input-sm" id="api_key" placeholder="Api Key">
									      <p class="help-block">Api Key digunakan untuk akses mobile app</p>
									    </div>
									  </div>
									  
									  <div class="form-group">
									    <div class="col-sm-offset-2 col-sm-10">
									      <a id="submit-button" onclick="Add()" class="btn btn-default btn-sm">Tambah</a>
									    </div>
									  </div>
									</form>
								</div>
							</div>
							
						</div>
					</div>
				</div>

				<div class="row">
				</div>

				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	/* ON FIRST LOAD */
	function firstLoad (detail) {
		getData();
		var part = location.hash.split('/');
		// console.log(part);
		if(part[0]=='#!' && part[1]=='add'){
			formAdd(part[2]);
		}else{
			detail = (typeof detail !== 'undefined') ? detail : '';
			Detail(detail);
		}

	}

	/* READY FUNCTION */
	$(document).ready(function(){
		/* SEARCH */
		$("#search").keyup(function(){
			getData($(this).val());
		});
	});

	/* PAGINATION */
	var current_page = '';
	var last_page = '';
	$("#next").click(function(){
		if(current_page<last_page){
			getData('', current_page+1);
		}
	});
	$("#prev").click(function(){
		if(current_page>1){
			getData('', current_page-1);
		}
	});

	/* AUTOCOMPLETE */
	$(function() {
		$("#group").autocomplete(
		{
			 source: "{{url('group')}}/0",
			 select: function( event, ui ) {
				$("#group").val(ui.item.label);
				$("#groupid").val(ui.item.id);
				return false;
			}
		})
	});

	/* CHECK FUNCTION */
	function checkAll(source) {
		// $("#checkdel").show();
		checkboxes = document.getElementsByName('cid[]');
		for(var i=0, n=checkboxes.length;i<n;i++) {
		   checkboxes[i].checked = source.checked;
		}
	}

	/* GET DATA FROM SERVER */
	function getData (term,page) {
		term = typeof term !== 'undefined' ? term : '';
		page = typeof page !== 'undefined' ? page : 1;
		$(document).bind("ajaxStart.mine", function() {
			$("#listcontact").html('<img src="{{asset("img/loadsmall.gif")}}">');
		});
		$(document).bind("ajaxStop.mine", function() {
			// alert('loaded');
		});
		$.get("{{url('user')}}?page="+page+"&term="+term, function(data,status){
			var res= '';
			current_page = data['current_page'];
			last_page = data['last_page'];
			$.each(data['data'], function(i, item) {
			    res += '<a id="l-'+item.id+'" href="#" onclick="Detail(\''+item.id+'\');" class="list-group-item"><p class="list-group-item-heading"><input name="cid[]" value="'+item.id+'" type="checkbox" class="cg"> <b>'+item.name+'</b></p><p class="list-group-item-text">'+item.email+'</p></a>';
			})
			$("#listcontact").html(res);
		});
		$(document).unbind(".mine");
	}

	/* DELETE WITH CHECKLIST */
	function Hapus(){
		var checkboxes = document.getElementsByName('cid[]');
		var vals = [];
		for (var i=0, n=checkboxes.length;i<n;i++) {
		  if (checkboxes[i].checked) 
		  {
		  vals[vals.length] = checkboxes[i].value;
		  }
		}
		if(vals.length){
			swal({
				title: "Anda yakin?",
				text: "Data yang sudah dihapus tidak dapat dikembalikan!",   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",   
				confirmButtonText: "Hapus",   
				closeOnConfirm: true }, 

				function(){   
					$.post("{{url('user')}}/"+vals,
					{
						id:vals, _method:"DELETE",	_token:"{{csrf_token()}}"
					},
					function(data,status){
						if(status=='success'){
					    	Detail('');
					    	getData();
						}
					});
				});
		}else{
			swal({title: "Pilih data yang akan dihapus!",text: "Akan tertutup setelah 2 detik.",timer: 2000,type: "info" });
		}
	}

	/* GET DETAIL */
	function Detail(id)
		{
			if(id){
				$("[id^='l-']").removeClass('active');
				$("#l-"+id).addClass('active');
			    $(document).bind("ajaxStart.mine1",function(){
					$("#title").html('<img src="{{asset("img/loadsmall.gif")}}">');
			    	$("#submit-button").attr('disabled','disabled');
			    });
			    $(document).ajaxError(function(event, jqxhr, settings, exception) {
				    if (jqxhr.status==401) {
				        location.reload(false);
				    }
				});

				$.get("{{url('user')}}/"+id, function(data,status){
					location.hash = id;
					$('input[name="name"]').val(data['name']);
					$('input[name="email"]').val(data['email']);
					$('input[name="username"]').val(data['username']);
					$('#group').val(data['group']);
					$('input[name="api_key"]').val(data['api_key']);
					
					var ip = '';
					if (data['access_ip']!='') { ip = '<br><br>Hanya dapat diakses oleh ip : '+data['access_ip'];};
					var url = "{{url('kirimsms')}}"+'?token='+data['token']+'&message=[isi_sms]&number=[nomor_tujuan]'+ip;
					$('#url').html(url);
			    	$("#submit-button").html('Simpan');
			    	$("#submit-button").removeAttr('disabled');
			    	$('#submit-button').attr('onclick', 'Add('+id+');');
				    $('#title').html(data['name']);
				});
				$(document).unbind(".mine1");
			}else{
			    formAdd();
			}
		}

	function formAdd(){
		$('#title').html('Tambah User baru');
	    $('input[name="name"]').val('');
		$('input[name="email"]').val('');
		$('input[name="username"]').val('');
		$('input[name="password"]').val('');
		$('input[name="passconf"]').val('');
		$('#group').val('0');
		$('input[name="api_key"]').val('');
		$('#url').html('');
		$("#submit-button").html('Tambah');
    	$("#submit-button").removeAttr('disabled');
    	$('#submit-button').attr('onclick', 'Add();');
	}

	/* ADD AND EDIT TRANSACTION */
	function Add(id) 
	{
		var edit = method = '';
		if(id){ 
			edit = '/'+id;
			method = 'PUT'; 
		}else{
			method = 'POST'; 
		}
		var nama = $("input#name").val();
		var email = $('input[name="email"]').val();
		var username = $('input[name="username"]').val();
		var group = $('#group').val();
		var api_key = $('input[name="api_key"]').val();
		var password = $('input[name="password"]').val();
		var passconf = $('input[name="passconf"]').val();
		if(nama && email && username && group && password && (password==passconf))
		{
			$.post("{{url('user')}}"+edit,
			{
				name:nama,
				email:email,
				username:username,
				password:password,
				group:group,
				api_key:api_key,
				_method:method,
				_token:"{{csrf_token()}}"
			},
			function(data,status){
				if(status=='success'){
					if(!id){
						formAdd();
					}
			  		firstLoad(data.id);
				}
			});
		}
		else
		{
			alert('Periksa kembali masukan anda!');
		}
	}
</script>

@endsection