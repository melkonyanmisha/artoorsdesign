<div class="">
    <!-- table-responsive -->
    <table class="table Crm_table_active3">
        <thead>
        <tr>
            <th scope="col" width="10%">{{ __('common.sl') }}</th>
            <th scope="col" width="20%">{{ __('common.title') }}</th>
            <th scope="col" width="20%">Json</th>
            <th scope="col" width="15%">{{ __('common.status') }}</th>
            <th scope="col" width="15%">{{ __('common.action') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($pageList as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->title }}</td>
                <td>{{ $item->json }}</td>
                <td>
                    <label class="switch_toggle" for="checkbox{{ $item->id }}">
                        <input type="checkbox" id="checkbox{{ $item->id }}" {{$item->status?'checked':''}} value="{{$item->id}}" data-value="{{$item}}" class="statusChange">
                        <div class="slider round"></div>
                    </label>
                </td>
                <td>
                    <!-- shortby  -->
                    <div class="dropdown CRM_dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ __('common.select') }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">
                            <a href="{{ route('scheme-markup.edit', $item->id) }}" class="dropdown-item edit_brand">{{ __('common.edit') }}</a>
                            <a href="{{ route('scheme-markup.delete')}}" class="dropdown-item delete_page" data-id="{{$item->id}}">{{ __('common.delete') }}</a>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
