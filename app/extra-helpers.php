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

function costCalculation($cooperator = NULL, $data = NULL){

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
        if($projectsIDs = Project::whereIn('superior_id',$accountsIDs)->lists('id')):
            $tasks = ProjectTask::whereIn('project_id',$projectsIDs)->with('cooperator','project')->get();
        else:
            return array();
        endif;
    endif;
    $projectsIDs = $userTasksMinutes = $projectsData = [];
    foreach($tasks as $task):
        if ($task->project):
            $projectsIDs[$task->project->id] = $task->project->id;
            $userTasksMinutes[$task->project->id][$task->id] = ['user_id'=>$task->user_id,'minutes'=>getLeadTimeMinutes($task)+floor($task->lead_time/60),'earnings'=>0,'overdose'=>FALSE,'overdose_money'=>0,'work_price'=>0,'budget'=>0];
        endif;
    endforeach;
    if ($projectsIDs):
        foreach(Project::whereIn('id',$projectsIDs)->with('team','owners')->get() as $project):
            $projectsData[$project->id] = [];
            if ($project->owners):
                foreach($project->owners as $user):
                    $projectsData[$project->id][$user->id] = ['hour_price'=>$user->hour_price,'budget'=>$user->budget];
                endforeach;
            endif;
            if ($project->team):
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
        foreach($userTasksMinutes as $project_id => $task):
            foreach($task as $task_id => $prices):
                $summaInMinute = round($prices['work_price']/60,2);
                $userTasksMinutes[$project_id][$task_id]['earnings'] = round($prices['minutes']*$summaInMinute);
            endforeach;
        endforeach;
        if ($userTasksMinutes):
            foreach($userTasksMinutes as $project_id => $task):
                foreach($task as $task_id => $prices):
                    $userTaskEarnings[$project_id][$prices['user_id']] = 0;
                endforeach;
            endforeach;
            foreach($userTasksMinutes as $project_id => $task):
                foreach($task as $task_id => $prices):
                    $userTaskEarnings[$project_id][$prices['user_id']] += $prices['earnings'];
                endforeach;
            endforeach;
            foreach($userTasksMinutes as $project_id => $tasks):
                foreach($tasks as $task_id => $prices):
                    if (isset($userTaskEarnings[$project_id][$prices['user_id']])):
                        if ($prices['budget'] > 0 && $userTaskEarnings[$project_id][$prices['user_id']] > $prices['budget']):
                            $userTasksMinutes[$project_id][$task_id]['overdose'] = TRUE;
                            $userTasksMinutes[$project_id][$task_id]['overdose_money'] = $userTaskEarnings[$project_id][$prices['user_id']];
                        endif;
                    endif;
                endforeach;
            endforeach;
        endif;
    endif;

    $return = array('project_id'=> FALSE,'task_id'=> FALSE);
    if (!is_null($cooperator) && is_array($cooperator)):
        if (isset($cooperator['project_id'])):
            $return['project_id'] = $cooperator['project_id'];
        endif;
        if (isset($cooperator['task_id'])):
            $return['task_id'] = $cooperator['task_id'];
        endif;
    endif;
    if (isset($cooperator['project_id']) && isset($cooperator['task_id'])):
        if (isset($userTasksMinutes[$cooperator['project_id']][$cooperator['task_id']])):
            return $userTasksMinutes[$cooperator['project_id']][$cooperator['task_id']];
        else:
            return 'Такой задачи нет';
        endif;
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

function isPerformer(){

    if (Auth::check() && Auth::user()->group->slug == 'performer'):
        return TRUE;
    endif;
    return FALSE;
}