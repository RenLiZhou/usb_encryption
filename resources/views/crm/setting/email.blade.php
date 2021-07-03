@extends("crm.layouts.main")

@section("content")
    <div class="or-mid">
        <form class="layui-form layui-form-pane"  style="width:95%;">

            <div class="layui-form-item">
                <label class="layui-form-label">激活码前5位</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="prefix" lay-verify="required" placeholder="请输入激活码前5位">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">新增USB授权数量</label>
                <div class="layui-input-block">
                    <input type="number" class="layui-input" name="auth_count" lay-verify="required" placeholder="请输入新增USB授权数量">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">创建数量</label>
                <div class="layui-input-block">
                    <input type="number" class="layui-input" name="amount" lay-verify="required" placeholder="请输入创建数量">
                </div>
            </div>



            @crm_permission('crm.activation_code.store')
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="create-data" data-url="{{ route('crm.activation_code.store') }}" data-type="POST">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
            @endcrm_permission
        </form>
    </div>
@endsection

@section("js")
    <script type="text/javascript">
        layui.use(['form', 'ori', 'dialog'], function () {
            var form = layui.form,
                ori = layui.ori,
                dialog = layui.dialog,
                $ = layui.$;

            form.on('submit(create-data)', function (data) {
                if(data.field.prefix.length != 5){
                    dialog.erMsg('前缀必须为5个字符');
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
