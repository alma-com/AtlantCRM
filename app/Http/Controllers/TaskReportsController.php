<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Requests\TaskReportRequest;
use App\TaskReport;
use App\User;
use Alma;
use Auth;
use Validator;

class TaskReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $reports = TaskReport::where('user_id', Auth::user()->id)->get();

        return Alma::viewReturn(view('pages.task_reports.index', compact('reports')), $request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TaskReportRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskReportRequest $request)
    {
        $report = TaskReport::create($request->all());
        $report->user()->associate(Auth::user());
        $report->save();

        return Alma::successReturn('Добавлено в отчет', [
            'request' => $request,
            'url' => route('task-reports.index'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $rules = [];
        $validator = Validator::make($request->all(), $rules);
        $arrStatus = [
            'request' => $request,
            'validator' => $validator,
        ];

        $report = TaskReport::find($id);
        $report->delete();

        $arrStatus['url'] = route('task-reports.index');
        return Alma::successReturn('Отчет успешно удален', $arrStatus);
    }

    /**
     * destroy list items
     */
    public function destroyItems(Request $request)
    {
        $rules = [];
        $validator = Validator::make($request->all(), $rules);
        $arrStatus = [
            'request' => $request,
            'validator' => $validator,
        ];

        $itemArray = $request->input('item');
        if(count($itemArray) == 0){
            return Alma::infoReturn('Ничего не выбрано', $arrStatus);
        }
        $arrStatus['validator'] = $validator;

        if ($validator->fails()) {
            return Alma::failsReturn('Не удалось удалить', $arrStatus);
        }

        foreach($itemArray as $key => $id_report){
            $report = TaskReport::find($id_report);
            $report->delete();
        }

        $arrStatus['url'] = route('task-reports.index');
        return Alma::successReturn('Отчеты успешно удалены', $arrStatus);
    }
}
