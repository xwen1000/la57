<div class="row">
	<div class="col-md-4">
		<select class="form-control" name="good_id">
			@foreach($ncp as $k => $v)
			<option value="{{ $k }}">{{ $v }}</option>
			@endforeach
		</select>
	</div>
	<div class="col-md-4">
		<input name="good_rate" type="text" class="form-control">
	</div>
	<div class="col-md-4">
		<button type="button" class="btn btn-default" id="crbtn">插入</button>	
	</div>
</div>
<table class="table table-bordered" style="margin-top:15px;" id="t1">
	<tr>
		<th>ID</th>
		<th>物品名称</th>
		<th>数量</th>
		<th>操作</th>
	</tr>
	@foreach($cardGoods as $k => $v)
	<tr>
		<td>{{$v->gid}}</td>
		<td>{{$v->gname}}</td>
		<td>{{$v->rate}}</td>
		<td><button type="button" class="btn btn-default remove" id="{{$v->id}}">删除</button></td>
		<input type="hidden" name="gname[]" value="{{$v->gname}}">
		<input type="hidden" name="gid[]" value="{{$v->gid}}">
		<input type="hidden" name="grate[]" value="{{$v->rate}}">
	</tr>
	@endforeach
</table>

<script>
	$('#crbtn').click(function(){
		var goodId = $('select[name=good_id]').val();
		var goodName = $('option[value='+goodId+']').text();
		var goodRate = $('input[name=good_rate]').val();
		console.log(goodId, goodName, goodRate);
		var cHtml = '<tr>';
		cHtml += '<td>'+goodId+'</td><td>'+goodName+'</td><td>'+goodRate+'</td>';
		cHtml += '<td><button type="button" class="btn btn-default remove" id="'+goodId+'">删除</button></td>';
		cHtml += '<input type="hidden" name="gname[]" value="'+goodName+'">';
		cHtml += '<input type="hidden" name="gid[]" value="'+goodId+'">';
		cHtml += '<input type="hidden" name="grate[]" value="'+goodRate+'">';
        cHtml += '</tr>'                                    

		$('#t1').append(cHtml);
	});
	$(document).on("click", ".remove", function() {
		$(this).parents('tr').remove();
	});
</script>

