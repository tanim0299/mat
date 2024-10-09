<div class="row">
    <div class="table-box col-lg-6 col-12" style="border-right: 1px solid lightgray;">
        <h5>@lang('common.status_histories')</h5>
        <hr>
        <div class="table-responsive">
            <table class="table fs--1 mb-0 myTable">
                <thead>
                    <tr>
                        <th>@lang('common.date_time')</th>
                        <th>@lang('common.changed_by')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data['histories']))
                    @foreach ($data['histories'] as $h)
                    @if($h->type == 'status')
                    <tr>
                        <td>
                            {{ App\Traits\Date::getDateWithMonth('-',$h->date) }}, {{ date('h:i:s a', strtotime($h->time)) }}
                        </td>
                        <td>
                            {{ App\Helpers\AuthHelper::GetUserName($h->user_id) }}
                        </td>
                    </tr>
                    @endif
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <hr>
    </div>
    <div class="table-box col-lg-6 col-12" >
        <h5>@lang('common.edit_histories')</h5>
        <hr>
        <div class="table-responsive">
            <table class="table fs--1 mb-0 myTable">
                <thead>
                    <tr>
                        <th>@lang('common.date_time')</th>
                        <th>@lang('common.edit_by')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data['histories']))
                    @foreach ($data['histories'] as $h)
                    @if($h->type == 'update')
                    <tr>
                        <td>
                            {{ App\Traits\Date::getDateWithMonth('-',$h->date) }}, {{ date('h:i:s a', strtotime($h->time)) }}
                        </td>
                        <td>
                            {{ App\Helpers\AuthHelper::GetUserName($h->user_id) }}
                        </td>
                    </tr>
                    @endif
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <hr>
    </div>
    <div class="table-box col-lg-6 col-12" style="border-right: 1px solid lightgray;">
        <h5>@lang('common.delete_histories')</h5>
        <hr>
        <div class="table-responsive">
            <table class="table fs--1 mb-0 myTable">
                <thead>
                    <tr>
                        <th>@lang('common.date_time')</th>
                        <th>@lang('common.delete_by')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data['histories']))
                    @foreach ($data['histories'] as $h)
                    @if($h->type == 'destroy')
                    <tr>
                        <td>
                            {{ App\Traits\Date::getDateWithMonth('-',$h->date) }}, {{ date('h:i:s a', strtotime($h->time)) }}
                        </td>
                        <td>
                            {{ App\Helpers\AuthHelper::GetUserName($h->user_id) }}
                        </td>
                    </tr>
                    @endif
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <hr>
    </div>
    <div class="table-box col-lg-6 col-12">
        <h5>@lang('common.restore_histories')</h5>
        <hr>
        <div class="table-responsive">
            <table class="table fs--1 mb-0 myTable">
                <thead>
                    <tr>
                        <th>@lang('common.date_time')</th>
                        <th>@lang('common.restore_by')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data['histories']))
                    @foreach ($data['histories'] as $h)
                    @if($h->type == 'restore')
                    <tr>
                        <td>    
                            {{ App\Traits\Date::getDateWithMonth('-',$h->date) }}, {{ date('h:i:s a', strtotime($h->time)) }}
                        </td>
                        <td>
                            {{ App\Helpers\AuthHelper::GetUserName($h->user_id) }}
                        </td>
                    </tr>
                    @endif
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <hr>
    </div>
</div>
