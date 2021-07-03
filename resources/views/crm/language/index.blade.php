@extends("crm.layouts.main")

@section("content")
	<blockquote class="layui-elem-quote">
		@crm_permission('crm.language.create')
		    <a class="layui-btn layui-btn-normal add" data-url="{{ route('crm.language.create') }}">添加语言</a>
		@endcrm_permission
	</blockquote>
	<div>
	  	<table class="layui-table">
		    <thead>
				<tr>
					<th style="min-width: 50px;width: 50px;">ID</th>
					<th>标识</th>
                    <th>名称</th>
                    <th>创建时间</th>
					<th style="min-width: 230px;width: 230px;">操作</th>
				</tr>
		    </thead>
		    <tbody class="links_content">
			@foreach ($datas as $data)
				<tr>
					<td>{{ $data->id }}</td>
					<td class="role-name">{{ $data->name }}</td>
                    <td class="role-name">{{ $data->desc }}</td>
                    <td class="role-name">{{ $data->created_at }}</td>
					<td>
						@crm_permission('crm.language.edit')
						<a class="layui-btn layui-btn-xs edit"  data-url="{{ route('crm.language.edit', ['language' => $data->id]) }}">
							<i class="layui-icon">&#xe642;</i>
							编辑
						</a>
						@endcrm_permission

						@crm_permission('crm.language.destroy')
						<a data-url="{{ route('crm.language.destroy', ['language' => $data->id]) }}" data-type="DELETE" class="layui-btn layui-btn-danger layui-btn-xs del">
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
                    'title': '添加语言',
                    'content': $(this).attr('data-url'),
                    'area': ['44%', '50%']
                });
            });

            $('.edit').click(function () {
                dialog.pop({
                    'title': '编辑语言',
                    'content': $(this).attr('data-url'),
                    'area': ['44%', '50%']
                });
            });
        });
	</script>
@endsection

