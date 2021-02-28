@extends('platform::dashboard')

@section('title','Log Viewer')
@section('description',  $log->getPath() )

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
    <div class="bg-white rounded shadow-sm p-4 py-4 d-flex">
        <div class="col-lg-3 px-0 pr-1">
            {{-- Log Menu --}}
            <div class="card mb-4">
                <div class="card-header"><i class="fa fa-fw fa-flag"></i> @lang('Levels')</div>
                <div class="list-group list-group-flush log-menu">
                    @foreach($log->menu() as $levelKey => $item)
                        @if ($item['count'] === 0)
                            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center disabled">
                                <span class="level-name">
                                    @if ( $levelKey == 'emergency' )
                                        <x-orchid-icon path="bug" class="mr-1" width="1em" height="1em" />
                                    @elseif ( $levelKey == 'alert' )
                                        <x-orchid-icon path="fire" class="mr-1" width="1em" height="1em" />
                                    @elseif ( $levelKey == 'critical' )
                                        <x-orchid-icon path="help" class="mr-1" width="1em" height="1em" />
                                    @elseif ( $levelKey == 'error' )
                                        <x-orchid-icon path="umbrella" class="mr-1" width="1em" height="1em" />
                                    @elseif ( $levelKey == 'warning' )
                                        <x-orchid-icon path="target" class="mr-1" width="1em" height="1em" />
                                    @elseif ( $levelKey == 'notice' )
                                        <x-orchid-icon path="pencil" class="mr-1" width="1em" height="1em" />
                                    @elseif ( $levelKey == 'info' )
                                        <x-orchid-icon path="info" class="mr-1" width="1em" height="1em" />
                                    @elseif ( $levelKey == 'debug' )
                                        <x-orchid-icon path="minus" class="mr-1" width="1em" height="1em" />
                                    @endif
                                    {{ $item['name'] }}
                                </span>
                                <span class="badge empty">{{ $item['count'] }}</span>
                            </a>
                        @else
                            <a href="?{{ $levelKey }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center level-{{ $levelKey }}{{ $levelKey === $levelKey ? ' active' : ''}}">
                                <span class="level-name"><x-orchid-icon path="list" class="mr-1" width="1em" height="1em" /> {{ $item['name'] }}</span>
                                <span class="badge badge-level-{{ $levelKey }}">{{ $item['count'] }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-9 px-0">
            {{-- Log Details --}}
            <div class="card mb-4">
                <div class="card-header">
                    @lang('Log info') :
                    <div class="btn-group pull-right">
                        <a href="{{ route('log-viewer::logs.download', [$log->date]) }}" class="btn btn-sm btn-success">
                            <x-orchid-icon path="cloud-download" class="mr-1" width="1em" height="1em" /> Скачать
                        </a>
                        <a href="#delete-log-modal" class="btn btn-sm btn-danger" data-toggle="modal">
                            <x-orchid-icon path="trash" class="mr-1" width="1em" height="1em" /> Удалить
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-condensed mb-0">
                        <tbody>
                        <tr>
                            <td>@lang('File path') :</td>
                            <td colspan="7">{{ $log->getPath() }}</td>
                        </tr>
                        <tr>
                            <td>@lang('Log entries') :</td>
                            <td>
                                <span class="badge badge-primary p-2 rounded">{{ $entries->total() }}</span>
                            </td>
                            <td>@lang('Size') :</td>
                            <td>
                                <span class="badge badge-primary p-2 rounded">{{ $log->size() }}</span>
                            </td>
                            <td>@lang('Created at') :</td>
                            <td>
                                <span class="badge badge-primary p-2 rounded">{{ $log->createdAt() }}</span>
                            </td>
                            <td>@lang('Updated at') :</td>
                            <td>
                                <span class="badge badge-primary p-2 rounded">{{ $log->updatedAt() }}</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Log Entries --}}
            <div class="card mb-4">
                @if ($entries->hasPages())
                    <div class="card-header">
                        <span class="badge badge-info float-right">
                            {{ __('Page :current of :last', ['current' => $entries->currentPage(), 'last' => $entries->lastPage()]) }}
                        </span>
                    </div>
                @endif

                <div class="table-responsive">
                    <table id="entries" class="table mb-0">
                        <thead>
                        <tr>
                            <th>@lang('ENV')</th>
                            <th style="width: 120px;">@lang('Level')</th>
                            <th style="width: 65px;">@lang('Time')</th>
                            <th>@lang('Header')</th>
                            <th class="text-right">@lang('Actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($entries as $key => $entry)
                            <?php /** @var  Arcanedev\LogViewer\Entities\LogEntry  $entry */ ?>
                            <tr>
                                <td>
                                    <span class="badge badge-env p-2 rounded">{{ $entry->env }}</span>
                                </td>
                                <td>
                                        <span class="badge badge-level-{{ $entry->level }} p-2 rounded">
                                            {!! $entry->level() !!}
                                        </span>
                                </td>
                                <td>
                                        <span class="badge badge-secondary p-2 rounded">
                                            {{ $entry->datetime->format('H:i:s') }}
                                        </span>
                                </td>
                                <td>
                                    {{ $entry->header }}
                                </td>
                                <td class="text-right">
                                    @if ($entry->hasStack())
                                        <a class="btn btn-sm btn-light" role="button" data-toggle="collapse"
                                           href="#log-stack-{{ $key }}" aria-expanded="false" aria-controls="log-stack-{{ $key }}">
                                            <x-orchid-icon path="options-vertical" class="mr-1" />
                                        </a>
                                    @endif

                                    @if ($entry->hasContext())
                                        <a class="btn btn-sm btn-light" role="button" data-toggle="collapse"
                                           href="#log-context-{{ $key }}" aria-expanded="false" aria-controls="log-context-{{ $key }}">
                                            <x-orchid-icon path="options-vertical" class="mr-1" />
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @if ($entry->hasStack() || $entry->hasContext())
                                <tr>
                                    <td colspan="5" class="stack py-0">
                                        @if ($entry->hasStack())
                                            <div class="stack-content collapse" id="log-stack-{{ $key }}">
                                                {!! $entry->stack() !!}
                                            </div>
                                        @endif

                                        @if ($entry->hasContext())
                                            <div class="stack-content collapse" id="log-context-{{ $key }}">
                                                <pre>{{ $entry->context() }}</pre>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <span class="badge badge-secondary">@lang('The list of logs is empty!')</span>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {!! $entries->render() !!}
        </div>
    </div>
@endsection

@section('modals')
    {{-- DELETE MODAL --}}
    <div id="delete-log-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form id="delete-log-form" action="{{ route('log-viewer::logs.delete') }}" method="POST">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="date" value="{{ $log->date }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Delete log file')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>@lang('Are you sure you want to delete this log file: :date ?', ['date' => $log->date])</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary mr-auto" data-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn btn-sm btn-danger" data-loading-text="@lang('Loading')&hellip;">@lang('Delete')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            var deleteLogModal = $('div#delete-log-modal'),
                deleteLogForm  = $('form#delete-log-form'),
                submitBtn      = deleteLogForm.find('button[type=submit]');
            deleteLogForm.on('submit', function(event) {
                event.preventDefault();
                submitBtn.button('loading');
                $.ajax({
                    url:      $(this).attr('action'),
                    type:     $(this).attr('method'),
                    dataType: 'json',
                    data:     $(this).serialize(),
                    success: function(data) {
                        submitBtn.button('reset');
                        if (data.result === 'success') {
                            deleteLogModal.modal('hide');
                            location.replace("{{ route('log-viewer::logs.list') }}");
                        }
                        else {
                            alert('OOPS ! This is a lack of coffee exception !')
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        alert('AJAX ERROR ! Check the console !');
                        console.error(errorThrown);
                        submitBtn.button('reset');
                    }
                });
                return false;
            });
            @unless (empty(log_styler()->toHighlight()))
            @php
                $htmlHighlight = version_compare(PHP_VERSION, '7.4.0') >= 0
                    ? join('|', log_styler()->toHighlight())
                    : join(log_styler()->toHighlight(), '|');
            @endphp
            $('.stack-content').each(function() {
                var $this = $(this);
                var html = $this.html().trim()
                    .replace(/({!! $htmlHighlight !!})/gm, '<strong>$1</strong>');
                $this.html(html);
            });
            @endunless
        });
    </script>
@endsection
