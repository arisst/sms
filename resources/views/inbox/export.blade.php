<table border="1">
	<tr>
		<td>Nama</td>
		<td>HP</td>
		<td>Percakapan</td>
		<td>Waktu</td>
		<td>Status</td>
	</tr>
	@foreach($data as $key)
	<tr>
		<td>@if($key->tabel=='sent' || $key->tabel=='outbox') {{ 'SMS{'.$key->author_name.'}' }} @else{{$key->Name}}@endif</td>
		<td>@if($key->tabel=='sent' || $key->tabel=='outbox') {{ 'SMS{'.$key->author_name.'}' }} @else{{$key->hp}}@endif</td>
		<td>{{$key->isi}}</td>
		<td>{{$key->waktu}}</td>
		<td>{{$key->status}}</td>
	</tr>
	@endforeach
</table>