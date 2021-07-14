@extends("crm.layouts.main")

@section("content")
    <div class="or-mid">
        <form class="layui-form layui-form-pane"  style="width:95%;">
            <div class="layui-form-item layui-form-text">
                <div class="layui-input-block">
                    <span name="remarks" class="layui-textarea text-left">
                        @foreach($datas as $data)
                            {{ $data->code }} <br>
                        @endforeach
                    </span>
                </div>
            </div>
        </form>
    </div>
@endsection
