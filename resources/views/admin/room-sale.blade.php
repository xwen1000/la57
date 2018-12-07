<style>
	.sale-box {
		border: 1px solid #ccc;
	}
	.col-md-2 {
		margin-bottom: 15px; 
	}
	.col-md-2 span {
		color: #F00;
		padding: 3px;
	}
	.sale-box h4 {
		font-size: 16px;
		border-bottom: 1px solid #ccc;
		padding: 10px;
		margin: 0; 
	}
	.sale-box p {
		margin: 0;
		padding: 5px 10px;
	}
	.sale-row {
		font-size: 18px;
	}
</style>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">客房统计</h3>
    </div>
    <div class="box-body">
    	<div class="row sale-row">
    		<div class="col-md-5" style="text-align: right;"><a href="/admin/room/sale/{{$id}}?months={{$lastM}}">上个月</a></div>
    		<div class="col-md-2" style="text-align: center;">{{$now}}</div>
    		<div class="col-md-5" style="text-align: left;"><a href="/admin/room/sale/{{$id}}?months={{$nextM}}">下个月</a></div>
    	</div>
    	@foreach($resArr as $k => $v)
    	<div class="col-md-2">
    		<div class="sale-box">
    			<h4>{{$v['time']}}</h4>
    			<p>剩余：<span>{{$v['quantity']}}</span>间</p>
    			<p>已售：<span>{{$v['now_nums']}}</span>间</p>
    		</div>
    	</div>
    	@endforeach
    </div>
</div>