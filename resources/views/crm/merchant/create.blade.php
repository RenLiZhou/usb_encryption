@extends("crm.layouts.main")

@section("content")
    <div class="or-mid">
        <form class="layui-form  layui-form-pane" style="width:95%;">
            <div class="layui-form-item">
                <label class="layui-form-label">商户名称</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="name" lay-verify="required" placeholder="请输入用户名">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">登录名</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="username" lay-verify="required" placeholder="请输入用户名(登录名)">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">邮箱</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="email" placeholder="请输入邮箱">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">有效期(UTC)</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" id="expire_date" name="expire_date" placeholder="请设置有效期">
                    <input type="checkbox" id="expire_perpetual" lay-filter="expire_perpetual" name="expire_perpetual" value="1" title="永久有效" lay-skin="primary">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">版本</label>
                <div class="layui-input-block">
                    <select name="version_id" lay-verify="required">
                        <option value="">请选择版本</option>
                        @foreach($versions as $version)
                            <option value="{{ $version->id }}">{{ $version->title_name }}</option>
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
                            <option value="{{ $language->id }}">{{ $language->desc }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">商户状态</label>
                <div class="layui-input-block">
                    <input type="radio" name="status" value="1" title="正常" checked>
                    <input type="radio" name="status" value="0" title="禁用">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">密码</label>
                <div class="layui-input-block">
                    <input type="password" name="password" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">确认密码</label>
                <div class="layui-input-block">
                    <input type="password" id="password_confirmation" required lay-verify="required" placeholder="请输再次输入密码" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">备注</label>
                <div class="layui-input-block">
                    <textarea placeholder="备注（选填）" name="remarks" class="layui-textarea"></textarea>
                </div>
            </div>

            @crm_permission('crm.merchant.store')
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="create-data" data-url="{{ route('crm.merchant.store') }}" data-type="POST">立即提交</button>
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

            form.on('submit(create-data)', function (data) {
                password_confirmation = $("#password_confirmation").val();
                if(password_confirmation != data.field.password){
                    dialog.erMsg('两次密码不一致');
                    return false;
                }
                ori.submit($(this), data.field, function () {
                    parent.location.reload();
                });
                return false;
            });
        });
    </script>
@endsection
