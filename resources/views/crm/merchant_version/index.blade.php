@extends("crm.layouts.main")

@section("content")
	<blockquote class="layui-elem-quote">
		@crm_permission('crm.merchant.version.create')
		    <a class="layui-btn layui-btn-normal add" data-url="{{ route('crm.merchant.version.create') }}">添加版本</a>
		@endcrm_permission
	</blockquote>
	<div>
	  	<table class="layui-table">
		    <thead>
				<tr>
					<th style="min-width: 50px;width: 50px;">编号</th>
					<th>版本名称</th>
                    <th>可加密U盘数量</th>
                    <th>售价</th>
                    <th>额外授权价格</th>
                    <th>创建时间</th>
                    <th>更新时间</th>
					<th style="min-width: 230px;width: 230px;">操作</th>
				</tr>
		    </thead>
		    <tbody class="links_content">
			@foreach ($datas as $data)
				<tr>
					<td>{{ $data->id }}</td>
					<td>{{ $data->title_name }}</td>
                    <td>{{ $data->disk_number }}</td>
                    <td>{{ $data->price }}</td>
                    <td>{{ $data->extra_price }}</td>
                    <td>{{ $data->created_at }}</td>
                    <td>{{ $data->updated_at }}</td>
					<td>
						@crm_permission('crm.merchant.version.edit')
						<a class="layui-btn layui-btn-xs edit"  data-url="{{ route('crm.merchant.version.edit', ['version' => $data->id]) }}">
							<i class="layui-icon">&#xe642;</i>
							编辑
						</a>
						@endcrm_permission

						@crm_permission('crm.merchant.version.rule')
						<a data-url="{{ route('crm.merchant.version.rule', ['version' => $data->id]) }}" class="layui-btn layui-btn-warm layui-btn-xs set">
							<i class="layui-icon"></i>
							配置权限
						</a>
						@endcrm_permission

						@crm_permission('crm.merchant.version.destroy')
						<a data-url="{{ route('crm.merchant.version.destroy', ['version' => $data->id]) }}" data-type="DELETE" class="layui-btn layui-btn-danger layui-btn-xs del">
							<i class="layui-icon">&#xe640;</i>
							删除
						</a>
						@endcrm_permission
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
@endsection

@section("js")
	<script type="text/javascript">
        layui.use(['form', 'ori'], function () {
            var form = layui.form,
                ori = layui.ori,
                dialog = layui.dialog,
				$ = layui.$;


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
                    'title': '添加版本',
                    'content': $(this).attr('data-url'),
                    'area': ['44%', '58%']
                });
            });

            $('.edit').click(function () {
                dialog.pop({
                    'title': '编辑版本',
                    'content': $(this).attr('data-url'),
                    'area': ['44%', '58%']
                });
            });

            $('.set').click(function () {
                dialog.pop({
					title: '配置角色权限',
					content: $(this).attr('data-url'),
					area: ['90%', '90%']
				});
			});

        });
	</script>
@endsection

