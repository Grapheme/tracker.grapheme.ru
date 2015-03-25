<?php

class ReportController extends \BaseController {

	public function __construct(){

	}

    public function index(){

        if (Clients::where('superior_id',Auth::user()->id)->exists()):
            $timeCell = self::setTimeCell();
            $reportsList = [];
            $hasReports = FALSE;
            foreach(Report::where('superior_id',Auth::user()->id)->with('user','client','project')->get() as $report):
                if (!empty($report->client) && File::exists(public_path($report->path))):
                    $reportsList[$report->id]['id'] = $report->id;
                    $reportsList[$report->id]['title'] = $report->title;
                    $reportsList[$report->id]['path'] = asset($report->path);
                    $reportsList[$report->id]['date'] = $report->created_at->format('Y-m-d');
                    $reportsList[$report->id]['date_timestamp'] = $report->created_at->timestamp;
                    $reportsList[$report->id]['client_id'] = $report->client->id;
                    $reportsList[$report->id]['client_title'] = $report->client->title;
                    if (!empty($report->user)):
                        $reportsList[$report->id]['user_id'] = $report->user->id;
                        $reportsList[$report->id]['user_fio'] = $report->user->fio;
                    else:
                        $reportsList[$report->id]['user'] = FALSE;
                    endif;
                    if (!empty($report->project) && $report->project->client_id == $report->client->id):
                        $reportsList[$report->id]['project_id'] = $report->project->id;
                        $reportsList[$report->id]['project_title'] = $report->project->title;
                    else:
                        $reportsList[$report->id]['project'] = FALSE;
                    endif;
                else:
                    if (File::exists(public_path($report->path))):
                        File::delete(public_path($report->path));
                    endif;
                    Report::where('id',$report->id)->where('superior_id',Auth::user()->id)->delete();
                endif;
            endforeach;
            if (count($reportsList)):
                foreach($timeCell as $year => $kvartals):
                    foreach($kvartals as $kvartal_title => $kvartal):
                        $timeCell[$year][$kvartal_title]['tasks'] = [];
                        foreach($reportsList as $report_id => $report):
                            if ($report['date_timestamp'] >= $kvartal['begin_timestamp'] && $report['date_timestamp'] <= $kvartal['end_timestamp'] ):
                                $timeCell[$year][$kvartal_title]['reports'][$report['client_title']][$report_id] = $report;
                                $hasReports = TRUE;
                            endif;
                        endforeach;
                    endforeach;
                endforeach;
            endif;
            return View::make(Helper::acclayout('reports.index'),compact('timeCell','hasReports'));
        else:
            return Redirect::route('dashboard')->with('message','Вы не можете управлять счетами. У Вас нет клиентов.');
        endif;
    }

    public function create(){

        $startOfDay = Input::has('begin_date') && Input::has('begin_date') != '' ? \Carbon\Carbon::createFromFormat('Y-m-d',Input::get('begin_date'))->format('Y-m-d 00:00:00') : \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00');
        $endOfDay = Input::has('end_date') && Input::has('end_date') != '' ? \Carbon\Carbon::createFromFormat('Y-m-d',Input::get('end_date'))->format('Y-m-d 00:00:00') : \Carbon\Carbon::now()->format('Y-m-d 00:00:00');
        $tasks = self::getReportTasks($startOfDay,$endOfDay);
        $clients[0] = 'Без клиента';
        $projects[0] = 'Без проекта';
        $users[0] = 'Только мои';
        foreach(Clients::where('superior_id',Auth::user()->id)->get() as $client):
            $clients[$client->id] = !empty($client->short_title) ? $client->short_title : $client->title ;
        endforeach;
        foreach(ProjectOwners::where('user_id',Auth::user()->id)->with('projects')->get() as $projectOwner):
            $projects[$projectOwner->projects->id] = $projectOwner->projects->title;
        endforeach;
        foreach(Team::where('superior_id',Auth::user()->id)->orWhere('cooperator_id',Auth::user()->id)->with('cooperator','superior')->get() as $userTeam):
            Helper::ta($userTeam);
            if ($userTeam->superior_id != Auth::user()->id):
                $users[$userTeam->superior_id] = $userTeam->superior->fio;
            endif;
            if ($userTeam->cooperator_id != Auth::user()->id):
                $users[$userTeam->cooperator_id] = $userTeam->cooperator->title;
            endif;
        endforeach;
        Helper::tad(1);
        return View::make(Helper::acclayout('reports.create'),compact('clients','projects','users','tasks','startOfDay','endOfDay'));
    }

