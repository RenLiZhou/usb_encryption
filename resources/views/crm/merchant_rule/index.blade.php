@extends("crm.layouts.main")

@section("css")
	<link rel="stylesheet" href="{{ asset('extra/treegrid/css/jquery.treegrid.css') }}">
@endsection

@section("content")
	<blockquote class="layui-elem-quote">
		@crm_permission('crm.merchant.rule.create')
		<a class="layui-btn layui-btn-normal add" data-url="{{ route('crm.merchant.rule.create') }}">添加权限</a> 该处名称使用多语言，请确保标识存在
		@endcrm_permission
	</blockquote>
	<div class="layui-form">
	  	<table class="layui-table tree">
		    <thead>
				<tr>
					<th style="min-width: 50px;">ID</th>
                    <th style="min-width: 50px;">图标</th>
					<th style="min-width: 100px;">权限名称</th>
                    <th style="min-width: 100px;">权限标识</th>
					<th style="min-width: 150px;">链接</th>
					<th style="min-width: 100px;">是否验证权限</th>
					<th style="min-width: 100px;">排序</th>
					<th style="min-width: 130px;">操作</th>
				</tr>
		    </thead>
		    <tbody class="links_content">
			@foreach ($rules as $rule)
				<tr class="treegrid-{{$rule['id']}} @if($rule['pid']!=0) treegrid-parent-{{$rule['pid']}} @endif">
					<td>{{ $rule['id'] }}</td>
                    <td style="text-align:left;">{{ $rule['icon'] }}</td>
					<td style="text-align:left;">{{ $rule['ltitle'] }}</td>
                    <td style="text-align:left;">{{ $rule['title'] }}</td>
					<td style="text-align:left;">{{ $rule['href'] }}</td>
					<td>
						<input data-url="{{ route('crm.merchant.rule.update', ['rule' => $rule['id']]) }}" data-type="PATCH" type="checkbox" lay-skin="switch" lay-text="是|否" lay-filter="isCheck" @if ($rule['check'] == 1) checked @endif>
					</td>
					<td>
						<input data-url="{{ route('crm.merchant.rule.update', ['rule' => $rule['id']]) }}" data-type="PATCH" name="sort" type="text" class="layui-input content-input" value="{{$rule['sort']}}" />
					</td>
					<td>
						@crm_permission('crm.merchant.rule.edit')
						<a data-url="{{ route('crm.merchant.rule.edit', ['rule' => $rule['id']]) }}" class="layui-btn layui-btn-xs edit">
							<i class="layui-icon">&#xe642;</i>
							编辑
						</a>
						@endcrm_permission
						@crm_permission('crm.merchant.rule.destroy')
						<a data-url="{{ route('crm.merchant.rule.destroy', ['rule' => $rule['id']]) }}" data-type="DELETE" class="layui-btn layui-btn-danger layui-btn-xs del">
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
	<script type="text/javascript" src="{{ asset('extra/jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('extra/treegrid/js/jquery.treegrid.js') }}"></script>
	<script type="text/javascript">
        layui.use(['form', 'ori'], function () {
            var form = layui.form,
                ori = layui.ori,
                dialog = layui.dialog;

			$('.tree').treegrid({});//全部展开

            form.on('switch(isCheck)',function (data) {
                var isCheck = data.elem.checked, val, _that = $(this);
                val = (isCheck === true) ? 1 : 0;
                ori.submit(_that, {check: val}, '', function () {
                    _that.prop('checked', !isCheck);
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
                    'title': '添加权限',
                    'content': $(this).attr('data-url'),
                    'area': ['44%', '60%']
                });
            });

            $('.edit').click(function () {
                dialog.pop({
                    'title': '编辑权限',
                    'content': $(this).attr('data-url'),
                    'area': ['44%', '60%']
                });
            });

            $('[name="sort"]').click(function () {
                var _that = $(this);
                var val = _that.val();
                dialog.prompt('修改排序', val, function (sort) {
                    if (parseFloat(sort).toString() == "NaN") {
                        dialog.erMsg('请输入数字');
                        return false;
					}
                    ori.submit(_that, {sort: sort}, function () {
                        _that.val(sort);
					});
				});
			});

        });
	</script>
@endsection

