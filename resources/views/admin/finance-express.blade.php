<style>
	.input {
		width: 80%; 
		display: inline-block;
		margin-right: 5px;
	}
</style>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">快递费用修改</h3>
    </div>
    <div class="box-body">
    	<form action="/admin/finances/express/store" method="post">
	    	<table class="table">
	    		<tr>
	    			<th>省份ID</th>
	    			<th>省份名称</th>
	    			<th>首费用</th>
	    			<th>首重</th>
	    			<th>续费</th>
	    			<th>续重</th>
	    		</tr>
	    		@foreach($list as $k => $v)
	    		<input type="hidden" name="id[]" value="{{$v->id}}">
	    		<tr>
	    			<td>{{$v->id}}</td>
	    			<td>{{$v->region_name}}</td>
	    			<td><input type="text" name="express[]" class="form-control input" value="{{$v->express}}">元</td>
	    			<td><input type="text" name="heavy[]" class="form-control input" value="{{$v->heavy}}">斤</td>
	    			<td><input type="text" name="oexpress[]" class="form-control input" value="{{$v->oexpress}}">元</td>
	    			<td><input type="text" name="oheavy[]" class="form-control input" value="{{$v->oheavy}}">斤</td>
	    		</tr>
	    		@endforeach
	    	</table>
	    	<button type="submit" class="btn btn-primary">修改</button>
          	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	    </form>
    </div>
</div>