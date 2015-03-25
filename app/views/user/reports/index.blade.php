@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <div class="container marketing">
        <div class="row">
            <h2 class="sub-header">Список счетов</h2>
@if(count($hasReports))
            <div class="table-responsive">
    @foreach($timeCell as $year_title => $kvartals)
            <?php $reportsInYearCount = 0; ?>
        @foreach($kvartals as $kvartal_title => $kvartal)
            <?php $reportsInKvartalCount = 0; ?>
            @if(count($kvartal['reports']))
                <h3>{{ $year_title }}. {{ $kvartal_title }}</h3>
                @foreach($kvartal['reports'] as $client_title => $reports)
                    <h5>{{ $client_title }}</h5>
                <table class="table table-striped">
                    <tbody>
                    @foreach($reports as $report)
                        <?php $reportsInYearCount++;?>
                        <?php $reportsInKvartalCount++;?>
                        <tr>
                            <td>
                                {{ $report['title'] }}
                            </td>
                            <td>
                                {{ (new myDateTime())->setDateString($report['date'])->format('d.m.Y') }}
                            </td>
                            <td>
                                {{ Form::open(['route'=>['report.download'],'method'=>'post']) }}
                                    {{ Form::hidden('report_id',$report['id']) }}
                                    {{ Form::submit('Скачать',['class'=>'btn btn-link']) }}
                                {{ Form::close() }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @endforeach
                @if($reportsInKvartalCount > 0)
                <div>
                    Всего за {{ $kvartal_title }}: {{ $reportsInKvartalCount }} {{ Lang::choice('счет|счета|счетов',$reportsInKvartalCount) }}.
                </div>
                @endif
            @endif
        @endforeach
            @if($reportsInYearCount > 0)
                <div>
                    Всего за {{ $year_title }}: {{ $reportsInYearCount }} {{ Lang::choice('счет|счета|счетов',$reportsInYearCount) }}.
                </div>
            @endif
    @endforeach
            </div>
@else
            <p>Список пуст</p>
@endif
        </div>
    </div>
@stop
@section('scripts') @stop