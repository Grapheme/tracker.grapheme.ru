<?php

function getLeadTimeMinutes($task){

    if ($task->start_status && !$task->stop_status):
        return abs(floor(myDateTime::getDiffTimeStamp((new \Carbon\Carbon('now')),(new \Carbon\Carbon($task->start_date)))/60));
    elseif($task->start_status && $task->stop_status):
        return abs(floor(myDateTime::getDiffTimeStamp((new \Carbon\Carbon($task->stop_date)),(new \Carbon\Carbon($task->start_date)))/60));
    endif;
}

function getLeadTimeFromMinutes($sub_minutes,$lead_time = 0){

    $lead_time = floor($lead_time/60);
    $hours = floor(($sub_minutes+$lead_time)/60);
    $minutes = ($sub_minutes+$lead_time)%60;
    return $hours.':'.str_pad($minutes,2,'0',STR_PAD_LEFT);
}

function culcLeadTime($task){

    $sub_minutes = getLeadTimeMinutes($task);
    return getLeadTimeFromMinutes($sub_minutes,$task->lead_time);
}

function costCalculation($taskID = NULL, $data = NULL){

    $accountsIDs = [Auth::user()->id];
    $tasks = array();
    if (!is_null($data) && is_array($data)):
        if (isset($data['accounts']) && is_array($data['accounts'])):
            $accountsIDs = $data['accounts'];
        endif;
        if (isset($data['tasks'])):
            $tasks = $data['tasks'];
        endif;
    endif;
    if (empty($tasks)):
        $tasks = ProjectTask::WhereIn('user_id',$accountsIDs)->with('cooperator','project.client')->get();
    endif;
    $userTasksMinutes = $ProjectIDs = $hourPrices = [];
    foreach($tasks as $task):
        $ProjectIDs[$task->project_id] = $task->project_id;
    endforeach;
    if ($ProjectIDs):
        foreach(ProjectTeam::whereIn('project_id',$ProjectIDs)->select('project_id','hour_price','user_id')->get() as $team):
            $hourPrices[$team->project_id][$team->user_id] = $team->hour_price;
        endforeach;
        foreach(ProjectOwners::where('project_id',$ProjectIDs)->select('project_id','hour_price','user_id')->get() as $team):
            $hourPrices[$team->project_id][$team->user_id] = (int) $team->hour_price;
        endforeach;
    endif;
    foreach($tasks as $task):
        $hourPrice = isset($hourPrices[$task->project_id][$task->cooperator->id]) ? $hourPrices[$task->project_id][$task->cooperator->id] : 0;
        $whose_price = 'цена договорная';
        if($hourPrice == 0 && isset($task->project->client->hour_price) && $task->project->client->hour_price > 0):
            $whose_price = 'цена клиента';
            $hourPrice = $task->project->client->hour_price;
        endif;
        if($hourPrice == 0 && $task->cooperator->hour_price > 0):
            $whose_price = 'цена пользователя';
            $hourPrice = $task->cooperator->hour_price;
        endif;
        $userTasksMinutes[$task->id] = ['user_id'=>$task->user_id,'minutes'=>getLeadTimeMinutes($task)+floor($task->lead_time/60),'earnings'=>0,'whose_price'=>@$whose_price,'hour_price'=>$hourPrice];
    endforeach;
    foreach($userTasksMinutes as $task_id => $prices):
        $summaInMinute = round($prices['hour_price']/60,2);
        $userTasksMinutes[$task_id]['earnings'] = round($prices['minutes']*$summaInMinute);
    endforeach;
    if (!is_null($taskID) && isset($userTasksMinutes[$taskID])):
        return $userTasksMinutes[$taskID];
    else:
        return $userTasksMinutes;
    endif;
}

function str2secLeadTime($string){

    $lead_time = 0;
    if (!empty($string)):
        preg_match('/^(\d+):(\d+)/',$string,$matches);
        if (!$matches):
            preg_match('/^(\d+)[\.\,](\d+)/',$string,$matches);
        endif;
        if ($matches && isset($matches[1]) && isset($matches[2])):
            $lead_time = $matches[1]*3600;
            if ($matches[2] <= 59):
                $lead_time += $matches[2]*60;
            else:
                $lead_time += 59*60;
            endif;
        endif;
    endif;
    return $lead_time;
}

function getCooperatorTeam($account = null){

    if (is_null($account)):
        $account = Auth::user()->id;
    endif;
    $cooperators = array();
    $superiorIDs = Team::where('cooperator_id',$account)->lists('superior_id');
    $teams = Team::whereIn('superior_id',$superiorIDs)->with(array('cooperator'=>function($query) use ($account){
        $query->where('id','!=',$account);
    }))->get();
    if ($teams):
        foreach($teams as $team):
            if ($team->cooperator):
                $cooperators[] = $team->cooperator->toArray();
            endif;
        endforeach;
    endif;
    return $cooperators;
}

function isProjectAdministrator(){

    if (Auth::check() && Auth::user()->group->slug == 'admin-projects'):
        return TRUE;
    endif;
    return FALSE;
}