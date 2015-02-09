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

function costCalculation($tasks){

    $projectsIDs = $userTasksMinutes = $projectsData = [];
    foreach($tasks as $task):
        if ($task->project):
            $projectsIDs[$task->project->id] = $task->project->id;
            $userTasksMinutes[$task->project->id][$task->id] = ['user_id'=>$task->user_id,'minutes'=>getLeadTimeMinutes($task)+floor($task->lead_time/60),'earnings'=>0,'overdose'=>FALSE,'work_price'=>0,'budget'=>0];
        endif;
    endforeach;
    if ($projectsIDs):
        foreach(Project::whereIn('id',$projectsIDs)->with('team')->get() as $project):
            if ($project->team):
                $projectsData[$project->id] = [];
                foreach($project->team as $user):
                    $projectsData[$project->id][$user->id] = ['hour_price'=>$user->hour_price,'budget'=>$user->budget];
                endforeach;
            endif;
        endforeach;
        foreach($projectsData as $project_id => $project):
            foreach($project as $user_id => $prices):
                if (isset($userTasksMinutes[$project_id])):
                    foreach($userTasksMinutes[$project_id] as $task_id => $task):
                        if ($task['user_id'] == $user_id):
                            $userTasksMinutes[$project_id][$task_id]['work_price'] = $prices['hour_price'];
                            $userTasksMinutes[$project_id][$task_id]['budget'] = $prices['budget'];
                        endif;
                    endforeach;
                endif;
            endforeach;
        endforeach;
    endif;
    return [$userTasksMinutes,$projectsData];
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