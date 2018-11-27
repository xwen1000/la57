<style>
	.span1 {
		color: #20a53a;
		font-size: 24px;
		margin-right: 5px;
	}
	p {
		text-align: center;
	}
	.p2 {
		height: 1px;
		background-color: #20a53a;
	}
</style>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><span style="margin-right: 5px;">{{$currYearMonth}}</span>财务概况</h3>
    </div>
    <div class="box-body">
        <div class="row">
        	<div class="col-md-1"></div>
        	<div class="col-md-2">
        		<p><span class="span1">{{$allTotal}}</span>元</p>
        		<p class="p2"></p>
        		<p>总销售金额</p>
        	</div>
        	<div class="col-md-2">
        		<p><span class="span1" style="color: #ffaa31;">{{$goodsTotal}}</span>元</p>
        		<p class="p2" style="color: #ffaa31;"></p>
        		<p>农产品销售金额</p>
        	</div>
        	<div class="col-md-2">
        		<p><span class="span1" style="color: #ff31be;">{{$roomTotal}}</span>元</p>
        		<p class="p2" style="color: #ff31be;"></p>
        		<p>客房销售金额</p>
        	</div>
        	<div class="col-md-2">
        		<p><span class="span1" style="color: #29baff;">{{$chargeTotal}}</span>元</p>
        		<p class="p2" style="color: #29baff;"></p>
        		<p>会员卡销售金额</p>
        	</div>
        	<div class="col-md-2">
        		<p><span class="span1" style="color: #ff4c29;">{{$buyTotal}}</span>元</p>
        		<p class="p2" style="color: #ff4c29;"></p>
        		<p>微信购买金额</p>
        	</div>
        	<div class="col-md-1"></div>
        </div>
    </div>
</div>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">历史财务概况</h3>
    </div>
    <div class="box-body">
    	<div class="row">
    		<div class="col-md-1">
    			查询时间：
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
	    			<th>时间</th>
	    			<th>总销售金额</th>
	    			<th>农产品销售金额</th>
	    			<th>客房销售金额</th>
	    			<th>会员卡销售金额</th>
	    			<th>微信购买金额</th>
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
			url: '/admin/finances/getSearch',
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
					str += '<td>'+k+'</td>'
					str += '<td>'+parseFloat(v.allTotal).toFixed(2)+'</td>';
					str += '<td>'+parseFloat(v.goodsTotal).toFixed(2)+'</td>';
					str += '<td>'+parseFloat(v.roomTotal).toFixed(2)+'</td>';
					str += '<td>'+parseFloat(v.chargeTotal).toFixed(2)+'</td>';
					str += '<td>'+parseFloat(v.buyTotal).toFixed(2)+'</td>';
					str += '</tr>';
					$('#table').append(str);
				});
			}
		});
	}
</script>