    public function save($format = 'pdf',$action = 'D'){

        $startOfDay = Input::has('begin_date') && Input::has('begin_date') != '' ? \Carbon\Carbon::createFromFormat('Y-m-d',Input::get('begin_date'))->format('Y-m-d 00:00:00') : \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00');
        $endOfDay = Input::has('end_date') && Input::has('end_date') != '' ? \Carbon\Carbon::createFromFormat('Y-m-d',Input::get('end_date'))->format('Y-m-d 00:00:00') : \Carbon\Carbon::now()->format('Y-m-d 00:00:00');
        $tasks = self::getReportTasks($startOfDay,$endOfDay);
        switch($format):
            case 'html':
                return View::make('default',compact('tasks','startOfDay','endOfDay'));
            case 'pdf' :
                $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                $mpdf->SetDisplayMode('fullpage');
                $mpdf->WriteHTML(View::make('default',compact('tasks','startOfDay','endOfDay'))->render(), 2);
                $startOfDay = (new myDateTime())->setDateString($startOfDay)->format('dmy');
                $endOfDay = (new myDateTime())->setDateString($endOfDay)->format('dmy');
                if ($action == 'D'):
                    return $mpdf->Output("report-$startOfDay-$endOfDay.pdf", $action);
                elseif(Input::has('client') && Input::get('client') > 0):
                    if(!File::exists(Config::get('site.public_invoice_dir').'/'.Auth::user()->id)):
                        File::makeDirectory(Config::get('site.public_invoice_dir').'/'.Auth::user()->id,0777,TRUE);
                    endif;
                    $fileName = uniqid('report-')."-$startOfDay-$endOfDay.pdf";
                    $mpdf->Output(Config::get('site.public_invoice_dir').'/'.Auth::user()->id.'/'.$fileName, $action);
                    Report::create(['superior_id'=>Auth::user()->id,'user_id'=>Input::get('user'),'client_id'=>Input::get('client'),'project_id'=>Input::get('project'),'title'=>'Cчет от '.date("d.m.Y"),'path'=>Config::get('site.invoice_dir').'/'.Auth::user()->id.'/'.$fileName]);
                    return Redirect::back()->with('message',"Счет создан. Файл успешно сохранен.");
                else:
                    return Redirect::back()->with('message',"Создать счет невозможно. Не указан клиент.");
                endif;
        endswitch;
        return Redirect::back();
    }

    public function delete(){

        $validator = Validator::make(Input::all(),['report_id'=>'required']);
        if($validator->passes()):
            if (Clients::where('superior_id',Auth::user()->id)->exists()):
                if($report = Report::where('id',Input::get('report_id'))->where('superior_id',Auth::user()->id)->first()):
                    if (File::exists(public_path($report->path))):
                        File::delete(public_path($report->path));
                    endif;
                    Report::where('id',$report->id)->where('superior_id',Auth::user()->id)->delete();
                    return Redirect::back()->with('message','Счет удален.');
                else:
                    return Redirect::back()->with('message','Отсутствует запрашиваемый счет.');
                endif;
            else:
                return Redirect::route('dashboard')->with('message','Вы не можете управлять счетами. У Вас нет клиентов.');
            endif;
        else:
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        endif;
    }

    public function download(){

        $validator = Validator::make(Input::all(),['report_id'=>'required']);
        if($validator->passes()):
            if (Clients::where('superior_id',Auth::user()->id)->exists()):
                if($report = Report::where('id',Input::get('report_id'))->where('superior_id',Auth::user()->id)->first()):
                    if (File::exists(public_path($report->path))):
                        return Response::download(public_path($report->path),$report->title,['content-type'=>'application/pdf']);
                    else:
                        return Redirect::back()->with('message','Файл счета отсутствует.');
                    endif;
                else:
                    return Redirect::back()->with('message','Отсутствует запрашиваемый счет.');
                endif;
            else:
                return Redirect::route('dashboard')->with('message','Вы не можете управлять счетами. У Вас нет клиентов.');
            endif;
        else:
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        endif;
    }
    /*********************************************************************************************/
    private function getReportTasks($startOfDay,$endOfDay){

        $tasks = [];
        if (Input::has('project') && Input::get('project') > 0):
            if(ProjectOwners::where('project_id',Input::get('project'))->where('user_id',Auth::user()->id)->exists()):
                $tasks = ProjectTask::where('project_id',Input::get('project'))->where('stop_status',1)->whereBetween('set_date',[$startOfDay,$endOfDay])->with('project','project.client')->get();
            endif;
        elseif(Input::has('client') && Input::get('client') > 0):
            if($ProjectIDs = Clients::where('id',Input::get('client'))->first()->projects()->lists('id')):
                $tasks = ProjectTask::whereIn('project_id',$ProjectIDs)->where('stop_status',1)->whereBetween('set_date',[$startOfDay,$endOfDay])->with('project','project.client')->get();
            endif;
        elseif(Input::has('user') && Input::get('user') > 0):
            if (Input::get('user') == Auth::user()->id):
                $tasks = ProjectTask::where('user_id',Auth::user()->id)->where('stop_status',1)->whereBetween('set_date',[$startOfDay,$endOfDay])->with('project','project.client')->get();
            elseif(Team::where('superior_id',Input::get('user'))->where('cooperator_id',Auth::user()->id)->exists()):
                $tasks = ProjectTask::where('user_id',Input::get('user'))->where('stop_status',1)->whereBetween('set_date',[$startOfDay,$endOfDay])->with('project','project.client')->get();
            elseif(Team::where('superior_id',Auth::user()->id)->where('cooperator_id',Input::get('user'))->exists()):
                $tasks = ProjectTask::where('user_id',Input::get('user'))->where('stop_status',1)->whereBetween('set_date',[$startOfDay,$endOfDay])->with('project','project.client')->get();
            endif;
        else:
            $tasks = ProjectTask::where('user_id',Auth::user()->id)->where('stop_status',1)->whereBetween('set_date',[$startOfDay,$endOfDay])->with('project','project.client')->get();
        endif;
        return $tasks;
    }

