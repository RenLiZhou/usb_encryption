@extends("crm.layouts.main")

@section("content")
    <blockquote class="layui-elem-quote">
        <form id="searchform" class="layui-form">
            <div class="layui-row">
                <div class="layui-col-md10">
                    <div class="layui-input-inline">
                        <input type="text" name="search" value="{{ $search_data['search'] }}" placeholder="商户名" class="layui-input">
                    </div>
                    <a class="layui-btn search">查询</a>
                </div>
                <div class="layui-col-md2">
                    @crm_permission('crm.merchant.create')
                    <a class="layui-btn layui-btn-normal add right" data-url="{{ route('crm.merchant.create') }}">添加商户</a>
                    @endcrm_permission
                </div>
            </div>
        </form>
    </blockquote>
    <div class="layui-form">
        <table class="layui-table">
            <thead>
                <th>编号</th>
                <th>商户名称</th>
                <th>登录名</th>
                <th>邮箱</th>
                <th>购买版本</th>
                <th>有效期</th>
                <th>总授权数量</th>
                <th>已授权数量</th>
                <th>额外授权数量</th>
                <th>状态</th>
                <th>备注</th>
                <th>创建时间</th>
                <th>更新时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody  class="links_content">
            @foreach($datas as $data)
            <tr>
                <td>{{ $data->id }}</td>
                <td>{{ $data->name }}</td>
                <td>{{ $data->username }}</td>
                <td>{{ $data->email }}</td>
                <td>{{ !empty($data->version[0]) ? $data->version[0]->title_name : '' }}</td>
                <td>{{ $data->expire_date }}</td>
                <td>{{ empty($data->version[0]) ? 0 : $data->version[0]->disk_number+$data->add_auth_count }}</td>
                <td>{{ $data->auth_number }}</td>
                <td>{{ $data->add_auth_count }}</td>
                <td>
                    <input data-url="{{ route('crm.merchant.active', ['merchant' => $data->id]) }}" data-type="PUT" type="checkbox" name="status"
                           lay-skin="switch" lay-filter="active" lay-text="启用|禁用" @if($data->status==1) checked @endif>
                </td>
                <td>{{ $data->remarks }}</td>
                <td>{{ $data->created_at }}</td>
                <td>{{ $data->updated_at }}</td>
                <td>
                    @crm_permission('crm.merchant.edit')
                    <a data-url="{{ route('crm.merchant.edit', ['merchant' => $data->id]) }}" class="layui-btn layui-btn-xs edit">
                        <i class="layui-icon">&#xe642;</i>编辑
                    </a>
                    @endcrm_permission

                    @crm_permission('crm.merchant.destroy')
                    <a data-url="{{ route('crm.merchant.destroy', ['merchant' => $data->id]) }}" data-type="DELETE" class="layui-btn btn-gap10 layui-btn-danger layui-btn-xs del">
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
        {{ $datas->appends($search_data)->links('crm.layouts.paginate') }}
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
                var search_data = $('#searchform').serialize();
                location.href = "{{ route('crm.merchant.index') }}" + '?' + search_data;
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
                dialog.open('添加商户', $(this).attr('data-url'));
            });

            $('.edit').click(function () {
                dialog.open('编辑商户', $(this).attr('data-url'));
            });

        });
    </script>
@endsection
