<div class="table-responsive">
    <table class="table table-striped">
        <tbody>
        <?php $tasks_total_time = 0;?>
        <?php $tasks_total_price = 0;?>
        <?php $earnMoneyCurrentDate = costCalculation(NULL,['tasks' => $tasks]);?>
        @foreach($tasks as $task)
            <?php $tasks_total_time += (getLeadTimeMinutes($task)+floor($task->lead_time/60));?>
            @if(isset($earnMoneyCurrentDate[$task->id]['earnings']))
                <?php $tasks_total_price += $earnMoneyCurrentDate[$task->id]['earnings'];?>
            @endif
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
                    {{ culcLeadTime($task) }} / {{ isset($earnMoneyCurrentDate[$task->id]['earnings']) ? number_format($earnMoneyCurrentDate[$task->id]['earnings'],2,'.',' ').' руб.' : '' }}
                </td>
            </tr>
        @endforeach
        @if(isset($showTotal) && $showTotal)
        <tr>
            <td>
                Всего {{ count($tasks) }} {{ Lang::choice('задача|задачи|задач',count($tasks)) }}. <br>
                Время выполнения: {{ getLeadTimeFromMinutes($tasks_total_time) }} ч.<br>
                Общая сумма: {{ number_format($tasks_total_price,2,'.',' ') }} руб.
            </td>
            <td colspan="4"></td>
        </tr>
        @endif
        </tbody>
    </table>
</div>