    private function setTimeCell(){

        $timeCell = [];
        for($year=date("Y");$year>=Config::get('site.timeCell');$year--):
            $timeCell["$year год"]['4 квартал'] = [
                'begin'=>Carbon\Carbon::createFromDate($year)->day(1)->month(1)->addMonths(9)->firstOfMonth()->hour(0)->minute(0)->second(0)->format('Y-m-d'),
                'begin_timestamp'=>Carbon\Carbon::createFromDate($year)->day(1)->month(1)->addMonths(9)->firstOfMonth()->hour(0)->minute(0)->second(0)->timestamp,
                'end'=>Carbon\Carbon::createFromDate($year)->day(1)->month(1)->addMonths(11)->lastOfMonth()->hour(23)->minute(59)->second(59)->format('Y-m-d'),
                'end_timestamp'=>Carbon\Carbon::createFromDate($year)->day(1)->month(1)->addMonths(11)->lastOfMonth()->hour(23)->minute(59)->second(59)->timestamp
            ];
            $timeCell["$year год"]['3 квартал'] = [
                'begin'=>Carbon\Carbon::createFromDate($year)->day(1)->month(1)->addMonths(6)->firstOfMonth()->hour(0)->minute(0)->second(0)->format('Y-m-d'),
                'begin_timestamp'=>Carbon\Carbon::createFromDate($year)->day(1)->month(1)->addMonths(6)->firstOfMonth()->hour(0)->minute(0)->second(0)->timestamp,
                'end'=>Carbon\Carbon::createFromDate($year)->day(1)->month(1)->addMonths(8)->lastOfMonth()->hour(23)->minute(59)->second(59)->format('Y-m-d'),
                'end_timestamp'=>Carbon\Carbon::createFromDate($year)->day(1)->month(1)->addMonths(8)->lastOfMonth()->hour(23)->minute(59)->second(59)->timestamp
            ];
            $timeCell["$year год"]['2 квартал'] = [
                'begin'=>Carbon\Carbon::createFromDate($year)->day(1)->month(1)->addMonths(3)->firstOfMonth()->hour(0)->minute(0)->second(0)->format('Y-m-d'),
                'begin_timestamp'=>Carbon\Carbon::createFromDate($year)->day(1)->month(1)->addMonths(3)->firstOfMonth()->hour(0)->minute(0)->second(0)->timestamp,
                'end'=>Carbon\Carbon::createFromDate($year)->day(1)->month(1)->addMonths(5)->lastOfMonth()->hour(23)->minute(59)->second(59)->format('Y-m-d'),
                'end_timestamp'=>Carbon\Carbon::createFromDate($year)->day(1)->month(1)->addMonths(5)->lastOfMonth()->hour(23)->minute(59)->second(59)->timestamp
            ];
            $timeCell["$year год"]['1 квартал'] = [
                'begin'=>Carbon\Carbon::createFromDate($year)->day(1)->month(1)->hour(0)->minute(0)->second(0)->format('Y-m-d'),
                'begin_timestamp'=>Carbon\Carbon::createFromDate($year)->day(1)->month(1)->hour(0)->minute(0)->second(0)->timestamp,
                'end'=>Carbon\Carbon::createFromDate($year)->day(1)->month(1)->addMonths(2)->lastOfMonth()->hour(23)->minute(59)->second(59)->format('Y-m-d'),
                'end_timestamp'=>Carbon\Carbon::createFromDate($year)->day(1)->month(1)->addMonths(2)->lastOfMonth()->hour(23)->minute(59)->second(59)->timestamp
            ];
        endfor;
        return $timeCell;
    }
    /*********************************************************************************************/
}