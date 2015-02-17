@extends('app')
@section('content')
<body onload="ajaxDetail(window.location.hash.substring(1));">
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default">
				<div class="panel-heading">Inbox <?php $gr = (Session::get('group')=='Off') ? '1' : '0' ; ?>
					<a href="{{url('inbox/'.$gr.'/edit')}}" class="pull-right btn btn-default btn-sm <?php if(!$gr) echo 'active'; ?>" >Grouping : {{Session::get('group')}}</a>
				</div>

				<div class="panel-body">

					<?php 
					$murl = (Input::has('q')||Input::has('filter')) ? Request::getRequestUri().'&' : '?' ;
					 ?>
					<?php $i = 1; //($data->currentPage() - 1) * $data->perPage() + 1; ?>
				<div class="row">
					<div class="col-md-4" style="height:500px;overflow:scroll">
						<div class="list-group">
						@foreach($data as $value)
						  <a id="list{{$i}}" href="#" onclick="ajaxDetail('{{$value->SenderNumber}}');" class="list-group-item">
						    <p class="list-group-item-heading"><input name="cid[]" value="{{$value->ID}}" type="checkbox" class="cg"> {{$value->SenderNumber}}</p>
						    <p class="list-group-item-text">{{str_limit($value->TextDecoded, 60)}}</p>
						  </a>
						<?php $i++; ?>
						@endforeach
						</div>
					</div>

					<div class="col-md-8">
						<div class="panel panel-default">
							<div id="title" class="panel-heading"></div>
							<div id="description" class="panel-body">
								<div id="detail"></div>
							</div>
							

						</div>
					</div>
				</div>

				<div class="row">
					<a href="#" onClick="Delete()">With selected: Delete?</a>
				</div>


				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function checkAll(source) {
		checkboxes = document.getElementsByName('cid[]');
		for(var i=0, n=checkboxes.length;i<n;i++) {
		   checkboxes[i].checked = source.checked;
		}
		// document.getElementById("del").innerHTML = '<a href="#" onClick="Delete()">Delete data ini?</a>';
	}

	function Delete () {
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
					ajaxLoad("{{url('inbox')}}/"+vals,"POST","id="+vals+"&_method=DELETE&_token={{csrf_token()}}");
				});
		}else{
			swal({title: "Select data you want to delete!",text: "It will close in 2 seconds.",timer: 2000,type: "info" });
		}
	}

	function ajaxLoad(url,type,params)
			{
				var xmlhttp;
				if (window.XMLHttpRequest)
				  {// code for IE7+, Firefox, Chrome, Opera, Safari
				  	xmlhttp=new XMLHttpRequest();
				  }
				else
				  {// code for IE6, IE5
				  	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				  }
				xmlhttp.onreadystatechange=function()
				  {
					  if (xmlhttp.readyState==4 && xmlhttp.status==200)
					    {
					    	swal("Deleted!", "Your imaginary file has been deleted.", "success");
					    	// location.reload();
					    	document.getElementById("status").innerHTML=xmlhttp.responseText+' data deleted!';
					    }
					  else if(xmlhttp.readyState<4)
					  {
					    document.getElementById("status").innerHTML='Loading ... ';
					  }
					   else
					   {
					    document.getElementById("status").innerHTML='Terjadi kesalahan : '+xmlhttp.responseText+'<br><input type="button" value="Reload" onclick="window.location.reload();return false;"> ';
					   }
				  }

				xmlhttp.open(type,url,true);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				// xmlhttp.setRequestHeader("Content-length", params.length);
				// xmlhttp.setRequestHeader("Connection", "close");
				xmlhttp.setRequestHeader("X-Requested-With","XMLHttpRequest");
				xmlhttp.send(params);
			}

	function ajaxDetail(phone)
			{
				var xmlhttp;
				if (window.XMLHttpRequest)
				  {// code for IE7+, Firefox, Chrome, Opera, Safari
				  	xmlhttp=new XMLHttpRequest();
				  }
				else
				  {// code for IE6, IE5
				  	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				  }
				xmlhttp.onreadystatechange=function()
				  {
					  if (xmlhttp.readyState==4 && xmlhttp.status==200)
					    {
					    	location.hash = phone;
						    var v = JSON.parse(xmlhttp.responseText);
						    var response = '';
						    var style = '';
							for (var i = 0; i < v.inbox.length; i++) {
								if(i%2==0){
									style = "alert-success pull-left";
								}else{
									style = "alert-info pull-right";
								}
								response +='<span class="col-md-8 alert '+style+'">'+v.inbox[i]['TextDecoded']+'</span>';
								
							};
					    	document.getElementById("detail").innerHTML = response;
					    	// document.getElementById("detail").className += ' active';
					    	document.getElementById("title").innerHTML=v['inbox'][0]['SenderNumber'];
					    	// document.getElementById("description").innerHTML=v['inbox'][0]['TextDecoded'];
					    }
					  else if(xmlhttp.readyState<4)
					  {
					    document.getElementById("title").innerHTML='Loading ... ';
					  }
					   else
					   {
					    document.getElementById("description").innerHTML='Terjadi kesalahan : '+xmlhttp.responseText+'<br><input type="button" value="Reload" onclick="window.location.reload();return false;"> ';
					   }
				  }

				xmlhttp.open('GET',"{{url('inbox')}}/"+phone,true);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlhttp.setRequestHeader("X-Requested-With","XMLHttpRequest");
				xmlhttp.send();
			}
</script>

@endsection