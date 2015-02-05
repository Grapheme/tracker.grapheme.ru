<?php

function getLeadTimeMinutes($task){

    if ($task->start_status && !$task->stop_status):
        return floor(myDateTime::getDiffTimeStamp((new \Carbon\Carbon('now')),(new \Carbon\Carbon($task->start_date)))/60);
    elseif($task->start_status && $task->stop_status):
        return floor(myDateTime::getDiffTimeStamp((new \Carbon\Carbon($task->stop_date)),(new \Carbon\Carbon($task->start_date)))/60);
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

function str2secLeadTime($string){

    $lead_time = 0;
    if (!empty($string)):
        preg_match('/^(\d):(\d+)/',$string,$matches);
        if (!$matches):
            preg_match('/^(\d)[\.\,](\d+)/',$string,$matches);
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

function isPerformer(){

    if (Auth::check() && Auth::user()->group->slug == 'performer'):
        return TRUE;
    endif;
    return FALSE;
}