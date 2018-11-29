<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">会员充值明细</h3>
    </div>
    <div class="box-body">
    	<div class="row">
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
	    			<th>卡名称</th>
	    			<th>购买用户</th>
	    			<th>支付金额</th>
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
        $.ajax({
            url: '/admin/finances/getCharges',
            method: 'get',
            data: {
                startDate: startDate,
                endDate: endDate
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
                    str += '<td>'+v.nickname+'</td>';
                    str += '<td>'+v.pay_amount+'</td>';
                    str += '<td>'+v.order_time+'</td>';
                    str += '<td>'+v.pay_time+'</td>';
                    str += '</tr>';
                    $('#table').append(str);
                });
            }
        });
    }
</script>