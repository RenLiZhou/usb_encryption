@extends("crm.layouts.main")

@section("content")
    <div class="or-mid">
        <form class="layui-form layui-form-pane"  style="width:95%;">
            <div class="layui-form-item">
                <label class="layui-form-label">标识</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="name" lay-verify="required" value="{{ $data->name }}" placeholder="请输入标识">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">语言名称</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="desc" lay-verify="required" value="{{ $data->desc }}" placeholder="请输入语言名称">
                </div>
            </div>

            @crm_permission('crm.language.update')
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="edit-data" data-url="{{ route('crm.language.update', ['language' => $data->id]) }}" data-type="PATCH">立即提交</button>
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

            form.on('submit(edit-data)', function (data) {
                ori.submit($(this), data.field, function () {
                    parent.location.reload();
                });
                return false;
            });
        });
    </script>
@endsection
