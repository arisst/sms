@extends('app')
@section('content')

<body onload="Detail(window.location.hash.substring(1));">
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default">
				<div class="panel-heading">
					Contact
				</div>

				<div class="panel-body">
					<?php $i = 1; ?>
				<div class="row">
					<div class="col-md-4">
					<div style="height:500px;overflow-x:hidden;overflow-y:auto">
						<div class="list-group">
						@foreach($data as $value)
						  <a id="l-{{$value->ID}}" href="#" onclick="Detail('{{$value->ID}}');" class="list-group-item">
						    <p class="list-group-item-heading"><input name="cid[]" value="{{$value->ID}}" type="checkbox" class="cg"> <b>{{$value->Name}}</b></p>
						    <p class="list-group-item-text">{{$value->Number}}</p>
						  </a>
						<?php $i++; ?>
						@endforeach
						</div>
					</div>
					<a class="btn btn-danger" href="#" onClick="Hapus()">With selected: Delete?</a>
					</div>

					<div class="col-md-8">
						<div class="panel panel-default">
							<div id="title" class="panel-heading"></div>
							<div name="detail" id="description" class="panel-body" style="height:458px;overflow-x:hidden;overflow-y:auto">
								<div id="detail">
									<form class="form-horizontal">
									  <div class="form-group">
									    <label for="name" class="col-sm-2 control-label">Name</label>
									    <div class="col-sm-10">
									      <input type="text" name="name" class="form-control input-sm" id="name" placeholder="Name" required>
									    </div>
									  </div>
									  <div class="form-group">
									    <label for="number" class="col-sm-2 control-label">Number</label>
									    <div class="col-sm-10">
									      <input type="text" name="number" class="form-control input-sm" id="number" placeholder="Number" required>
									    </div>
									  </div>
									  <div class="form-group">
									    <label for="group" class="col-sm-2 control-label">Group</label>
									    <div class="col-sm-10" id="prefetch">
									    <input type="hidden" name="groupid" id="groupid">
									      <input type="text" name="group" class="form-control input-sm" id="group" placeholder="Group" required>
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
	$(function() {
		$( "#group" ).autocomplete(
		{
			 source: "{{url('group')}}/0",
			 select: function( event, ui ) {
				$("#group").val(ui.item.label);
				$("#groupid").val(ui.item.id);
				return false;
			}
		})
	});

	function checkAll(source) {
		checkboxes = document.getElementsByName('cid[]');
		for(var i=0, n=checkboxes.length;i<n;i++) {
		   checkboxes[i].checked = source.checked;
		}
		// document.getElementById("del").innerHTML = '<a href="#" onClick="Delete()">Delete data ini?</a>';
	}

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
				closeOnConfirm: false }, 

				function(){   
					$.post("{{url('contact')}}/"+vals,
					{
						id:vals,
						_method:"DELETE",
						_token:"{{csrf_token()}}"
					},
					function(data,status){
						if(status=='success'){
					    	Detail('');
					    	location.reload();
						}
					});
				});
		}else{
			swal({title: "Select data you want to delete!",text: "It will close in 2 seconds.",timer: 2000,type: "info" });
		}
	}

	function deleteOutbox (value) {
			swal({
				title: "Are you sure?",
				text: "You will not be able to recover this data!",   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",   
				confirmButtonText: "Yes, delete it!",   
				closeOnConfirm: true }, 

				function(){   
					$.post("{{url('outbox')}}/"+value,
					{
						id:value,
						_method:"DELETE",
						_token:"{{csrf_token()}}"
					},
					function(data,status){
						if(status=='success'){
					    	// swal("Deleted!", data+" data has been deleted.", "success");
					    	Detail(window.location.hash.substring(1));
						}
					});
				});		
	}

	function Detail(id)
		{
			if(id){
			    $(document).ajaxStart(function(){
			        document.getElementById("title").innerHTML='Loading...';
			    });
			    $(document).ajaxError(function(event, jqxhr, settings, exception) {
				    if (jqxhr.status==401) {
				        location.reload();
				    }
				});
				$.get("{{url('contact')}}/"+id, function(data,status){
					location.hash = id;
					$('input[name="name"]').val(data[0]['Name']);
					$('input[name="number"]').val(data[0]['Number']);
					$('input[name="group"]').val(data[0]['GroupName']);
			    	document.getElementById("submit-button").innerHTML = 'Save';
			    	$('#submit-button').attr('onclick', 'Add('+id+');');
			    	document.getElementById("title").innerHTML = data[0]['Name'];
				});
			}else{
			    document.getElementById("title").innerHTML='Add new contact';
			}
		}

	function Add(id) 
	{
		var edit = method = '';
		if(id){ 
			edit = id;
			method = 'PUT'; 
		}
		var nama = $("input#name").val();
		var nomor = $("input#number").val();
		var group = $("input#group").val();
		if(nama && nomor){
			$.post("{{url('contact')}}/"+edit,
			{
				name:nama,
				number:nomor,
				group:group,
				_method:method,
				_token:"{{csrf_token()}}"
			},
			function(data,status){
				if(status=='success'){
			    	window.location.href = "{{url('contact')}}#"+data['id'];
			    	location.reload();
				}
			});
		}
	}
</script>

@endsection