<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">微信购买明细</h3>
    </div>
    <div class="box-body">
    	<div class="row">
            <div class="col-md-1">
                商品ID：
            </div>
            <div class="col-md-2">
                <input type="text" name="goods_id" value="" class="form-control" />
            </div>
            <div class="col-md-1"></div>
    		<div class="col-md-1">
    			下单时间：
    		</div>
    		<div class="col-md-2">
    			<div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" name="startDate" value="{{$lastYearMonth}}" class="form-control datetime" />
                </div>
    		</div>
    		<div class="col-md-1" style="text-align: center;">
    			~
			</div>
			<div class="col-md-2">
    			<div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" name="endDate" value="{{$currYearMonth}}" class="form-control datetime" />
                </div>
			</div>
			<div class="col-md-2">
				<button type="button" class="btn btn-primary" id="search">搜索</button>
			</div>
    	</div>
    	<table class="table table-bordered" style="margin-top: 15px;">
    		<thead>
    			<tr>
	    			<th>ID</th>
	    			<th>订单编号</th>
	    			<th>商品名称</th>
                    <th>购买数量</th>
                    <th>商品单价</th>
                    <th>折扣卡单价</th>
                    <th>预订天数</th>
                    <th>下单时间</th>
	    			<th>支付时间</th>
    			</tr>
    		</thead>
    		<tbody id="table">
    			
    		</tbody>
    	</table>
    </div>
</div>
<script>
    $(function(){
         $('.datetime').parent().datetimepicker({
                "format":"YYYY-MM",
                "locale":"zh-CN",
                "allowInputToggle":true
         });
        getSearch();
    });
    $('#search').click(function(){

        getSearch();
    });
    function getSearch()
    {
        var startDate = $('input[name=startDate]').val();
        var endDate = $('input[name=endDate]').val();
        var goodsId = $('input[name=goods_id]').val();
        $.ajax({
            url: '/admin/finances/getSales',
            method: 'get',
            data: {
                startDate: startDate,
                endDate: endDate,
                goodsId: goodsId
            },
            dataType: 'json',
            success: function(jsonData){
                $('#table').html('');
                $.each(jsonData, function(k, v){
                    str = '';
                    str += '<tr>';
                    str += '<td>'+v.id+'</td>';
                    str += '<td>'+v.order_sn+'</td>';
                    str += '<td>'+v.goods_name+'</td>';
                    str += '<td>'+v.quantity+'</td>';
                    str += '<td>'+v.goods_price+'</td>';
                    str += '<td>'+v.card_balance+'</td>';
                    str += '<td>'+v.book_days+'</td>';
                    str += '<td>'+v.order_time+'</td>';
                    str += '<td>'+v.pay_time+'</td>';
                    str += '</tr>';
                    $('#table').append(str);
                });
            }
        });
    }
</script>