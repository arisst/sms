@extends('app')
@section('content')

<body onload="firstLoad(window.location.hash.substring(1))">
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default">
				<div class="panel-heading">
					Keyword
					<div class="pull-right">
						<a class="btn-sm" title="Add new contact" href="#" onclick="firstLoad()"><span style="color:green" class="glyphicon glyphicon-plus"></span>Add new</a>
					</div>
				</div>

				<div class="panel-body">
					
				<div class="row">
					<div class="col-md-4">
					<input type="search" id="search" class="form-control input-sm" placeholder="Search name or number">
					<div id="listarea" style="height:470px;overflow-x:hidden;overflow-y:auto">
						<div class="list-group" id="listcontact"></div>
						<div id="pagination" align="center">
						  <ul class="pagination pagination-sm">
						    <li><a id="prev" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
						    <li><a id="next" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
						  </ul>
						</div>
					</div>
					<a  id="checkdel" class="btn btn-danger" href="#" onClick="Hapus()">With selected: Delete?</a>
					</div>

					<div class="col-md-8">
						<div class="panel panel-default">
							<div id="title" class="panel-heading"></div>
							<div name="detail" id="description" class="panel-body" style="height:458px;overflow-x:hidden;overflow-y:auto">
								<div id="detail">
									<form class="form-horizontal">
									  <div class="form-group">
									    <label for="name" class="col-sm-2 control-label">Name *</label>
									    <div class="col-sm-10">
									      <input type="text" name="name" class="form-control input-sm" id="name" placeholder="Name" required>
									    </div>
									  </div>

									  <br>
									  <label>Filter *</label>

									  <div class="form-group">
									    <label for="keyword" class="col-sm-2 control-label">Keyword</label>
									    <div class="col-sm-10">
									      <input type="text" name="keyword" class="form-control input-sm" id="keyword" placeholder="Keyword">
									    </div>
									  </div>

									  <div class="form-group">
									    <label for="group" class="col-sm-2 control-label">Group</label>
									    <div class="col-sm-10">
									      <input type="text" name="group" class="form-control input-sm" id="group" placeholder="Group">
									    </div>
									  </div>

									  <br>
									  <label>Action *</label>

									  <div class="form-group">
									    <label for="url" class="col-sm-2 control-label">URL</label>
									    <div class="col-sm-10">
									      <input type="text" name="url" class="form-control input-sm" id="url" placeholder="URL">
									    </div>
									  </div>

									  <div class="form-group">
									    <label for="togroup" class="col-sm-2 control-label">To Group</label>
									    <div class="col-sm-10">
									      <input type="text" name="togroup" class="form-control input-sm" id="togroup" placeholder="Group">
									    </div>
									  </div>

									  <div class="form-group">
									    <label for="reply" class="col-sm-2 control-label">Auto Reply</label>
									    <div class="col-sm-10">
									      <input type="text" name="reply" class="form-control input-sm" id="reply" placeholder="Text to reply">
									    </div>
									  </div>
									
									  <div class="form-group">
									    <div class="col-sm-offset-2 col-sm-10">
									      <a id="submit-button" onclick="Add()" class="btn btn-default btn-sm">Add</a>
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
				title: "Are you sure?",
				text: "You will not be able to recover this data!",   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",   
				confirmButtonText: "Yes, delete it!",   
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
			swal({title: "Select data you want to delete!",text: "It will close in 2 seconds.",timer: 2000,type: "info" });
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
			    	$("#submit-button").html('Save');
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
		$('#title').html('Add new keyword');
	    $('input[name="name"]').val('');
		$('input[name="keyword"]').val('');
		$('input[name="url"]').val('');
		$("#submit-button").html('Add');
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
		if(nama){
			$.post("{{url('keyword')}}"+edit,
			{
				name:nama,
				keyword:keyword,
				url:url,
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