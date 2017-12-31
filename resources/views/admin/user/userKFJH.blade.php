@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">患者管理</li>
                    <li class="active">康复计划管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +保存患者康复计划
                </button>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="white-bg">
            <div style="padding: 15px;">
                <div class="font-size-16 text-info">
                    患者病历详情
                    <span class="pull-right margin-right-10 font-size-12" style="cursor: pointer;"
                          onclick="clickShowCaseInfo();">
                                <i class="fa fa-angle-down opt-btn-i-size"></i>
                                展开
                            </span>
                </div>
                <div id="userCaseInfo_div" style="display: none;">
                    <div class="margin-top-10 font-size-14 grey-bg">
                        <div style="padding: 10px;">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <td rowspan="2">
                                        <img src="{{ $user->avatar ? $user->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/default_headicon.png')}}"
                                             style="width: 60px;height: 60px;">
                                    </td>
                                    <td>患者</td>
                                    <td>{{$user->real_name}}</td>
                                    <td>性别</td>
                                    <td>
                                        @if($user->gender=="0")
                                            保密
                                        @endif
                                        @if($user->gender=="1")
                                            男
                                        @endif
                                        @if($user->gender=="2")
                                            女
                                        @endif
                                    </td>
                                    <td>联系方式</td>
                                    <td>{{$user->phonenum}}</td>
                                </tr>
                                <tr>
                                    <td>出生日期</td>
                                    <td>{{$user->birthday}}</td>
                                    <td>年龄</td>
                                    <td>{{$user->age}}岁</td>
                                    <td>--</td>
                                    <td>--</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="margin-top-10 font-size-14 grey-bg">
                        <div style="padding: 10px;">
                            <table class="table table-bordered table-hover">
                                <thead>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>状态</td>
                                    <td>--</td>
                                    <td>手术时间</td>
                                    <td>{{$userCase->ss_time}}</td>
                                    <td>首次弯腿时间</td>
                                    <td>{{$userCase->wt_time}}</td>
                                </tr>
                                <tr>
                                    <td>主治医师</td>
                                    <td class="">{{$userCase->zz_doctor->name}}</td>
                                    <td>康复医师</td>
                                    <td class="">{{$userCase->kf_doctor->name}}</td>
                                    <td>病历建立时间</td>
                                    <td>{{$userCase->created_at}}</td>
                                </tr>
                                </tbody>
                            </table>
                            <div id="userCase_desc_div">
                                {{$userCase->desc}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="margin-top-10  font-size-14">
                    点击查看：<a href="#"><span
                                class="text-aqua">患者执行计划详情<i
                                    class="fa fa-link margin-left-5"></i></span></a>
                </div>
                <div class="margin-top-10  font-size-14">
                    <div class="row">
                        <div class="col-lg-8">
                            <select id="kfmb_id" name="kfmb_id" class="form-control">
                                @foreach($kfmbs as $kfmb)
                                    <option value="{{$kfmb->id}}">{{$kfmb->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <button type="button" class="btn btn-danger btn-sm" onclick="clickGetKFMB();">点击生成模板计划
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--计划部分--}}
        <section class="content">
            <div id="message-content">

            </div>
        </section>


        <script id="message-content-template" type="text/x-dot-template">
            <!-- row -->
            <div class="row margin-top-10">
                <div class="col-md-12">
                    <!-- The time line -->
                    <ul class="timeline">
                        <!-- timeline time label -->
                        <li class="time-label">
                  <span class="bg-aqua-active">
                    患者康复计划
                  </span>
                        </li>
                        <!-- /.timeline-label -->
                        <li class="time-label">
                            <div class="text-center">
                                <img src="{{URL::asset('/img/add_button_icon.png')}}" style="width: 36px;height: 36px;"
                                     onclick="editJH(0,'add');">
                            </div>
                        </li>
                        <!-- timeline item -->
                        @{{for(var i=0;i
                        <it.jhs.length ;i++){}}
                        <li>
                            <i class="fa fa-clock-o bg-aqua"></i>

                            <div class="timeline-item">
                                <!--右侧-->
                                <span class="time font-size-14">@{{=it.jhs[i].btime_type_str}}</span>

                                <h3 class="timeline-header">

                                    @{{? it.jhs[i].btime_type === '0' || it.jhs[i].btime_type === '1'}}

                                    <button type="button" class="btn btn-info">
                                        @{{=it.jhs[i].start_time}}@{{=it.jhs[i].start_unit_str}}
                                    </button>
                                    -
                                    <button type="button" class="btn btn-info">
                                        @{{=it.jhs[i].end_time}}@{{=it.jhs[i].end_unit_str}}
                                    </button>
                                    @{{?}}
                                    @{{? it.jhs[i].btime_type === '2' }}
                                    <button type="button" class="btn btn-info">
                                        @{{=it.jhs[i].set_date}}
                                    </button>
                                    @{{?}}
                                </h3>
                                <div class="timeline-body">
                                    <div>
                                        @{{=it.jhs[i].desc_str}}
                                    </div>
                                    <div class="margin-top-10 grey-bg">

                                        @{{for(var j=0;j
                                        <it.jhs
                                                [i].jhsjs.length;j++){}}
                                        <div style="padding: 10px;">
                                            <i class="fa fa-align-justify"></i>
                                            <span class="margin-left-15">@{{=it.jhs[i].jhsjs[j].sjx.name}}
                                                (@{{=it.jhs[i].jhsjs[j].sjx.unit}})</span>
                                            <span class="margin-left-5 text-aqua">阈值范围 @{{=it.jhs[i].jhsjs[j].min_value}}
                                                - @{{=it.jhs[i].jhsjs[j].max_value}}</span>

                                            <i class="fa fa-minus-circle text-info pull-right btn"
                                               onclick="delSJ(@{{=i}},@{{=j}})"></i>
                                        </div>
                                        @{{}}}

                                        <div style="padding: 10px;" class="text-info btn">
                                            <i class="fa fa-plus-circle"></i>
                                            <span class="margin-left-15" onclick="editSJ(@{{=i}},'add');">添加数据采集</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="timeline-footer">
                                    <div class="text-right margin-top-15"><img
                                                src="{{URL::asset('/img/up_pointer_icon.png')}}"
                                                class="opt-btn-size margin-right-10" onclick="moveUpJH(@{{=i}});"> <img
                                                src="{{URL::asset('/img/down_pointer_icon.png')}}"
                                                class="opt-btn-size margin-right-10" onclick="moveDownJH(@{{=i}});">
                                        <img
                                                src="{{URL::asset('/img/edit_icon.png')}}"
                                                class="opt-btn-size margin-right-10" onclick="editJH(@{{=i}},'edit');">
                                        <img
                                                src="{{URL::asset('/img/delete_icon.png')}}"
                                                class="opt-btn-size" onclick="delJH(@{{=i}});"></div>
                                </div>
                            </div>
                        </li>

                        <!-- END timeline item -->
                        <!-- timeline item -->
                        <!-- END timeline item -->
                        <!-- timeline time label -->
                        <li class="time-label">
                            <div class="text-center">
                                <img src="{{URL::asset('/img/add_button_icon.png')}}" style="width: 36px;height: 36px;"
                                     onclick="editJH(@{{=i+1}},'add')">
                            </div>
                        </li>
                        <!-- /.timeline-label -->
                        <!-- timeline item -->
                        @{{}}}
                    </ul>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </script>


        <!--新建编辑宣教步骤对话框-->
        <div class="modal fade modal-margin-top-m" id="editJHModal" tabindex="-1" role="dialog">


        </div>

        <script id="editJHModal-content-template" type="text/x-dot-template">
            <div class="modal-dialog">
                <div class="modal-content message_align">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title">管理康复计划</h4>
                    </div>
                    <form id="editJH" action="" method="post" class="form-horizontal">
                        <div class="modal-body">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="desc" class="col-sm-2 control-label">说明*</label>

                                    <div class="col-sm-10">
                                    <textarea id="desc" name="desc" class="form-control" rows="5"
                                              placeholder="请输入 ...">@{{=it.desc}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top: 15px;">
                                    <label for="btime_type" class="col-sm-2 control-label">基线*</label>

                                    <div class="col-sm-10">
                                        <select id="btime_type" name="btime_type" class="form-control"
                                                onchange="clickSetDate();">
                                            <option value="0">手术后</option>
                                            <option value="1">首次弯腿后
                                            </option>
                                            <option value="2">指定日期
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div id="btime_type_01">
                                    <div class="form-group">
                                        <label for="start_time" class="col-sm-2 control-label">开始</label>

                                        <div class="col-sm-10">
                                            <div class="form-inline">
                                                <input id="start_time" name="start_time" type="number"
                                                       class="form-control pull-left"
                                                       placeholder="开始时间"
                                                       value="@{{=it.start_time}}">
                                                <select id="start_unit" class="form-control">
                                                    <option value="0">天</option>
                                                    <option value="1">周</option>
                                                    <option value="2">月</option>
                                                </select>
                                                <span class="pull-right text-info text-oneline"
                                                      style="line-height: 30px;">计划的执行不早于开始时间</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="end_time" class="col-sm-2 control-label">结束</label>

                                        <div class="col-sm-10">
                                            <div class="form-inline">
                                                <input id="end_time" name="end_time" type="number"
                                                       class="form-control pull-left"
                                                       placeholder="结束时间"
                                                       value="@{{=it.end_time}}">
                                                <select id="end_unit" class="form-control">
                                                    <option value="0">天</option>
                                                    <option value="1">周</option>
                                                    <option value="2">月</option>
                                                </select>
                                                <span class="pull-right text-info text-oneline"
                                                      style="line-height: 30px;">计划的执行不晚于结束时间</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="btime_type_2" style="display: none;">
                                    <div class="form-group">
                                        <label for="start_time" class="col-sm-2 control-label">开始</label>

                                        <div class="col-sm-10">
                                            <div class="form-inline">
                                                <input id="set_date" name="set_date" type="date"
                                                       class="form-control pull-left"
                                                       placeholder="指定日期"
                                                       value="@{{=it.set_date}}">
                                                <span class="pull-right text-info text-oneline"
                                                      style="line-height: 30px;">当天将对患者进行计划提醒</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="button" data-value="" class="btn btn-success"
                                    onclick="clickEditJH(@{{=it.index}},'@{{=it.opt}}');">确定
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </script>


        <!--数据采集模板-->
        <div class="modal fade modal-margin-top-m" id="editSJModal" tabindex="-1" role="dialog">

        </div>


        <script id="editSJModal-content-template" type="text/x-dot-template">
            <div class="modal-dialog">
                <div class="modal-content message_align">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title">管理采集数据</h4>
                    </div>
                    <form id="editSJ" action="" method="post" class="form-horizontal">
                        <div class="modal-body">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="sjx_id" class="col-sm-2 control-label">采集数据</label>
                                    <div class="col-sm-10">
                                        <select id="sjx_id" name="sjx_id" class="form-control">
                                            @foreach($sjxs as $sjx)
                                                <option id="sjx_{{$sjx->id}}" data-name="{{$sjx->name}}"
                                                        data-unit="{{$sjx->unit}}"
                                                        value="{{$sjx->id}}">{{$sjx->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="min_value" class="col-sm-2 control-label">最小值*</label>

                                    <div class="col-sm-10">
                                        <input id="min_value" name="min_value" type="number" class="form-control"
                                               placeholder="该值为阈值最小值，小于最小值将报警"
                                               value="@{{=it.min_value}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="max_value" class="col-sm-2 control-label">最大值*</label>

                                    <div class="col-sm-10">
                                        <input id="max_value" name="max_value" type="number" class="form-control"
                                               placeholder="该值为阈值最大值，大于最大值将报警"
                                               value="@{{=it.max_value}}">
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="button" data-value="" class="btn btn-success"
                                    onclick="clickEditSJ(@{{=it.jh_index}},'add');">确定
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </script>


        <!--提示modal-->
        <div class="modal fade" id="tipModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content message_align">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title">提示信息</h4>
                    </div>
                    <div class="modal-body" id="tipModalBody">

                    </div>
                    <div class="modal-footer">
                        <button id="delConfrimModal_confirm_btn" data-value=""
                                class="btn btn-success"
                                data-dismiss="modal">确定
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

    </section>


@endsection


@section('script')
    <script type="application/javascript">

        var kfjhInfo = {
            id:{{$userCase->id}}
        };

        $(function () {
            $('[data-toggle="tooltip"]').tooltip();

            //设置康复模板
            $("#kfmb_id").val({{$userCase->kfmb_id}});
            //从互联网加载数据
            getUserCaseById("{{URL::asset('')}}", {id: {{$userCase->id}}, level: "012"}, function (ret, err) {
                var msgObj = ret.ret;
                kfjhInfo.jhs = msgObj.jhs;
                loadHtml();
            });
        })

        function clickShowCaseInfo() {
            $("#userCaseInfo_div").toggle();
        }

        //点击获取康复模板信息
        function clickGetKFMB() {
            var kfmb_id = $("#kfmb_id").val();
            getKFMBById("{{URL::asset('')}}", {id: kfmb_id, level: "03"}, function (ret, err) {
                var msgObj = ret.ret;
                kfjhInfo.jhs = msgObj.jhs;
                loadHtml();
            });
        }


        //    加载页面
        function loadHtml() {
            //清理页面
            $("#message-content").empty();
            //康复模板的描述
            kfjhInfo.desc_str = Text2Html(kfjhInfo.desc);
            //整理数据
            for (var i = 0; i < kfjhInfo.jhs.length; i++) {
                kfjhInfo.jhs[i].desc_str = Text2Html(kfjhInfo.jhs[i].desc);
                kfjhInfo.jhs[i].btime_type_str = getBtimeTypeStr(kfjhInfo.jhs[i].btime_type);
                kfjhInfo.jhs[i].start_unit_str = getTimeUnitStr(kfjhInfo.jhs[i].start_unit);
                kfjhInfo.jhs[i].end_unit_str = getTimeUnitStr(kfjhInfo.jhs[i].end_unit);
                kfjhInfo.jhs[i].status_str = getJHStatus(kfjhInfo.jhs[i].status);
            }
            //加载页面
            console.log("kfjhInfo:" + JSON.stringify(kfjhInfo));
            var interText = doT.template($("#message-content-template").text());
            $("#message-content").html(interText(kfjhInfo));

        }

        //点击编辑康复计划
        function editJH(index, edit_or_add) {
            console.log("index index:" + index + " edit_or_add:" + edit_or_add);
            var jhs = kfjhInfo.jhs;
            var jhObj = {
                desc: "",
                btime_type: "0",
                start_time: 0,
                start_unit: 0,
                end_time: 0,
                end_unit: 0,
                set_date: "",
                "index": index,
                "opt": edit_or_add,
                jhsjs: []
            };
            //如果是新建
            if (edit_or_add == "add") {

            } else {        //如果是编辑
                jhObj.desc = nullToEmptyStr(kfjhInfo.jhs[index].desc);
                jhObj.start_time = kfjhInfo.jhs[index].start_time;
                jhObj.start_unit = kfjhInfo.jhs[index].start_unit;
                jhObj.end_time = kfjhInfo.jhs[index].end_time;
                jhObj.end_unit = kfjhInfo.jhs[index].end_unit;
                jhObj.btime_type = kfjhInfo.jhs[index].btime_type;
                jhObj.set_date = kfjhInfo.jhs[index].set_date;
            }
            //设置
            console.log("editJH jhObj:" + JSON.stringify(jhObj));
            console.log("jhObj:" + JSON.stringify(jhObj));
            var interText = doT.template($("#editJHModal-content-template").text());
            $("#editJHModal").html(interText(jhObj));
            //基线时间
            $("#btime_type").val(jhObj.btime_type);
            //进行设置
            clickSetDate();
            /*
             * 需要进行select赋值
             */
            $("#start_unit").val(jhObj.start_unit);
            $("#end_unit").val(jhObj.end_unit);
            $("#editJHModal").modal('show');
        }


        //点击保存
        function clickEditJH(index, edit_or_add) {
            var jhObj = {
                desc: $("#desc").val(),
                btime_type: $("#btime_type").val(),
                start_time: $("#start_time").val(),
                start_unit: $("#start_unit").val(),
                end_time: $("#end_time").val(),
                end_unit: $("#end_unit").val(),
                set_date: $("#set_date").val(),
                jhsjs: []
            };
            console.log("jhObj:" + JSON.stringify(jhObj));

            //合规校验
            if (judgeIsNullStr(jhObj.desc)) {
                $("#desc").focus();
                return;
            }
            if (jhObj.btime_type == "0" || jhObj.btime_type == "1") {
                if (judgeIsNullStr(jhObj.start_time)) {
                    $("#start_time").focus();
                    return;
                }
                if (judgeIsNullStr(jhObj.end_time)) {
                    $("#end_time").focus();
                    return;
                }
            }
            if (jhObj.btime_type == "2") {
                if (judgeIsNullStr(jhObj.set_date)) {
                    $("#set_date").focus();
                    return;
                }
            }
            //进行操作
            if (edit_or_add == "add") {
                kfjhInfo.jhs.splice(index, 0, jhObj);
            } else {
                kfjhInfo.jhs[index].desc = jhObj.desc;
                kfjhInfo.jhs[index].btime_type = jhObj.btime_type;
                kfjhInfo.jhs[index].start_time = jhObj.start_time;
                kfjhInfo.jhs[index].start_unit = jhObj.start_unit;
                kfjhInfo.jhs[index].end_time = jhObj.end_time;
                kfjhInfo.jhs[index].end_unit = jhObj.end_unit;
                kfjhInfo.jhs[index].set_date = jhObj.set_date;
            }
            console.log("kfjhInfo:" + JSON.stringify(kfjhInfo));
            loadHtml();
            $("#editJHModal").modal('hide');
        }


        //删除步骤
        function delJH(index) {
            kfjhInfo.jhs.splice(index, 1);
            loadHtml();
        }


        //上移宣教信息
        function moveUpJH(index) {
            if (index == 0) {
                return;
            }
            var tempObj = kfjhInfo.jhs[index];
            kfjhInfo.jhs[index] = kfjhInfo.jhs[index - 1];
            kfjhInfo.jhs[index - 1] = tempObj;
            loadHtml();
        }

        //下移宣教信息
        function moveDownJH(index) {
            if (index == kfjhInfo.jhs.length - 1) {
                return;
            }
            var tempObj = kfjhInfo.jhs[index];
            kfjhInfo.jhs[index] = kfjhInfo.jhs[index + 1];
            kfjhInfo.jhs[index + 1] = tempObj;
            loadHtml();
        }

        //编辑数据
        function editSJ(jh_index, edit_or_add) {

            console.log("editSJ index:" + jh_index + " edit_or_add:" + edit_or_add);
            //新建数据项
            var jhsjObj = {
                sjx_id: 0,
                min_value: 0,
                max_value: 0,
                jh_index: jh_index
            };
            //如果是新建
            if (edit_or_add == "add") {

            } else {        //如果是编辑

            }
//        console.log("jhObj:" + JSON.stringify(jhObj));
            var interText = doT.template($("#editSJModal-content-template").text());
            $("#editSJModal").html(interText(jhsjObj));
            $("#editSJModal").modal('show');
        }


        //点击编辑
        function clickEditSJ(jh_index, edit_or_add) {
            console.log("kfjhInfo:" + JSON.stringify(kfjhInfo) + " jh_index:" + jh_index);
            var jhsjObj = {};
            jhsjObj.sjx_id = $("#sjx_id").val();
            jhsjObj.min_value = $("#min_value").val();
            jhsjObj.max_value = $("#max_value").val();
            jhsjObj.sjx = {
                name: $("#sjx_" + jhsjObj.sjx_id).attr("data-name"),
                unit: $("#sjx_" + jhsjObj.sjx_id).attr("data-unit")
            }
            kfjhInfo.jhs[jh_index].jhsjs.push(jhsjObj);
            console.log("kfjhInfo:" + JSON.stringify(kfjhInfo));
            loadHtml();
            $("#editSJModal").modal('hide');
        }

        //删除数据项
        function delSJ(jh_index, sj_index) {
            kfjhInfo.jhs[jh_index].jhsjs.splice(sj_index, 1);
            loadHtml();
        }

        //点击更换时间
        function clickSetDate() {
            var btime_type = $("#btime_type").val();
            console.log("clickSetDate btime_type:" + btime_type);
            if (btime_type == "0" || btime_type == "1") {
                $("#btime_type_01").show();
                $("#btime_type_2").hide();
            }
            if (btime_type == "2") {
                $("#btime_type_01").hide();
                $("#btime_type_2").show();
            }
        }


        //点击保存
        function clickAdd() {
            console.log("clickAdd kfjhInfo:" + JSON.stringify(kfjhInfo));
            //没有康复计划
            if (kfjhInfo.jhs.length <= 0) {
                $("#tipModalBody").html('<p>康复模板id为空，请联系管理员处理</p>');
                $("#tipModal").modal('show');
                return;
            }
            delete kfjhInfo.desc_str;
            //进行计划排序
            for (var i = 0; i < kfjhInfo.jhs.length; i++) {
                kfjhInfo.jhs[i].seq = i;
            }
            //调用接口进行编辑
            editUserCaseKFJH("{{URL::asset('')}}", JSON.stringify(kfjhInfo), function (ret, err) {
                //提示保存成功
                if (ret.result == true) {
                    $("#tipModalBody").html('<p>康复模板计划保存成功</p>');
                    $("#tipModal").modal('show');
                    kfjhInfo = ret.ret;
                    loadHtml();
                } else {
                    $("#tipModalBody").html("<p>宣教信息保存失败，请联系<span class='text-info'>管理员处理</span></p>");
                    $("#tipModal").modal('show');
                }
            })
        }

    </script>
@endsection