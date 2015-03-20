@extends('app')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default">
				<div class="panel-heading">Inbox <?php $gr = (Session::get('group')=='Off') ? '1' : '0' ; ?>
					<a href="{{url('inbox/'.$gr.'/edit')}}" class="pull-right btn btn-default btn-sm <?php if(!$gr) echo 'active'; ?>" >Grouping : {{Session::get('group')}}</a>
				</div>

				<div class="panel-body">

					<!-- SEARCH -->
					<div id="search_block" class="panel-body">
					    <form role="form" class="form-horizontal" method="get" action="">
						<!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
					        <div class="form-group">
					            <label class="col-xs-10 col-sm-2 col-md-1 control-label" for="q">Search:</label>
					            <div class="col-xs-10 col-sm-8 col-md-4">
					                <input type="search" placeholder="Enter Search Keywords" value="{{Input::get('q')}}" name="q" id="q" class="form-control input-sm" autofocus>
					            </div>

					             <label class="col-xs-10 col-sm-2 col-md-1 control-label" for="filters">Filters:</label>
					            <div class="col-xs-10 col-sm-8 col-md-2" id="filters">
					                <select class="form-control input-sm" id="filters" name="filter">
					                    <option value="all">All</option>
					                    <option <?php if(Input::get('filter')=='phone') echo 'selected' ?> value="phone">Phone</option>
					                    <option <?php if(Input::get('filter')=='text') echo 'selected' ?> value="text">Text</option>
					                </select>
					            </div>

					            <div class="button-group col-xs-10 col-xs-offset-0 col-sm-8 col-sm-offset-2 col-md-3 col-md-offset-0">
					                <button class="btn btn-primary btn-sm" value="search" type="submit">Search</button>
					                <a href="{{url('inbox')}}" class="btn btn-default btn-sm" value="reset" name="reset" type="reset">Reset</a>
					            </div>
					        </div>
					    </form>
					</div>
					<!-- END SEARCH -->
					<?php 
					$murl = (Input::has('q')||Input::has('filter')) ? Request::getRequestUri().'&' : '?' ;
					 ?>

					<table class="table">
					<tr>
						<th><input type="checkbox" onClick="checkAll(this)"></th>
						<th>#</th>
						<th>
							<a <?php if(Input::get('sort')=='phone'){echo'href="?sort" style="color:#33B7A3;"';}else{echo'href="'.$murl.'sort=phone"';} ?>>Phone</a>
						</th>
						<th>
							<a <?php if(Input::get('sort')=='text'){echo'href="?sort" style="color:#33B7A3;"';}else{echo'href="'.$murl.'sort=text"';} ?>>Text</a>
						</th>
						<th>
							<a <?php if(Input::get('sort')=='time'){echo'href="?sort" style="color:#33B7A3;"';}else{echo'href="'.$murl.'sort=time"';} ?>>Time</a>
						</th>
						@foreach($matches as $value)
							<th>
								<a href="">{{$value}}</a>
							</th>
						@endforeach
						
					</tr>
					<?php $i = ($data->currentPage() - 1) * $data->perPage() + 1; ?>
						@foreach($data as $value)
						<tr>
							<td><input name="cid[]" value="{{$value->id}}" type="checkbox" class="cg"></td>
							<td>{{$i}}</td>
							<td><a href="@if(!$gr){{url('inbox/group').'?p='.urlencode($value->hp)}}@else{{url('inbox/'.$value->id)}}@endif">{{$value->hp}}</a></td>
							<td>{{str_limit($value->isi, 20)}}</td>
							<td>{{$value->waktu}}</td>
						</tr>
						<?php $i++; ?>
						@endforeach
					</table>
					<a href="#" onClick="Delete()">With selected: Delete?</a>
					<center>{!! $data->appends(['sort'=>Input::get('sort'), 'q'=>Input::get('q'), 'filter'=>Input::get('filter')])->render() !!}</center>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function checkAll(source) {
		// location.hash = 'foo';
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
</script>

@endsection	