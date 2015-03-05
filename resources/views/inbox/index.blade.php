@extends('app')
@section('content')
<style>
  .ui-autocomplete {
    max-height: 200px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  }
  /* IE 6 doesn't support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
  * html .ui-autocomplete {
    height: 200px;
  }
  </style>
<body onload="Detail(window.location.hash.substring(1));">
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default">
				<div class="panel-heading">Inbox <?php $gr = (Session::get('group')=='Off') ? '1' : '0' ; ?>
					<a href="{{url('inbox/'.$gr.'/edit')}}" class="pull-right btn btn-default btn-sm <?php if(!$gr) echo 'active'; ?>" >Grouping : {{Session::get('group')}}</a>
				</div>

				<div class="panel-body">
					<?php $i = 1; ?>
				<div class="row">
					<div class="col-md-4">
					<div style="height:500px;overflow-x:hidden;overflow-y:auto">
						<div class="list-group">
						@foreach($data as $value)
						  <a id="l-{{$value->hp}}" href="#" onclick="Detail('{{$value->hp}}');" class="list-group-item">
						    <p class="list-group-item-heading"><input name="cid[]" value="{{$value->hp}}" type="checkbox" class="cg"> @if($value->Name)<b>{{$value->Name}}</b>@else{{$value->hp}}@endif</p>
						    <p class="list-group-item-text">{{str_limit($value->isi, 60)}}</p>
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
								<div id="detail"></div>

								<form id="form0" class="form-horizontal">
									  
									  <div class="form-group">
									    <label for="destination" class="col-sm-2 control-label">Destination</label>
									    <div class="col-sm-10" id="prefetch">
									      <input type="text" name="destination" class="form-control input-sm" id="destination" placeholder="Destination" required>
									      {{-- <select name="destination[]" class="chosen-select" tabindex="4" multiple=""></select> --}}
									    </div>
									  </div>
									  <div class="form-group">
									    <label for="text" class="col-sm-2 control-label">Text</label>
									    <div class="col-sm-10">
									      <textarea id="msg0" class="form-control" id="text" placeholder="Text" required></textarea>
									    </div>
									  </div>
									  <div class="form-group">
									    <div class="col-sm-offset-2 col-sm-10">
									      <a id="submit-button" onclick="Send()" class="btn btn-default btn-sm">Send</a>
									    </div>
									  </div>
									</form>

							</div>
							<div id="form" class="input-group">
					          <textarea id="msg" name="msg" class="form-control" style="resize:none" rows="2" required></textarea>
					          <a class="input-group-addon btn btn-primary" onclick="Send(window.location.hash.substring(1));"><span class="glyphicon glyphicon-send" ></span> Send</a>
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

//AUTOCOMPLETE
	$(function() {
		function split( val ) {
	      return val.split( /,\s*/ );
	    }
	    function extractLast( term ) {
	      return split( term ).pop();
	    }
		$( "#destination" )
			.bind( "keydown", function( event ) {
	        if ( event.keyCode === $.ui.keyCode.TAB &&
	            $( this ).autocomplete( "instance" ).menu.active ) {
	          event.preventDefault();
	        }
	      	})

			.autocomplete(
			{
				minLength: 0,
				// source: "{{url('group')}}/0",
				source: function( request, response ) {
		          $.getJSON( "{{url('contact')}}/0", {
		            term: extractLast( request.term )
		          }, response );
		        },
				focus: function() {
		          return false;
		        },
				select: function( event, ui ) {
					var terms = split( this.value );
			          terms.pop();
			          terms.push( ui.item.label );
			          // console.log(ui.item.value);
			          terms.push( "" );
			          this.value = terms.join( ", " );
					$("#destination").val(this.value);
					return false;
				}
			})
			.autocomplete( "instance" )._renderItem = function( ul, item ) {
		      return $( "<li>" )
		        .append( "<li class=\"list-group-item\" role=\"presentation\"><a role=\"menuitem\" tabindex=\"-1\" href=\"#\">" + item.label + " (" + item.num + ")</a></li>" )
		        .appendTo( ul );
		    };
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
				confirmButtonText: "Delete!",   
				closeOnConfirm: false }, 

				function(){   
					$.post("{{url('inbox')}}/"+vals,
					{
						id:vals,
						_method:"DELETE",
						_token:"{{csrf_token()}}"
					},
					function(data,status){
						if(status=='success'){
					    	swal("Deleted!", data+" data has been deleted.", "success");
					    	Detail('');
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
				confirmButtonText: "Delete!",   
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

	function Detail(phone)
		{
			if(phone){
			    $("#form0").hide();
			    $(document).ajaxStart(function(){
			        document.getElementById("title").innerHTML='Loading...';
			    });
			    $(document).ajaxError(function(event, jqxhr, settings, exception) {
				    if (jqxhr.status==401) {
				        location.reload();
				    }
				});
				$.get("{{url('inbox')}}/"+phone, function(data,status){
					location.hash = phone;
				    var response = '';
				    var style = '';
				    var tag = '';
				    var btn = '';
					for (var i = 0; i < data.length; i++) {
						if(data[i]['tabel']=='inbox'){
							style = "alert-success pull-left";
						}else if(data[i]['tabel']=='sent'){
							style = "alert-info pull-right";
						}else{
							style = "alert-warning pull-right";
							btn = ' <a class="pull-right" onclick="deleteOutbox('+data[i]['id']+')" href="#'+phone+'"><span class="glyphicon glyphicon-trash"></span></a>';
						}
						if (data[i]['udh']!='') {tag='span';}else{tag='p';}
						response +='<'+tag+' class="col-md-8 alert '+style+'">'+data[i]['isi']+'<br><small>'+data[i]['waktu']+'</small>'+btn+'</'+tag+'>';
					};
			    	document.getElementById("detail").innerHTML = response;
			    	// document.getElementById('l-'+phone).className = 'list-group-item active';
			    	var nama='';
			    	if (data[0]['Name']) { nama=data[0]['Name']+" - " };
			    	document.getElementById("title").innerHTML=nama+data[0]['hp'];
			    	var myDiv = document.getElementById("description");
					myDiv.scrollTop = myDiv.scrollHeight;
			    	// document.getElementById("msg").focus();
				    $("#form").show();

				});
			}else{
			    document.getElementById("title").innerHTML='Compose message';
			    $("#form0").show();
			    $("#form").hide();
			}
		}

	function Send (phone) 
	{
		var dest = '';
		var state = '';
		var msg = '';
		if(phone){ //conversation
			state = 1;
			dest = phone;
			msg = $("textarea#msg").val();
		}
		else //compose
		{
			state = 0;
			dest = $("input#destination").val();
			msg = $("textarea#msg0").val();
		}
		swal({
				title: "Are you sure?",
				text: "You will send this message to '"+dest+"'?",   
				type: "info",   
				showCancelButton: true,   
				// confirmButtonColor: "#DD6B55",   
				confirmButtonText: "Yes, send it!",   
				closeOnConfirm: true }, 

				function(){ 
					$.post("{{url('inbox')}}",
					{
						destination:dest,
						state:state,
						message:msg,
						_token:"{{csrf_token()}}"
					},
					function(data,status){
						if(status=='success'){
							$("textarea#msg").val('');
					    	Detail(window.location.hash.substring(1));
						}
					});
				});
	}
</script>

@endsection