<div class="table-responsive">
    <table class="table table-striped">
        <tbody>
        <?php $tasks_total_time = 0;?>
        <?php $tasks_total_price = FALSE;?>
        <?php $earnMoneyCurrentDate = costCalculation(NULL,['tasks' => $tasks]);?>
        @foreach($tasks as $task)
            <?php
                $tasks_total_time += (getLeadTimeMinutes($task)+floor($task->lead_time/60));
                $showMoney = FALSE;
                if(isset($task->project->superior_id) && $task->project->superior_id == Auth::user()->id):
                    $showMoney = TRUE;
                elseif(!$task->project_id):
                    $showMoney = TRUE;
                endif;
            ?>
            <tr>
                <td>
                    {{ Carbon\Carbon::createFromFormat('Y-m-d 00:00:00',$task->set_date)->format('d.m.Y') }}
                </td>
                <td>
                    {{ $task->note }}
                    <br>{{ getInitials($task->cooperator->fio) }}
                </td>
                <td>
                @if(isset($task->project) && !empty($task->project))
                    {{ $task->project->title }}
                @endif
                @if(isset($task->project->client) && !empty($task->project->client))
                    ({{ !empty($task->project->client->short_title) ? $task->project->client->short_title : $task->project->client->title }})
                @endif
                </td>
                <td>
                    {{ culcLeadTime($task) }}
                @if($showMoney)
                    @if(isset($earnMoneyCurrentDate[$task->id]['earnings']))
                        <?php $tasks_total_price += $earnMoneyCurrentDate[$task->id]['earnings'];?>
                    @endif
                    / {{ isset($earnMoneyCurrentDate[$task->id]['earnings']) ? number_format($earnMoneyCurrentDate[$task->id]['earnings'],2,'.',' ').' руб.' : '' }}
                    @if($earnMoneyCurrentDate[$task->id]['whose_price'])<br><span class="label label-info">{{ @$earnMoneyCurrentDate[$task->id]['whose_price'] }}</span>@endif
                @endif
                </td>
            </tr>
        @endforeach
        @if(isset($showTotal) && $showTotal)
        <tr>
            <td>
                <nobr>Всего {{ count($tasks) }} {{ Lang::choice('задача|задачи|задач',count($tasks)) }}. </nobr><br>
                <nobr>Время выполнения: {{ getLeadTimeFromMinutes($tasks_total_time) }} ч.</nobr><br>
                @if($tasks_total_price !== FALSE)
                <nobr>Общая сумма: {{ number_format($tasks_total_price,2,'.',' ') }} руб.</nobr>
                @endif
            </td>
            <td colspan="4"></td>
        </tr>
        @endif
        </tbody>
    </table>
</div>