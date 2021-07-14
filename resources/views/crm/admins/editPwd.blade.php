@extends("crm.layouts.main")

@section("content")
    <div class="or-mid">
        <form class="layui-form  layui-form-pane"  style="width:95%;">
            <div class="layui-form-item">
                <label class="layui-form-label">密码</label>
                <div class="layui-input-block">
                    <input type="password" name="password" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">确认密码</label>
                <div class="layui-input-block">
                    <input type="password" name="password_confirmation" required lay-verify="required" placeholder="请输再次输入密码" autocomplete="off" class="layui-input">
                </div>
            </div>
            @crm_permission('crm.admin.password')
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="update-pwd" data-url="{{ route('crm.admin.password', ['admin' => $admin->id]) }}" data-type="PATCH">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
            @endcrm_permission
        </form>
    </div>
@endsection

@section("js")
    <script type="text/javascript">
        layui.use(['form', 'ori'], function () {
            var form = layui.form,
                ori = layui.ori,
                $ = layui.$;

            form.on('submit(update-pwd)', function (data) {
                ori.submit($(this), data.field);
                return false;
            });
        });
    </script>
@endsection
