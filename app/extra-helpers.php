<?php

function culcLeadTime($task){

    if ($task->start_status && !$task->stop_status):
        $sub_seconds = myDateTime::getDiffTimeStamp((new \Carbon\Carbon('now')),(new \Carbon\Carbon($task->start_date)));
    elseif($task->start_status && $task->stop_status):
        $sub_seconds = myDateTime::getDiffTimeStamp((new \Carbon\Carbon($task->stop_date)),(new \Carbon\Carbon($task->start_date)));
    endif;
    $hours = floor(($sub_seconds+$task->lead_time)/3600);
    $seconds = ($sub_seconds+$task->lead_time)%3600;
    return $hours.':'.str_pad(floor($seconds/60),2,'0',STR_PAD_LEFT);
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

function isProjectAdministrator(){

    if (Auth::check() && Auth::user()->group->slug == 'admin-projects'):
        return TRUE;
    endif;
    return FALSE;
}