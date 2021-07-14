@extends("crm.layouts.main")

@section("content")
    <blockquote class="layui-elem-quote">
        <div class="layui-row">
            <div class="layui-col-md10">
                <div class="layui-input-inline">
                    <input type="text" name="username" value="{{ $search }}" placeholder="用户名" class="layui-input">
                </div>
                <a class="layui-btn search">查询</a>
                <span class="layui-word-aux">输入用户名进行查找</span>
            </div>
            <div class="layui-col-md2">
                @crm_permission('crm.admin.create')
                <a class="layui-btn layui-btn-normal add right" data-url="{{ route('crm.admin.create') }}">添加管理员</a>
                @endcrm_permission
            </div>
        </div>
    </blockquote>
    <div class="layui-form">
        <table class="layui-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>用户名</th>
                <th>是否启用</th>
                <th>邮箱</th>
                <th>角色</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody  class="links_content">
            @foreach($admins as $admin)
            <tr>
                <td>{{ $admin->id }}</td>
                <td>{{ $admin->username }}</td>
                <td>
                    <input data-url="{{ route('crm.admin.active', ['admin' => $admin->id]) }}" data-type="PATCH" type="checkbox" name="status"
                           lay-skin="switch" lay-filter="active" lay-text="启用|禁用" @if($admin->status==1) checked @endif>
                </td>
                <td><span class="layui-elip" style="display: inline-block; width: 150px">{{ $admin->email }}</span></td>
                <td><span style="display: inline-block; width: 150px;" class="layui-elip">@foreach($admin->roles as $role) {{ $role->name }} @endforeach</span></td>
                <td>{{ $admin->created_at }}</td>
                <td>
                    @crm_permission('crm.admin.edit')
                    <a data-url="{{ route('crm.admin.edit', ['admin' => $admin->id]) }}" class="layui-btn layui-btn-xs edit">
                        <i class="layui-icon">&#xe642;</i>编辑
                    </a>
                    @endcrm_permission
                    @crm_permission('crm.admin.destroy')
                    <a data-url="{{ route('crm.admin.destroy', ['admin' => $admin->id]) }}" data-type="DELETE" class="layui-btn btn-gap10 layui-btn-danger layui-btn-xs del">
                        <i class="layui-icon">&#xe640;</i>删除
                    </a>
                    @endcrm_permission
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="text-right">
        {{ $admins->appends(['search'=>$search])->links('crm.layouts.paginate') }}
    </div>
@endsection

@section("js")
    <script type="text/javascript">
        layui.use(['form', 'ori'], function () {
            var form = layui.form,
                ori = layui.ori,
                dialog = layui.dialog,
                $ = layui.$;

            $('.search').click(function () {
                var search = $('[name="username"]').val();
                location.href = "{{ route('crm.admin.index') }}" + '?search=' + search;
            });

            form.on('switch(active)',function (data) {
                var isActive = data.elem.checked,
                    _that = $(this);
                ori.submit(_that, {}, '', function () {
                    _that.prop('checked', !isActive);
                    form.render('checkbox');
                })
                return false;
            });

            $('.del').click(function () {
                var _that = $(this);
                dialog.confirm('确认删除', function () {
                    ori.submit(_that, '', function () {
                        _that.closest('tr').remove();
                    });
                });
            });

            $('.add').click(function () {
                dialog.pop({
                    'title': '添加管理员',
                    'content': '{{ route('crm.admin.create') }}',
                    'area': ['48%', '70%']
                });
            });

            $('.edit').click(function () {
                dialog.pop({
                    'title': '编辑管理员',
                    'content': $(this).attr('data-url'),
                    'area': ['48%', '55%']
                });
            });

        });
    </script>
@endsection
