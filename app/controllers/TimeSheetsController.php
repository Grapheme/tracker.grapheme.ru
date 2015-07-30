<?php

class TimeSheetsController extends \BaseController {

    public function __construct() {

        date_default_timezone_set(Config::get('app.timezone'));
    }

    public function index() {

        $tasks = $weekTasks = array();
        $dt_request = Request::has('date') ? Request::get('date') : $dt_request = date('Y-m-d');
        $startOfDay = (new \Carbon\Carbon($dt_request))->hour(0)->minute(0)->second(0);
        $endOfDay = (new \Carbon\Carbon($dt_request))->hour(23)->minute(59)->second(59);
        $startOfWeek = (new \Carbon\Carbon($startOfDay))->startOfWeek()->hour(0)->minute(0)->second(0);
        $endOfWeek = (new \Carbon\Carbon($startOfDay))->endOfWeek()->hour(23)->minute(59)->second(59);
        for ($day = 0; $day < 7; $day++):
            $index = (new \Carbon\Carbon($dt_request))->startOfWeek()->AddDays($day);
            $weekTasks[$index->format('Y-m-d')] = ['label' => $index->format('d.m'), 'lead_time' => '0:00',
                'tasks_count' => 0];
        endfor;
        $tasks = ProjectTask::where('user_id', Auth::user()->id)->whereBetween('set_date', [$startOfDay,
            $endOfDay])->with('cooperator', 'project.client')->get();
        $tasksWeeek = ProjectTask::where('user_id', Auth::user()->id)->whereBetween('set_date', [$startOfWeek,
            $endOfWeek])->get();
        foreach ($tasksWeeek as $task):
            $index = (new myDateTime())->setDateString($task->set_date);
            $weekTasks[$index->format('Y-m-d')]['lead_time'] += (getLeadTimeMinutes($task) + floor($task->lead_time / 60));
            $weekTasks[$index->format('Y-m-d')]['tasks_count'] += 1;
        endforeach;
        return View::make(Helper::acclayout('timesheets.list'), compact('tasks', 'weekTasks', 'dt_request', 'startOfWeek', 'endOfWeek'));
    }

    public function create() {

        if (strtotime(Request::get('date')) > strtotime(date("Y-m-d", time()))):
            return Redirect::route('timesheets.create', ['date' => date("Y-m-d")]);
        endif;
        $projects = self::getProjects();
        return View::make(Helper::acclayout('timesheets.create'), compact('projects'));
    }

    public function store() {

        $validator = Validator::make(Input::all(), ProjectTask::$rules);
        $set_date = Input::get('set_date') ? Input::get('set_date') : date('Y-m-d');
        if ($validator->passes()):
            ProjectTask::where('user_id', Auth::user()->id)->where('start_status', 1)->where('stop_status', 0)->update(['stop_status' => 1,
                'stop_date' => date("Y-m-d H:i:s")]);
            if ($task = ProjectTask::create(['project_id' => Input::get('project'),
                'user_id' => Input::get('performer'), 'note' => Input::get('note'), 'start_status' => 1,
                'start_date' => date("Y-m-d H:i:s"), 'set_date' => $set_date,
                'lead_time' => str2secLeadTime(Input::get('lead_time'))])
            ):
                if(Input::has('redirect')):
                    return Redirect::route('dashboard')->with('message', 'Задача создана успешно.');
                else:
                    return Redirect::route('timesheets.index', ['date' => $set_date])->with('message', 'Задача создана успешно.');
                endif;
            else:
                return Redirect::back()->with('message', 'Возникла ошибка при записи в БД');
            endif;
        else:
            return Redirect::route('timesheets.create', ['date' => $set_date])->withErrors($validator)->withInput(Input::all());
        endif;
    }

    public function edit($id) {

        if ($task = ProjectTask::where('id', $id)->first()):
            $projects = self::getProjects();
            $task->lead_time = culcLeadTime($task);
            return View::make(Helper::acclayout('timesheets.edit'), compact('task', 'projects'));
        endif;
        App::abort(404);
    }

