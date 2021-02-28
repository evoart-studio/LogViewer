@extends('platform::dashboard')

@section('title','Log View')
@section('description','Simple log view')

@section('content')
    <style>
        /*
         * Log Menu
         */
        .log-menu .list-group-item.disabled {
            cursor: not-allowed;
        }
        .log-menu .list-group-item.disabled .level-name {
            color: #D1D1D1;
        }
        /*
         * Log Entry
         */
        .stack-content {
            color: #AE0E0E;
            font-family: consolas, Menlo, Courier, monospace;
            white-space: pre-line;
            font-size: .8rem;
        }
        /*
         * Colors: Badge & Infobox
         */
        .badge.badge-env,
        .badge.badge-level-all,
        .badge.badge-level-emergency,
        .badge.badge-level-alert,
        .badge.badge-level-critical,
        .badge.badge-level-error,
        .badge.badge-level-warning,
        .badge.badge-level-notice,
        .badge.badge-level-info,
        .badge.badge-level-debug,
        .badge.empty {
            color: #FFF;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
        }
        .badge.badge-level-all,
        .box.level-all {
            background-color: {{ log_styler()->color('all') }};
        }
        .badge.badge-level-emergency,
        .box.level-emergency {
            background-color: {{ log_styler()->color('emergency') }};
        }
        .badge.badge-level-alert,
        .box.level-alert  {
            background-color: {{ log_styler()->color('alert') }};
        }
        .badge.badge-level-critical,
        .box.level-critical {
            background-color: {{ log_styler()->color('critical') }};
        }
        .badge.badge-level-error,
        .box.level-error {
            background-color: {{ log_styler()->color('error') }};
        }
        .badge.badge-level-warning,
        .box.level-warning {
            background-color: {{ log_styler()->color('warning') }};
        }
        .badge.badge-level-notice,
        .box.level-notice {
            background-color: {{ log_styler()->color('notice') }};
        }
        .badge.badge-level-info,
        .box.level-info {
            background-color: {{ log_styler()->color('info') }};
        }
        .badge.badge-level-debug,
        .box.level-debug {
            background-color: {{ log_styler()->color('debug') }};
        }
        .badge.empty,
        .box.empty {
            background-color: {{ log_styler()->color('empty') }};
        }
        .badge.badge-env {
            background-color: #6A1B9A;
        }
        #entries {
            overflow-wrap: anywhere;
        }
    </style>
    <!-- main content  -->
    <section class="mb-md-4">
        <div class="bg-white rounded shadow-sm p-4 py-4 d-flex flex-column">


            <div class="panel">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                        <tr>
                            @foreach($headers as $key => $header)
                                <th scope="col" class="px-1 py-2 {{ $key == 'date' ? 'text-left' : 'text-center' }}">
                                    @if ($key == 'date')
                                        <span class="badge badge-info p-2 rounded"><x-orchid-icon path="calendar" class="mr-1 text-white" width="1.4em" height="1.4em" /> {{ $header }}</span>
                                    @else
                                        <span class="badge badge-level-{{ $key }} p-2 rounded">
                                            @if ( $key == 'all' )
                                                <x-orchid-icon path="list" class="mr-1 text-white" width="1.4em" height="1.4em" />
                                            @elseif ( $key == 'emergency' )
                                                <x-orchid-icon path="bug" class="mr-1" width="1.4em" height="1.4em" />
                                            @elseif ( $key == 'alert' )
                                                <x-orchid-icon path="fire" class="mr-1" width="1.4em" height="1.4em" />
                                            @elseif ( $key == 'critical' )
                                                <x-orchid-icon path="help" class="mr-1" width="1.4em" height="1.4em" />
                                            @elseif ( $key == 'error' )
                                                <x-orchid-icon path="umbrella" class="mr-1" width="1.4em" height="1.4em" />
                                            @elseif ( $key == 'warning' )
                                                <x-orchid-icon path="target" class="mr-1" width="1.4em" height="1.4em" />
                                            @elseif ( $key == 'notice' )
                                                <x-orchid-icon path="pencil" class="mr-1" width="1.4em" height="1.4em" />
                                            @elseif ( $key == 'info' )
                                                <x-orchid-icon path="info" class="mr-1" width="1.4em" height="1.4em" />
                                            @elseif ( $key == 'debug' )
                                                <x-orchid-icon path="minus" class="mr-1" width="1.4em" height="1.4em" />
                                            @endif
                                                {{$header}}
                                        </span>
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @if ($rows->count() > 0)
                            @foreach($rows as $date => $row)
                                <tr>
                                    @foreach($row as $key => $value)
                                        <td class="{{ $key == 'date' ? 'text-left' : 'text-center' }}">
                                            @if ($key == 'date')
                                                <span class="badge badge-primary p-2 rounded">{{ $value }}</span>
                                            @elseif ($value == 0)
                                                <span class="badge empty p-2 rounded">{{ $value }}</span>
                                            @else
                                                <a href="{{ route('dashboard.systems.logs.show', [$date, $key])}}">
                                                    <span class="badge badge-level-{{ $key }} p-2 rounded">{{ $value }}</span>
                                                </a>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="11" class="text-center">
                                    <span class="label label-default">empty-logs</span>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>


                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-sm-8">
                            <small class="text-muted inline m-t-sm m-b-sm">{{trans('dashboard::common.show')}} {{$rows->total()}}
                                -{{$rows->perPage()}} {{trans('dashboard::common.of')}} {!! $rows->count() !!} {{trans('dashboard::common.elements')}}</small>
                        </div>
                        <div class="col-sm-4 text-right text-center-xs">
                            {!! $rows->render() !!}
                        </div>
                    </div>
                </footer>
            </div>


        </div>
    </section>
    <!-- / main content  -->
@endsection

