<div class="box box-default">
    <div class="box-body">
    	<table class="table table-bordered order-table">
			<caption>会员详情</caption>
			<tr>
				<td>会员ID：</td>
				<td>{{$memberInfo->id}}</td>
			</tr>
			<tr>
				<td>open_id：</td>
				<td>{{$memberInfo->open_id}}</td>
			</tr>
			<tr>
				<td>会员头像：</td>
				<td><img src="{{$memberInfo->headimgurl}}" width="100" height="100" /></td>
			</tr>
			<tr>
				<td>会员昵称：</td>
				<td>{{$memberInfo->nickname}}</td>
			</tr>
			<tr>
				<td>手机号：</td>
				<td>{{$memberInfo->phone}}</td>
			</tr>
			<tr>
				<td>性别：</td>
				<td>{{ $memberInfo->sex == 1 ? '男' : '女' }}</td>
			</tr>
			<tr>
				<td>注册时间：</td>
				<td>{{$memberInfo->time}}</td>
			</tr>
			<tr>
				<td>折扣卡余额：</td>
				<td>{{$memberInfo->member_cardbalance}}</td>
			</tr>
			<tr>
				<td>月卡剩余天数：</td>
				<td>{{$memberInfo->member_carddays}}</td>
			</tr>
			<tr>
				<td>用户地址：</td>
				<td>
					<table class="table">
						<tr>
							<th>ID</th>
							<th>收件人</th>
							<th>电话</th>
							<th>详细地址</th>
							<th>地址类型</th>
						</tr>
						@foreach($memberInfo->address_list as $k => $v)
						<tr>
							<td>{{$v->id}}</td>
							<td>{{$v->receive_name}}</td>
							<td>{{$v->mobile}}</td>
							<td>{{$v->full_address}}</td>
							<td>
								@if($v->address_name == 'WORK')
								公司
								@elseif($v->address_name == 'HOME')
								家庭
								@endif
							</td>
						</tr>
						@endforeach
					</table>
				</td>
			</tr>
			<tr>
				<td>交易记录：</td>
				<td>
					<table class="table">
						<tr>
							<th>订单ID</th>
							<th>商品名称</th>
							<th>下单时间</th>
							<th>订单总额</th>
							<th>卡支付</th>
							<th>微信支付</th>
							<th>订单状态</th>
						</tr>
						@foreach($memberInfo->order_list as $k => $v)
						<tr>
							<td>{{$v->id}}</td>
							<td>{{$v->order_name}}</td>
							<td>{{$v->order_time}}</td>
							<td>{{$v->order_amount}}</td>
							<td>{{$v->card_amount}}</td>
							<td>{{$v->pay_amount}}</td>
							<td>{{$v->order_status}}</td>
						</tr>
						@endforeach
					</table>
				</td>
			</tr>
		</table>
	</div>
</div>