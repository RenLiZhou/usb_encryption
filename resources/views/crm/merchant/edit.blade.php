@extends("crm.layouts.main")

@section("content")
    <div class="or-mid">
        <form class="layui-form layui-form-pane" style="width:95%;">
            <div class="layui-form-item">
                <label class="layui-form-label">商户名称</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="name" lay-verify="required" value="{{ $merchant->name }}" placeholder="请输入用户名">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">登录名</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input layui-disabled" value="{{ $merchant->username }}" disabled>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">邮箱</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="email" value="{{ $merchant->email }}" placeholder="请输入邮箱">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">有效期</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input @if($merchant->is_permanent == 1) layui-disabled @endif" id="expire_date" name="expire_date" value="{{ $merchant->expire_time }}" placeholder="请设置有效期" @if($merchant->is_permanent == 1) disabled @endif>
                    <input type="checkbox" id="expire_perpetual" @if($merchant->is_permanent == 1) checked @endif lay-filter="expire_perpetual" name="expire_perpetual" value="1" title="永久有效" lay-skin="primary">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">版本</label>
                <div class="layui-input-block">
                    <select name="version_id" lay-verify="required">
                        <option value="">请选择版本</option>
                        @foreach($versions as $version)
                            <option value="{{ $version->id }}" @if(!empty($merchant->version[0]) && $merchant->version[0]->id == $version->id) selected @endif>{{ $version->title_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">语言</label>
                <div class="layui-input-block">
                    <select name="language_id" lay-verify="required">
                        <option value="">请选择语言</option>
                        @foreach($languages as $language)
                            <option value="{{ $language->id }}"  @if($merchant->lang_id == $language->id) selected @endif>{{ $language->desc }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">商户状态</label>
                <div class="layui-input-block">
                    <input type="radio" name="status" value="1" title="正常" @if($merchant->status == 1) checked @endif>
                    <input type="radio" name="status" value="0" title="禁用" @if($merchant->status == 0) checked @endif>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">已授权数量</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input layui-disabled" value="{{ $merchant->auth_number }}" disabled>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">跟目录</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input layui-disabled" value="{{ $merchant->root_directory }}" disabled>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">额外授权数量</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="add_auth_count" value="{{ $merchant->add_auth_count }}" placeholder="请输入邮箱">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">修改密码</label>
                <div class="layui-input-block">
                    <input type="password" name="password" placeholder="请输入修改密码" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">备注</label>
                <div class="layui-input-block">
                    <textarea placeholder="备注（选填）" name="remarks" class="layui-textarea">{{ $merchant->remarks }}</textarea>
                </div>
            </div>

            @crm_permission('crm.merchant.update')
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="update-user" data-url="{{ route('crm.merchant.update', ['merchant' => $merchant->id]) }}" data-type="PATCH">立即提交</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            @endcrm_permission
        </form>
    </div>
@endsection

@section("js")
    <script type="text/javascript">
        layui.use(['form', 'ori', 'laydate','dialog'], function () {
            var form = layui.form,
                ori = layui.ori,
                laydate = layui.laydate,
                dialog = layui.dialog,
                $ = layui.$;

            laydate.render({
                elem: '#expire_date', //指定元素
                type: 'datetime'
            });

            form.on('checkbox(expire_perpetual)', function (data) {
                if(data.elem.checked){
                    $("#expire_date").attr("disabled",true);
                    $("#expire_date").addClass('layui-disabled');
                }else{
                    $("#expire_date").attr("disabled",false);
                    $("#expire_date").removeClass('layui-disabled');
                }
            });

            form.on('submit(update-user)', function (data) {
                ori.submit($(this), data.field, function () {
                    parent.location.reload();
                });
                return false;
            });
        });
    </script>
@endsection
