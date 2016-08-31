<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Requests\TaskReportRequest;
use App\TaskReport;
use App\User;
use Alma;
use Auth;

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
}
