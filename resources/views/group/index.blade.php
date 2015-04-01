@extends('app')
@section('content')

<body onload="firstLoad(window.location.hash.substring(1))">
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default">
				<div class="panel-heading">
					Group
					<div class="pull-right">
						<a class="btn-sm" title="Tambah group baru" href="#" onclick="firstLoad()"><span style="color:green" class="glyphicon glyphicon-plus"></span>Tambah baru</a>
					</div>
				</div>

				<div class="panel-body">
					
				<div class="row">
					<div class="col-md-4">
					<input type="search" id="search" class="form-control input-sm" placeholder="Pencarian: masukkan nama group">
					<div id="listarea" style="height:470px;overflow-x:hidden;overflow-y:auto">
						<div class="list-group" id="listgroup"></div>
						{{-- <div id="pagination" align="center">
						  <ul class="pagination pagination-sm">
						    <li><a id="prev" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
						    <li><a id="next" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
						  </ul>
						</div> --}}
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
									    <label for="name" class="col-sm-2 control-label">Nama group</label>
									    <div class="col-sm-10">
									      <input type="text" name="name" class="form-control input-sm" id="name" placeholder="Nama group" required>
									    </div>
									  </div>
									  <div class="form-group">
									    <div class="col-sm-offset-2 col-sm-10">
									      <a id="submit-button" onclick="Add()" class="btn btn-default btn-sm">Tambah</a>
									    </div>
									  </div>
									</form>
								</div>

								<div class="member">
									
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
			$("#listgroup").html('<img src="{{asset("img/loadsmall.gif")}}">');
		});
		$(document).bind("ajaxStop.mine", function() {
			// alert('loaded');
		});
		$.get("{{url('group')}}?page="+page+"&term="+term, function(data,status){
			var res= '';
			current_page = data['current_page'];
			last_page = data['last_page'];
			$.each(data, function(i, item) {
			    res += '<a id="l-'+item.ID+'" href="#" onclick="Detail('+item.ID+');" class="list-group-item"><p class="list-group-item-heading"><input name="cid[]" value="'+item.ID+'" type="checkbox" class="cg"> <b>'+item.Name+' <span class="badge">'+item.count+'</span></b></p><p class="list-group-item-text">'+/*item.Number+*/'</p></a>';
			})
			$("#listgroup").html(res);
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
					$.post("{{url('group')}}/"+vals,
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

				$.get("{{url('group')}}/"+id, function(data,status){
					location.hash = id;
					$('input[name="name"]').val(data[0]['Name']);
			    	$("#submit-button").html('Simpan');
			    	$("#submit-button").removeAttr('disabled');
			    	$('#submit-button').attr('onclick', 'Add('+id+');');
				    $('#title').html(data[0]['Name']);

				    var res = '';
				    $.each(data, function(i, item) 
				    {
					    if (item.Number)
					    {
					    	res += '<a id="l-'+item.ID+'" href="#" class="list-group-item"><p class="list-group-item-heading"><b>'+item.cname+'</b></p><p class="list-group-item-text">'+item.Number+'<span class="pull-right" onclick=alert("oi") title="Remove from this group"><span style="color:red" class="glyphicon glyphicon-remove-circle"></span></span></p></a>';
					    }
					    else
					    {
					    	res = 'Tidak ada data';
					    }
					})
					$(".member").html(res);
				});
				$(document).unbind(".mine1");
			}else{
			    formAdd();
			}
		}

	function formAdd(number){
		$('#title').html('Tambah group baru');
	    $('input[name="name"]').val('');
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
		if(nama){
			$.post("{{url('group')}}"+edit,
			{
				name:nama,
				_method:method,
				_token:"{{csrf_token()}}"
			},
			function(data,status){
				if(status=='success'){
					if(!id){
						$('input[name="name"]').val('');
					}
			  		firstLoad(data.id);
				}
			});
		}
	}
</script>

@endsection