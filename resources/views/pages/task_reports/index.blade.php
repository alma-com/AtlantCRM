@extends('layouts.app')

@section('title')
  Атлант - Отчет по задачам
@endsection

@section('content-header')
<h1>Отчет<small>по задачам</small></h1>
<ol class="breadcrumb">
  <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Атлант</a></li>
  <li>Отчет по задачам</li>
</ol>
@endsection


@section('content')
<div class="btn-group margin-bottom">
</div>

<div class="row">
  <div class="col-md-12">
    <div class="box">

      <div class="box-header">
        <h3 class="box-title">Отчет по задачам</h3>
      </div>

      <div class="box-body">

        @if(count($reports) > 0)
        <form id="form-items" role="form" method="POST">
          {!! csrf_field() !!}
          <table class="data-table-small table table-bordered table-striped" data-order='[[ 1, "asc" ]]'>
            <thead>
              <tr>
                <th class="no-sort">
                  <input type="checkbox" class="checkbox-toggle" name="checkbox-toggle" data-name="item[]" value="">
                </th>
                <th>Задача</th>
                <th>Комментарий</th>
                <th>Часы</th>
              </tr>
            </thead>

            <tbody>
              @foreach($reports as $report)
                <tr data-item="{{ $report->id }}">
                  <td>
                    <input type="checkbox" name="item[]" value="{{ $report->id }}">
                  </td>
                  <td>
                    <div class="td-text">{{ $report->name }}</div>
                    <div class="td-input"><input type="text" class="form-control" name="name[{{ $report->id }}]" value="{{ $report->name }}"></div>
                  </td>
                  <td>
                    <div class="td-text">{{ $report->comment }}</div>
                    <div class="td-input">
                      <textarea class="form-control" name="comment[{{ $report->id }}]">{{ $report->comment }}</textarea>
                    </div>
                  </td>
                  <td>
                    <div class="td-text">{{ $report->hours }}</div>
                    <div class="td-input"><input type="text" class="form-control" name="hours[{{ $report->id }}]" value="{{ $report->hours }}"></div>
                  </td>
                </tr>
              @endforeach
            </tbody>

            <tfoot>
              <tr>
                <td colspan="4">
                  <div class="box-body no-padding">
                    <div class="table-controls">
                      <input type="checkbox" class="checkbox-toggle" name="checkbox-toggle" data-name="item[]" value="">

                      <div class="btn-group">
                          <button type="button" class="btn btn-default btn-sm disabled"
                            onclick="editTable(this, 'show')"
                          >
                            <i class="fa fa-pencil"></i>
                          </button>

                          <button type="button" class="btn btn-default btn-sm disabled"
                            onclick="actionCall(this, '{{ route('task-reports.destroyItems') }}', 'Вы действительно хотите удалить отчет?')"
                          >
                            <i class="fa fa-trash-o"></i>
                          </button>
                      </div>

                      <div class="btn-group-edit">
                        <button type="button" class="btn btn-success"
                          onclick="actionCall(this, '{{ route('task-reports.updateItems') }}', 'Вы действительно хотите изменить данные отчета?')"
                        >
                          <i class="fa fa-check"></i> Сохранить
                        </button>
                        <button type="button" class="btn btn-default" onclick="editTable(this, 'hide')">Отмена</button>
                      </div>

                    </div>
                  </div>
                </td>
              </tr>
            </tfoot>
          </table>
        </form>
        @endif


        <div class="box-header">
          <h3 class="box-title">Добавление в отчет</h3>
        </div>

        {!! Form::open([
            'method' => 'POST',
            'action' => ['TaskReportsController@store'],
            'id' => 'form-admin',
            'role' => 'form',
            'data-confirm' => 'Вы действительно хотите добавить в отчет?',
        ]) !!}
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                {!! Form::label('name', 'Задача:') !!}
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                {!! Form::label('comment', 'Комментарий:') !!}
                {!! Form::textarea('comment', null, ['class' => 'form-control']) !!}
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                {!! Form::label('hours', 'Часы:') !!}
                {!! Form::text('hours', null, ['class' => 'form-control']) !!}
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                {!! Form::submit('Добавить', ['name' => 'submit_role', 'class' => 'btn btn-success']) !!}
              </div>
            </div>
          </div>
        {!! Form::close() !!}

      </div>
    </div>
  </div>
</div>

@endsection