    public function update($id) {

        $set_date = Input::get('set_date') ? Input::get('set_date') : date('Y-m-d');
        $validator = Validator::make(Input::all(), ProjectTask::$update_rules);
        if ($validator->passes()):
            if ($task = ProjectTask::where('id', $id)->first()):
                $task->note = Input::get('note');
                $task->project_id = Input::get('project');
                if (Input::get('lead_time') != ''):
                    $lead_time = str2secLeadTime(Input::get('lead_time'));
                    $task->start_date = '0000-00:00 00:00:00';
                    if ($task->start_status == 1 && $task->stop_status == 0):
                        $task->start_date = date("Y-m-d H:i:s");
                    endif;
                    $task->stop_date = '0000-00:00 00:00:00';
                    $task->lead_time = $lead_time;
                    if ($task->stop_status == 1):
                        $task->start_date = date("Y-m-d H:i:s");
                        $task->stop_date = date("Y-m-d H:i:s");
                    endif;
                endif;
                $task->save();
                $task->touch();
                return Redirect::route('timesheets.index', ['date' => $set_date])->with('message', 'Задача сохранена успешно.');
            endif;
        else:
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        endif;
    }

    public function destroy($id) {

        ProjectTask::where('id', $id)->where('user_id', Auth::user()->id)->delete();
        return Redirect::back()->with('message', 'Задача удалена успешно.');
    }

    public function RunningTimer() {

        $validator = Validator::make(Input::all(), ['task' => 'integer|required', 'run' => 'integer|required']);
        if ($validator->passes()):
            if ($task = ProjectTask::where('id', Input::get('task'))->first()):
                $teamIDs = Team::where('superior_id', Auth::user()->id)->lists('cooperator_id');
                $teamIDs[] = Auth::user()->id;
                if (in_array($task->user_id, $teamIDs)):
                    if (Input::get('run') == 0):
                        $task->stop_status = 1;
                        $task->stop_date = date('Y-m-d H:i:s');
                    elseif (Input::get('run') == 1):
                        $dt = myDateTime::getDiffTimeStamp($task->stop_date, $task->start_date);
                        $task->lead_time += $dt > 60 ? $dt : 0;
                        $task->start_status = 1;
                        $task->start_date = date('Y-m-d H:i:s');
                        $task->stop_status = 0;
                        $task->stop_date = '0000-00-00 00:00:00';
                        ProjectTask::where('user_id', $task->user_id)->where('stop_status', 0)->where('id', '!=', $task->id)->update(['stop_status' => 1,
                            'stop_date' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                    endif;
                    $task->save();
                    $task->touch();
                endif;
                return Redirect::back();
            endif;
        endif;
        App::abort(404);
    }

    public static function getProjects() {

        $projects[0] = 'Без проекта';
        foreach (Project::where('superior_id', Auth::user()->id)->where('in_archive', 0)->orderBy('title')->lists('title', 'id') as $project_id => $project_title):
            $projects[$project_id] = $project_title;
        endforeach;
        foreach (ProjectTeam::where('user_id', Auth::user()->id)->groupBy('project_id')->with('project')->get() as $project_team):
            if ($project_team->project->in_archive == 0):
                $projects[$project_team->project->id] = $project_team->project->title;
            endif;
        endforeach;
        return $projects;
    }

    public function move(){

        $validator = Validator::make(Input::all(), ['project_id' => 'integer|required', 'project_move' => 'integer|required']);
        if ($validator->passes()):
            $project_id = Input::get('project_id');
            $project_move = Input::get('project_move');
            if($project_id > 0):
                ProjectTask::where('project_id', $project_id)->update(['project_id'=>$project_move]);
            endif;
            return Redirect::back();
        endif;
        App::abort(404);
    }
}