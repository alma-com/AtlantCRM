@extends('layouts.app')

@section('title')
  Атлант - Управление ролями
@endsection

@section('content-header')
<h1>Управление ролями<small>системы</small></h1>
<ol class="breadcrumb">
  <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Атлант</a></li>
  <li><a href="{{ route('users.index') }}">Пользователи</a></li>
  <li>Управление ролями</li>
</ol>
@endsection


@section('content')
<div class="btn-group margin-bottom">
  <a href="{{ route('users.index') }}" class="btn btn-default">
    <i class="fa fa-btn fa-angle-double-left"></i>
    Список пользователей
  </a>
  <a href="{{ route('roles.create') }}" class="btn btn-info">
    <i class="fa fa-btn fa-plus"></i>
    Добавить роль
  </a>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="box">

      <div class="box-header">
        <h3 class="box-title">Список ролей</h3>
      </div>

      <div class="box-body">

        @if(count($roles) > 0)
          <form id="form-items" role="form" method="POST">
          {!! csrf_field() !!}

          @if(count($roles) > 25)
            <table class="data-table table table-bordered table-striped"  data-order='[[ 4, "asc" ]]'>
          @else
            <table class="data-table-small table table-bordered table-striped" data-order='[[ 4, "asc" ]]'>
          @endif

            <thead>
              <tr>
                <th class="no-sort">
                  <input type="checkbox" class="checkbox-toggle" name="checkbox-toggle" data-name="item[]" value="">
                </th>
                <th>Название</th>
                <th>Описание</th>
                <th>Пользователи</th>
                <th>Сортировка</th>
              </tr>
            </thead>

            <tbody>
              @foreach($roles as $role)
                <tr data-item="{{ $role->id }}">
                  <td>
                    <input type="checkbox" name="item[]" value="{{ $role->id }}">
                  </td>
                  <td>
                    <div class="td-text"><a href="{{ route('roles.edit', $role->id) }}">{{ $role->display_name }}</a></div>
                    <div class="td-input"><input type="text" class="form-control" name="display_name[{{ $role->id }}]" value="{{ $role->display_name }}"></div>
                  </td>
                  <td>
                    <div class="td-text">{{ $role->description }}</div>
                    <div class="td-input"><input type="text"  class="form-control" name="description[{{ $role->id }}]" value="{{ $role->description }}"></div>
                  </td>
                  <td>
                    {{ count($role->users) }}
                  </td>
                  <td>
                    <div class="td-text">{{ $role->sort_order }}</div>
                    <div class="td-input"><input type="text"  class="form-control" name="sort_order[{{ $role->id }}]" value="{{ $role->sort_order }}"></div>
                  </td>
                </tr>
              @endforeach
            </tbody>

            <tfoot>
              <tr>
                <td colspan="5">
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
                          onclick="actionCall(this, '{{ route('roles.destroyItems') }}', 'Вы действительно хотите удалить пользователей?')"
                        >
                          <i class="fa fa-trash-o"></i>
                        </button>
                      </div>

                      <div class="btn-group-edit">
                        <button type="button" class="btn btn-success"
                          onclick="actionCall(this, '{{ route('roles.updateItems') }}', 'Вы действительно хотите изменить данных пользователей?')"
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
        @else
          Нет ролей
        @endif
      </div>
    </div>
  </div>
</div>

@endsection
