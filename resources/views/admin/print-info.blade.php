<div class="box box-default">
    <div class="box-body">
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
				<td><span>发货时间:</span>{{ $orderInfo->shipping_time }}</td>
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
    </div>
</div>
<script>
	window.print();
</script>