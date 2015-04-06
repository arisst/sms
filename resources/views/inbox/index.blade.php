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
<body onload="firstLoad(window.location.hash.substring(1));">
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default">
				<div class="panel-heading">Kotak Masuk 
					<div class="pull-right">
						<a class="btn-sm" title="Buat SMS baru" href="inbox"><span style="color:green" class="glyphicon glyphicon-envelope"></span>SMS baru</a>
						<?php $gr = (Session::get('group')=='Off') ? '1' : '0' ; ?>
							<a href="{{url('inbox/'.$gr.'/edit')}}" class="btn btn-default btn-sm <?php if(!$gr) echo 'active'; ?>" >Grouping : {{Session::get('group')}}</a>
					</div>
				</div>

				<div class="panel-body">
				<div class="row">
					<div class="col-md-4">
					<input type="search" id="search" class="form-control input-sm" placeholder="Pencarian: masukkan nama atau nomor">
					<div style="height:500px;overflow-x:hidden;overflow-y:auto">
						<div class="list-group" id="listinbox">
							{{-- LIST OF CONTENT --}}
						</div>
						{{-- <div id="pagination" align="center">
						  <ul class="pagination pagination-sm">
						    <li><a id="prev" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
						    <li><a id="next" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
						  </ul>
						</div> --}}
					</div>
					<a class="btn btn-danger" href="#" onClick="Hapus()">Hapus yang dipilih?</a>
					</div>

					<div class="col-md-8">
						<div class="panel panel-default">
							<div id="title" class="panel-heading"></div>
							<div name="detail" id="description" class="panel-body" style="height:458px;overflow-x:hidden;overflow-y:auto">
								<div id="detail"></div>

								<form id="form0" class="form-horizontal">
									  
									  <div class="form-group">
									    <label for="destination" class="col-sm-2 control-label">Tujuan</label>
									    <div class="col-sm-10" id="prefetch">
									      <input type="text" name="destination" class="form-control input-sm" id="destination" placeholder="Isi nomor, nama kontak atau nama group">
									      {{-- <select name="destination[]" class="chosen-select" tabindex="4" multiple=""></select> --}}
									    </div>
									  </div>
									  <div class="form-group">
									    <label for="text" class="col-sm-2 control-label">Isi SMS</label>
									    <div class="col-sm-10">
									      <textarea id="msg0" class="form-control input-sm" id="text" placeholder="Isi SMS" required>{{Input::get('text')}}</textarea>
									    </div>
									  </div>

									  <div id="schedule-button" class="form-group">
									    <label for="text" class="col-sm-2 control-label"></label>
									  	<div class="col-sm-10">
										  	<a class="btn btn-warning btn-xs" onclick="showScheduleForm()">Jadwalkan pengiriman</a>
									  	</div>
									  </div>
									  <input type="hidden" id="scheduled" value="0">

									  <script type="text/javascript">
									  	function showScheduleForm(){
									  		$('#schedule-button').hide();
									  		$('#schedule-form').show();
									  		$('#scheduled').val('1');
									  	}
									  	function hideScheduleForm(){
									  		$('#schedule-button').show();
									  		$('#schedule-form').hide();
									  		$('#scheduled').val('0');
									  	}
									  </script>
									  
									  <div id="schedule-form" class="form-group" style="display:none;">
									    <label for="send_date" class="col-sm-2 control-label">Waktu Kirim</label>
									    <div class="col-sm-3">
									      <input type="text" id="send_date" class="form-control input-sm" id="send_date" data-date-format="yyyy-mm-dd" placeholder="Tanggal Kirim"> 
									      <p class="help-block">tanggal</p> 
									    </div>

									  <script type="text/javascript">
									  	$(function () {
									  		$('#send_date').datepicker({
									  			dateFormat: "yy-mm-dd"
									  		});
									  	});
									  </script>

									    <div class="col-sm-2">
									      <select id="hour" class="form-control input-sm">
									      	@for ($i=0; $i < 24; $i++) 
									      		<option>{{sprintf("%02d",$i)}}</option>
									      	@endfor
									      </select>
									      <p class="help-bloc">jam</p>
									    </div>
									    <div class="col-sm-2">
									      <select id="minute" class="form-control input-sm">
									      	@for ($i=0; $i < 60; $i++) 
									      		<option>{{sprintf("%02d",$i)}}</option>
									      	@endfor
									      </select>
									      <p class="help-block">menit</p>
									    </div>
									    <div class="col-sm-2">
										  	<a class="btn btn-warning btn-xs" onclick="hideScheduleForm()">Batalkan</a>
									  	</div>
									  </div>									 

									  <div class="form-group">
									    <div class="col-sm-offset-2 col-sm-10">
									      <a id="submit-button" onclick="Send()" class="btn btn-default btn-sm">Kirim</a>
									    </div>
									  </div>
									</form>

							</div>
							<div id="form" class="input-group">
					          <textarea id="msg" name="msg" class="form-control" style="resize:none" rows="2" required></textarea>
					          <a class="input-group-addon btn btn-primary" onclick="Send(window.location.hash.substring(1));"><span class="glyphicon glyphicon-send" ></span> Kirim</a>
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
			// alert(last_page);
		if(current_page<last_page){
			getData('', current_page+1);
		}
	});
	$("#prev").click(function(){
		if(current_page>1){
			getData('', current_page-1);
		}
	});

