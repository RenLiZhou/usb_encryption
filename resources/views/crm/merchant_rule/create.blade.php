@extends("crm.layouts.main")

@section("content")
    <div class="or-mid">
        <form class="layui-form layui-form-pane"  style="width:95%;">
            <div class="layui-form-item">
                <label class="layui-form-label">父级</label>
                <div class="layui-input-block">
                    <select name="pid" lay-verify="required">
                        <option data-level="0" value="0">默认顶级</option>
                    @foreach($rules as $rule)
                        @if ($rule['level'] < 3)
                        <option data-level="{{ $rule['level'] }}" value="{{ $rule['id'] }}">{{ $rule['ltitle'] }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">权限标识</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="title" lay-verify="required" placeholder="权限标识">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">链接</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="href" placeholder="请输入链接">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">图标</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="icon" placeholder="请输入图标">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">是否验证权限</label>
                <div class="layui-input-block">
                    <select name="check" lay-verify="required">
                        <option value="0">不验证</option>
                        <option value="1">验证</option>
                    </select>
                </div>
            </div>

            @crm_permission('crm.merchant.rule.store')
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="create-rule" data-url="{{ route('crm.merchant.rule.store') }}" data-type="POST">立即提交</button>
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

            form.on('submit(create-rule)', function (data) {
                var level = $('[name="pid"]').find('option:selected').attr('data-level');
                if (level >= 2) {
                    dialog.erMsg('权限最多二级,请重新选择父级');
                    return false;
                }
                data.field.level = level - (-1);
                ori.submit($(this), data.field, function () {
                    parent.location.reload();
                });
                return false;
            });
        });
    </script>
@endsection
