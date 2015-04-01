@extends('app')
@section('content')

<body onload="firstLoad(window.location.hash.substring(1))">
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default">
				<div class="panel-heading">
					Format SMS / Kata Kunci
					<div class="pull-right">
						<a class="btn-sm" title="Tambah format baru" href="#" onclick="firstLoad()"><span style="color:green" class="glyphicon glyphicon-plus"></span>Tambah baru</a>
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
									    <label for="name" class="col-sm-2 control-label">Nama *</label>
									    <div class="col-sm-10">
									      <input type="text" name="name" class="form-control input-sm" id="name" placeholder="Nama Kata Kunci" required>
									    </div>
									  </div>

									  <label>Filter *</label>

									  <div class="form-group">
									    <label for="keyword" class="col-sm-2 control-label">Kata Kunci</label>
									    <div class="col-sm-10">
									      <input type="text" name="keyword" class="form-control input-sm" id="keyword" placeholder="Kata Kunci">
									      <p class="help-block">Untuk menyaring SMS yang masuk dengan kata pertama</p>
									    </div>
									  </div>

									 {{--  <div class="form-group">
									    <label for="group" class="col-sm-2 control-label">Group</label>
									    <div class="col-sm-10">
									      <input type="text" name="group" class="form-control input-sm" id="group" placeholder="Group">
									    </div>
									  </div> --}}

									  <label>Action *</label>

									  <div class="form-group">
									    <label for="url" class="col-sm-2 control-label">URL *</label>
									    <div class="col-sm-10">
									      <input type="text" name="url" class="form-control input-sm" id="url" placeholder="URL">
									      <p class="help-block">Untuk meneruskan data ke website lain</p>
									    </div>
									  </div>

									  <div class="form-group">
									    <label for="gname" class="col-sm-2 control-label">Ke Group</label>
									    <div class="col-sm-10">
									      <input type="hidden" name="joingroup_id" id="joingroup_id">
									      <input type="text" name="gname" class="form-control input-sm" id="gname" placeholder="Group">
									      <p class="help-block">Memasukkan nomor ke dalam group</p>
									    </div>
									  </div>

									  <div class="form-group">
									    <label for="text_reply" class="col-sm-2 control-label">Balas otomatis</label>
									    <div class="col-sm-10">
									      <textarea name="text_reply" class="form-control input-sm" id="text_reply" placeholder="Isi balasan"></textarea>
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

				<div class="row"></div>

				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	/* ON FIRST LOAD */
	function firstLoad (detail) {
		getData();
		detail = (typeof detail !== 'undefined') ? detail : '';
		Detail(detail);
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
			$("#listcontact").html('<img src="{{asset("img/loadsmall.gif")}}">');
		});
		$(document).bind("ajaxStop.mine", function() {
			// alert('loaded');
		});
		$.get("{{url('keyword')}}?page="+page+"&term="+term, function(data,status){
			var res= '';
			current_page = data['current_page'];
			last_page = data['last_page'];
			$.each(data['data'], function(i, item) {
			    res += '<a id="l-'+item.id+'" href="#" onclick="Detail('+item.id+');" class="list-group-item"><p class="list-group-item-heading"><input name="cid[]" value="'+item.id+'" type="checkbox" class="cg"> <b>'+item.name+'</b></p><p class="list-group-item-text">'+item.keyword+'</p></a>';
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
					$.post("{{url('keyword')}}/"+vals,
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

				$.get("{{url('keyword')}}/"+id, function(data,status){
					location.hash = id;
					$('input[name="name"]').val(data[0]['name']);
					$('input[name="keyword"]').val(data[0]['keyword']);
					$('input[name="url"]').val(data[0]['url']);
					$('input[name="joingroup_id"]').val(data[0]['joingroup_id']);
					$('input[name="gname"]').val(data[0]['gname']);
					$('textarea[name="text_reply"]').val(data[0]['text_reply']);
			    	$("#submit-button").html('Simpan');
			    	$("#submit-button").removeAttr('disabled');
			    	$('#submit-button').attr('onclick', 'Add('+id+');');
				    $('#title').html(data[0]['name']);
				});
				$(document).unbind(".mine1");
			}else{
			    formAdd();
			}
		}

	function formAdd(){
		$('#title').html('Tambah kata kunci baru');
	    $('input[name="name"]').val('');
		$('input[name="keyword"]').val('');
		$('input[name="url"]').val('');
		$('input[name="joingroup_id"]').val('');
		$('input[name="gname"]').val('');
		$('textarea[name="text_reply"]').val('');
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
		var keyword = $("input#keyword").val();
		var url = $("input#url").val();
		var joingroup_id = $("input#joingroup_id").val();
		var gname = $("input#gname").val();
		var text_reply = $("textarea#text_reply").val();
		if(nama){
			$.post("{{url('keyword')}}"+edit,
			{
				name:nama,
				keyword:keyword,
				url:url,
				joingroup_id:joingroup_id,
				gname:gname,
				text_reply:text_reply,
				_method:method,
				_token:"{{csrf_token()}}"
			},
			function(data,status){
				if(status=='success'){
					if(!id){
						$('input[name="name"]').val('');
						$('input[name="keyword"]').val('');
						$('input[name="url"]').val('');
					}
			  		firstLoad(data.id);
				}
			});
		}
	}
</script>

@endsection