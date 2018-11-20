<style>
    .row1 p {
        text-align: center;
        color: #666;
    }
</style>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">内容概况</h3>

    </div>

    <!-- /.box-header -->
    <div class="box-body">
        <div class="table-responsive">
            <div class="row row1">
              <div class="col-md-3">
                <p><i class="fa fa-tree fa-3x" style="color: #20a53a;" aria-hidden="true"></i></p>
                <p style="padding-top: 10px;">农产品总量<span style="color: #20a53a; font-weight: bold; font-size: 18px;  padding: 0 3px;">{{ $gnum }}</span>件</p>
                <p><a href="{{ url('/admin/goods') }}">管理</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="{{ url('/admin/goods/create') }}">添加</a></p>
              </div>
              <div class="col-md-3">
                <p><i class="fa fa-bath fa-3x" style="color: #ffaa31;" aria-hidden="true"></i></p>
                <p style="padding-top: 10px;">客房总量<span style="color: #ffaa31; font-weight: bold; font-size: 18px;  padding: 0 3px;">{{ $rnum }}</span>件</p>
                <p><a href="#">管理</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#">添加</a></p>
              </div>
              <div class="col-md-3">
                <p><i class="fa fa-file-text-o fa-3x" style="color: #ff31be;" aria-hidden="true"></i></p>
                <p style="padding-top: 10px;">资讯总量<span style="color: #ff31be; font-weight: bold; font-size: 18px;  padding: 0 3px;">{{ $nnum }}</span>件</p>
                <p><a href="{{ url('/admin/news') }}">管理</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="{{ url('/admin/news/create') }}">添加</a></p>
              </div>
              <div class="col-md-3">
                <p><i class="fa fa-users fa-3x" style="color: #29baff;" aria-hidden="true"></i></p>
                <p style="padding-top: 10px;">会员总量<span style="color: #29baff; font-weight: bold; font-size: 18px;  padding: 0 3px;">{{ $mnum }}</span>件</p>
                <p><a href="#">管理</a></p>
              </div>
            </div>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.box-body -->
</div>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">财务概况</h3>

    </div>

    <!-- /.box-header -->
    <div class="box-body">
        <div class="table-responsive">
            <div class="row row1">
              <div class="col-md-1"></div>
              <div class="col-md-2">
                <p><span style="color: #20a53a; font-weight: bold; font-size: 18px;  padding: 0 3px;">{{$allTotal}}</span>元</p>
                <p style="height: 1px; background-color: #20a53a; margin: 15px 30px;"></p>
                <p>总销售金额</p>
              </div>
              <div class="col-md-2">
                <p><span style="color: #ffaa31; font-weight: bold; font-size: 18px;  padding: 0 3px;">{{$goodsTotal}}</span>元</p>
                <p style="height: 1px; background-color: #ffaa31; margin: 15px 30px;"></p>
                <p>农产品销售金额</p>
              </div>
              <div class="col-md-2">
                <p><span style="color: #ff31be; font-weight: bold; font-size: 18px;  padding: 0 3px;">{{$roomTotal}}</span>元</p>
                <p style="height: 1px; background-color: #ff31be; margin: 15px 30px;"></p>
                <p>客房销售金额</p>
              </div>
              <div class="col-md-2">
                <p><span style="color: #29baff; font-weight: bold; font-size: 18px;  padding: 0 3px;">{{$buyTotal}}</span>元</p>
                <p style="height: 1px; background-color: #29baff; margin: 15px 30px;"></p>
                <p>会员卡销售金额</p>
              </div>
              <div class="col-md-2">
                <p><span style="color: #ff4c29; font-weight: bold; font-size: 18px;  padding: 0 3px;">{{$chargeTotal}}</span>元</p>
                <p style="height: 1px; background-color: #ff4c29; margin: 15px 30px;"></p>
                <p>微信购买金额</p>
              </div>
              <div class="col-md-1"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">网站配置信息</h3>

            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    <p><span>操作系统：</span>{{$server}}</p>
                    <p><span>编程语言：</span>{{$php}}</p>
                    <p><span>数据库：</span>{{$database}}</p>
                    <p><span>网站版本：</span>{{$binfo->web_version}}</p>
                    <p><span>更新时间：</span>{{$binfo->update_time}}</p>
                    <p><span>网站域名：</span>{{$binfo->web_domain}}</p>
                    <p><span>备案号：</span>{{$binfo->web_right}}</p>
                    <p><span>最近登录：</span>用户名（{{$userInfo->username}}） 登录IP（{{$userInfo->last_login_ip}}） 登录时间（<span style="color: #F00;">{{$userInfo->last_login_time}}</span>）</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">最新发布</h3>


            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    @foreach($ninfo as $k => $v)
                    <p><span style="padding-right: 15px;">{{$v->created_at}}</span><a href="#">{{$v->title}}</a></p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>