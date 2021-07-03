@extends("crm.layouts.main")

@section("content")
    <div class="or-mid">
        <form class="layui-form layui-form-pane"  style="width:95%;">
            <div class="layui-form-item">
                <label class="layui-form-label">版本名</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="name" lay-verify="required" value="{{ $data->name }}" placeholder="请输入版本名">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">U盘数量</label>
                <div class="layui-input-block">
                    <input type="number" class="layui-input" name="disk_number" lay-verify="required" value="{{ $data->disk_number }}" placeholder="请输入可加密U盘数量">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">价格</label>
                <div class="layui-input-block">
                    <input type="number" class="layui-input" name="price" lay-verify="required" value="{{ $data->price }}" placeholder="请输入价格">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">额外授权价格</label>
                <div class="layui-input-block">
                    <input type="number" class="layui-input" name="extra_price" lay-verify="required" value="{{ $data->extra_price }}" placeholder="请输入额外授权价格">
                </div>
            </div>

            @crm_permission('crm.merchant.version.update')
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="edit-data" data-url="{{ route('crm.merchant.version.update', ['version' => $data->id]) }}" data-type="PATCH">立即提交</button>
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