//AUTOCOMPLETE destination number
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

	/* GET DATA FROM SERVER */
	function getData (term,page) {
		term = typeof term !== 'undefined' ? term : '';
		page = typeof page !== 'undefined' ? page : 1;
		$(document).bind("ajaxStart.mine", function() {
			$("#listinbox").html('<img src="{{asset("img/loadsmall.gif")}}">');
		});
		$(document).bind("ajaxStop.mine", function() {
			// alert('loaded');
		});
		$.get("{{url('inbox')}}?page="+page+"&term="+term, function(data,status){
			var res=nama= '';
			current_page = data['current_page'];
			last_page = data['last_page'];
			$.each(data, function(i, item) {
				if(item.Name!==null){nama = item.Name;}else{nama=item.hp;}
			    res += '<div id="l-'+item.hp+'" onclick="Detail(\''+item.hp+'\');" class="list-group-item" style="cursor:pointer;"><p class="list-group-item-heading"><input name="cid[]" value="'+item.hp+'" type="checkbox" class="cg"> <b>'+nama+'</b><a class="pull-right" title="Add to contact" href={{url("contact")}}#!/add/'+item.hp+'><span style="color:green" class="glyphicon glyphicon-floppy-disk"></span></a></p><p class="list-group-item-text">'+item.isi.substring(0,30)+'</p></div>';
			})
			$("#listinbox").html(res);
		});
		$(document).unbind(".mine");
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
				title: "Anda yakin?",
				text: "Data yang sudah dihapus tidak dapat dikembalikan!",   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",   
				confirmButtonText: "Hapus!",   
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
					    	swal("Terhapus!", data+" data telah dihapus.", "success");
					    	firstLoad();
						}
					});
				});
		}else{
			swal({title: "Pilih data yang akan dihapus!",text: "Akan tertutup setelah 2 detik.",timer: 2000,type: "info" });
		}
	}

	function deleteId(tabel,value) {
			swal({
				title: "Anda yakin?",
				text: "Data yang sudah dihapus tidak dapat dikembalikan!",   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",   
				confirmButtonText: "Delete!",   
				closeOnConfirm: true }, 

				function(){   
					$.post(tabel+"/"+value,
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
				// phone = '0'+phone;
				$("[id^='l-']").removeClass('active');
				$("#l-"+phone).addClass('active');
			    $("#form0").hide();
			    $(document).ajaxStart(function(){
			        $("#title").html('<img src="{{asset("img/loadsmall.gif")}}">');
			    });
			    $(document).ajaxError(function(event, jqxhr, settings, exception) {
				    if (jqxhr.status==401) {
				        location.reload(false);
				    }
				});
				$.get("{{url('inbox')}}/"+phone, function(data,status){
					location.hash = phone;
				    var response = '';
				    var style = '';
				    var tag = '';
				    var btn0 = '';
				    var btn = '';
					for (var i = 0; i < data.length; i++) {
						btn0 = ' <a title="Forward this message" href="?text='+encodeURIComponent(data[i]['isi'])+'"><span class="glyphicon glyphicon-arrow-right"></span></a>';
						if(data[i]['tabel']=='inbox'){
							style = "alert-success pull-left";
							btn = btn0;
						}else if(data[i]['tabel']=='sent'){
							style = "alert-info pull-right";
							btn = btn0;
						}else{
							style = "alert-warning pull-right";
							btn = btn0+' <a title="Delete" onclick="deleteId(\'outbox\', '+data[i]['id']+')" href="#'+phone+'"><span style="color:red" class="glyphicon glyphicon-trash"></span></a>';
						}
						if (data[i]['udh']!='') {tag='div';}else{tag='div';}
						response +='<'+tag+' class="col-md-8 alert '+style+'">'+data[i]['isi']+'<br><small><a href="#!/author/'+data[i]['author']+'">'+data[i]['author_name']+'</a> : <a href="#!/detail/'+data[i]['id']+'">'+data[i]['waktu']+'</a></small><div class="pull-right">'+btn+'</div></'+tag+'>';
					};
			    	$('#detail').html(response);
			    	var nama='';
			    	if (data[0]['Name']) { nama=data[0]['Name']+" - " };
			    	document.getElementById("title").innerHTML=nama+data[0]['hp'];
			    	var myDiv = document.getElementById("description");
					myDiv.scrollTop = myDiv.scrollHeight;
			    	// document.getElementById("msg").focus();
				    $("#form").show();

				});
			}else{
			    document.getElementById("title").innerHTML='Buat SMS baru';
			    $("#form0").show();
			    $("#form").hide();
			}
		}

	function formCompose(){
		$("input#destination").val('');
    	$("textarea#msg0").val('');
    	hideScheduleForm();
	}

	function Send (phone) 
	{
		var dest = state = msg = schedule = '';
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
			if($('#scheduled').val()=='1'){
				schedule = $("input#send_date").val()+' '+$('#hour').val()+':'+$('#minute').val()+':00';
			}
		}
		swal({
				title: "Anda yakin?",
				text: "Akan mengirim SMS ke '"+dest+"'?",   
				type: "info",   
				showCancelButton: true,   
				// confirmButtonColor: "#DD6B55",   
				confirmButtonText: "Kirim",   
				closeOnConfirm: true }, 

				function(){ 
					$.post("{{url('inbox')}}",
					{
						destination:dest,
						state:state,
						message:msg,
						schedule:schedule,
						_token:"{{csrf_token()}}"
					},
					function(data,status){
						if(status=='success'){
							if(state){
								$("textarea#msg").val('');
						    	Detail(window.location.hash.substring(1));
						    }else{
						    	firstLoad();
						    	formCompose();
						    }
						}
					});
				});
	}
</script>

@endsection