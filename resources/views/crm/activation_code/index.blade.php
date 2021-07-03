@extends("crm.layouts.main")

@section("content")
	<blockquote class="layui-elem-quote">
        <form id="searchform" class="layui-form">
            <div class="layui-row">
                <div class="layui-col-md10">
                    <label class="layui-input-inline">激活码:</label>
                    <div class="layui-input-inline">
                        <input type="text" name="code" value="{{ $search_data['code'] }}" placeholder="激活码" class="layui-input">
                    </div>

                    <label class="layui-input-inline" style="margin-left: 20px;">状态:</label>
                    <div class="layui-input-inline">
                        <select name="status" class="layui-select">
                            <option value="">全部</option>
                            <option value="0" @if ($search_data['status'] == '0')selected @endif>未激活</option>
                            <option value="1" @if ($search_data['status'] == '1')selected @endif>已激活</option>
                        </select>
                    </div>

                    <label class="layui-input-inline" style="margin-left: 20px;">批次号:</label>
                    <div class="layui-input-inline">
                        <input type="text" name="batch_no" value="{{ $search_data['batch_no'] }}" placeholder="批次号" class="layui-input">
                    </div>

                    <a class="layui-btn search">查询</a>
                </div>
                <div class="layui-col-md2">
                    @crm_permission('crm.activation_code.create')
                    <a class="layui-btn layui-btn-normal add right" data-url="{{ route('crm.activation_code.create') }}">生成激活码</a>
                    @endcrm_permission
                </div>
            </div>
        </form>
	</blockquote>
	<div>
	  	<table class="layui-table">
		    <thead>
				<tr>
					<th style="min-width: 50px;width: 50px;">ID</th>
					<th>激活码</th>
                    <th>新增USB授权数量</th>
                    <th>状态</th>
                    <th>激活时间</th>
                    <th>批次号</th>
                    <th>创建时间</th>
					<th style="min-width: 230px;width: 230px;">操作</th>
				</tr>
		    </thead>
		    <tbody class="links_content">
			@foreach ($datas as $data)
				<tr>
					<td>{{ $data->id }}</td>
					<td class="role-name">{{ $data->code }}</td>
                    <td class="role-name">{{ $data->auth_count }}</td>
                    <td class="role-name">
                        @if($data->status == 0)
                            未激活
                        @else
                            已激活
                        @endif

                    </td>
                    <td class="role-name">{{ $data->active_time??'--' }}</td>
                    <td class="role-name">

                        <a data-url="{{ route('crm.activation_code.batch_no', ['batch_no' => $data->batch_no]) }}" class="layui-btn layui-btn-xs batch_no">
                            {{ $data->batch_no }}
                        </a>

                    </td>
                    <td class="role-name">{{ $data->created_at }}</td>
					<td>
						@crm_permission('crm.activation_code.destroy')
						<a data-url="{{ route('crm.activation_code.destroy', ['activation_code' => $data->id]) }}" data-type="DELETE" class="layui-btn layui-btn-danger layui-btn-xs del">
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
                location.href = "{{ route('crm.activation_code.index') }}" + '?' + search_data;
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
                    'title': '生成激活码',
                    'content': $(this).attr('data-url'),
                    'area': ['46%', '50%']
                });
            });

            $('.batch_no').click(function () {
                dialog.pop({
                    'title': '批次号下激活码列表',
                    'content': $(this).attr('data-url'),
                    'area': ['46%', '70%']
                });
            });
        });
	</script>
@endsection

