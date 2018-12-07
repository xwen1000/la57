<style>
	.order-table {
		text-align: center;
	}
	.order-table span {
		margin-right: 10px;
		font-weight: bold;
	}
	.order-table caption {
		font-weight: bold;
		font-size: 16px;
		color: #000;
	}
	.order-table th {
		font-weight: normal;
		text-align: center;
	}
</style>

<div class="box box-default">
    <div class="box-body">
    	<table class="table table-bordered order-table">
			<caption>订单详情</caption>
			<tr>
				<td><span>仓库打印机:</span><a href="/admin/orders/printInfo/{{$orderInfo->id}}" class="btn btn-primary">打印</a></td>
				<td>
					@if($orderInfo->logistics)
					<span>顺丰运单号:</span>
					<span>{{$orderInfo->logistics}}</span>
					<button type="button" class="btn btn-primary">打印</button>
					@endif
				</td>
			</tr>
		</table>
		<table class="table table-bordered order-table">
			<caption>基本信息</caption>
			<tr>
				<td><span>订单号:</span>{{ $orderInfo->order_sn }}</td>
				<td><span>订单状态:</span>
					@switch($orderInfo->order_status)
						@case(0)
							待支付
							@break
						@case(1)
							已支付，未确认
							@break
						@case(2)
							已确认，待发货
							@break
						@case(3)
							配送中
							@break
						@case(4)
							已签收
							@break
						@case(5)
							交易已取消
							@break
						@case(6)
							未发货退款处理中
							@break
						@case(7)
							未发货退款成功
							@break
						@case(8)
							已发货退款处理中
							@break
						@case(9)
							已发货退款成功
							@break
					@endswitch
				</td>
			</tr>
			<tr>
				<td><span>购买人:</span>
					{{ $memberInfo->nickname }}
					<a href="/admin/orders/member/{{ $memberInfo->id }}">[显示购买人信息]</a>
				</td>
				<td><span>下单时间:</span>{{ $orderInfo->order_time }}</td>
			</tr>
			<tr>
				<td><span>支付方式:</span>{{ $payInfo->pay_type }}</td>
				<td><span>付款时间:</span>{{ $payInfo->pay_done_time }}</td>
			</tr>
			<tr>
				<td><span>配送方式:</span>
					{{ $orderInfo->shipping_name }}
					{{ $orderInfo->tracking_number }}
				</td>
				<td><span>发货时间:</span>
					{{ $orderInfo->shipping_time }}
				</td>
			</tr>
			<tr>
				<td><span>收货人:</span>{{ $orderInfo->receive_name }}</td>
				<td><span>电话:</span>{{ $orderInfo->mobile }}</td>
			</tr>
			<tr>
			 	<td colspan="2"><span>收货地址:</span>{{ $orderInfo->shipping_address }}</td>
			</tr>
			@if($orderInfo->tables)
			<tr>
				<td colspan="2">第<span>{{ $orderInfo->tables }}</span>桌</td>
			</tr>
			@endIf
		</table>
		<table class="table table-bordered order-table">
			<caption>订单详情</caption>
			<tr>
				<th>商品图片</th>
				<th>商品名称</th>
				<th>商品数量</th>
				<th>商品价格</th>
			</tr>
			@foreach($orderInfo->OrderGoods as $k => $v)
			<tr>
				<td><img src="{{ $v->image_url }}" width="100" height="100"></td>
				<td>{{ $v->goods_name }}</td>
				<td>{{ $v->quantity }}</td>
				<td>{{ $v->goods_price }}元</td>
			</tr>
			@endforeach
		</table>
		<table class="table order-table">
			<caption>操作信息</caption>
			<tr>
				<td style="text-align: right;">
					操作备注:
				</td>
				<td colspan="2">
					<textarea class="form-control" rows="10"></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="4">当前可执行操作：
					@if($orderInfo->order_type == 0)
						@switch($orderInfo->order_status)
							@case(0)
								<a href="#">取消订单</a>
								@break
							@case(1)
								<a href="#">确认订单</a>
								<a href="#">发货</a>
								@break
							@case(2)
								<a href="#">发货</a>
								@break
						@endswitch
					@endif
					@if($orderInfo->order_type == 1)
						@switch($orderInfo->order_status)
							@case(0)
								<a href="#">取消订单</a>
								@break
							@case(1)
								<a href="#">确认订单</a>
								<a href="#">发货</a>
								@break
							@case(2)
								<a href="#">发货</a>
								@break
						@endswitch
					@endif
					@if($orderInfo->order_type == 2)
						@switch($orderInfo->order_status)
							@case(0)
								<a href="#">取消订单</a>
								@break
						@endswitch
					@endif
				</td>
			</tr>
			@foreach($logInfo as $k => $v)
			<tr>
				<td>{{ $v->manage }}</td>
				<td>{{ $v->time }}</td>
				<td>{{ $v->do }}</td>
				<td>{{ $v->remark }}</td>
			</tr>
			@endforeach
		</table>
    </div>
</div>
<script>
	// $('#print').click(function(){
	// 	window.location = '/admin/orders/printInfo/'+{{$orderInfo->id}};
	// 	// window.print();
	// });
</script>