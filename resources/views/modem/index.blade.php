@extends('app')
@section('content')

<body onload="firstLoad(window.location.hash.substring(1))">
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default">
				<div class="panel-heading">
					Info Modem (Versi Gammu : {{$data}})
				</div>

				<div class="panel-body">
					
				<div class="row">
					<div class="col-md-4">
					<input type="search" id="search" class="form-control input-sm" placeholder="Pencarian: masukkan IMEI atau Client">
					<div id="listarea" style="height:470px;overflow-x:hidden;overflow-y:auto">
						<div class="list-group" id="listcontact"></div>
						<div id="pagination" align="center">
						  <ul class="pagination pagination-sm">
						    <li><a id="prev" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
						    <li><a id="next" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
						  </ul>
						</div>
					</div>
					</div>

					<div class="col-md-8">
						<div class="panel panel-default">
							<div id="title" class="panel-heading"></div>
							<div name="detail" id="description" class="panel-body" style="height:458px;overflow-x:hidden;overflow-y:auto">
								<div id="detail">
									<form class="form-horizontal">
									  <div class="form-group">
									    <label for="imei" class="col-sm-2 control-label">IMEI</label>
									    <div class="col-sm-10">
									      <input type="text" name="imei" class="form-control input-sm" id="imei" readonly>
									    </div>
									  </div>
									  <div class="form-group">
									    <label for="client" class="col-sm-2 control-label">Client</label>
									    <div class="col-sm-10">
									      <div id="client" class="well"></div>
									    </div>
									  </div>
									  <div class="form-group">
									    <label for="update" class="col-sm-2 control-label">UpdatedInDB</label>
									    <div class="col-sm-10">
									      <input type="text" name="update" class="form-control input-sm" id="update" readonly>
									    </div>
									  </div>
									  <div class="form-group">
									    <label for="insert" class="col-sm-2 control-label">InsertIntoDB</label>
									    <div class="col-sm-10">
									      <input type="text" name="insert" class="form-control input-sm" id="insert" readonly>
									    </div>
									  </div>
									  <div class="form-group">
									    <label for="timeout" class="col-sm-2 control-label">Timeout</label>
									    <div class="col-sm-10">
									      <input type="text" name="timeout" class="form-control input-sm" id="timeout" readonly>
									    </div>
									  </div>
									  <div class="form-group">
									    <label for="send_receive" class="col-sm-2 control-label">Send/Receive</label>
									    <div class="col-sm-10">
									      <input type="text" name="send_receive" class="form-control input-sm" id="send_receive" readonly>
									    </div>
									  </div>
									  <div class="form-group">
									    <label for="signal" class="col-sm-2 control-label">Signal</label>
									    <div class="col-sm-10">
									      <input type="text" name="signal" class="form-control input-sm" id="signal" readonly>
									    </div>
									  </div>
									  <div class="form-group">
									    <label for="sent_received" class="col-sm-2 control-label">Sent/Received</label>
									    <div class="col-sm-10">
									      <input type="text" name="sent_received" class="form-control input-sm" id="sent_received" readonly>
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
		$.get("{{url('modem')}}?page="+page+"&term="+term, function(data,status){
			var res= '';
			current_page = data['current_page'];
			last_page = data['last_page'];
			$.each(data['data'], function(i, item) {
			    res += '<a id="l-'+item.IMEI+'" href="#" onclick="Detail(\''+item.IMEI+'\');" class="list-group-item"><p class="list-group-item-heading"><b>IMEI : '+item.IMEI+'</b></p><p class="list-group-item-text">'+item.Client+'</p></a>';
			})
			$("#listcontact").html(res);
		});
		$(document).unbind(".mine");
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

				$.get("{{url('modem')}}/"+id, function(data,status){
					location.hash = id;
					$('input[name="imei"]').val(data['IMEI']);
					$('#client').html(data['Client']);
					$('input[name="update"]').val(data['UpdatedInDB']);
					$('input[name="insert"]').val(data['InsertIntoDB']);
					$('input[name="timeout"]').val(data['TimeOut']);
					$('input[name="send_receive"]').val(data['Send']+' / '+data['Receive']);
					$('input[name="signal"]').val(data['Signal']);
					$('input[name="sent_received"]').val(data['Sent']+' / '+data['Received']);
				    $('#title').html('IMEI : '+data['IMEI']);
				});
				$(document).unbind(".mine1");
			}else{
			    
			}
		}

</script>

@endsection