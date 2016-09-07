@extends('layouts.app')

@section('title')
  Атлант - Редактирование роли
@endsection

@section('content-header')
<h1>Редактирование роли<small>пользователя</small></h1>
<ol class="breadcrumb">
  <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Атлант</a></li>
  <li><a href="{{ route('users.index') }}">Пользователи</a></li>
  <li><a href="{{ route('roles.index') }}">Управление ролями</a></li>
  <li>Редактирование роли</li>
</ol>
@endsection


@section('content')
<div class="btn-group margin-bottom">
  <a href="{{ route('roles.index') }}" class="btn btn-default">
    <i class="fa fa-angle-double-left"></i>
    Управление ролями
  </a>
</div>

@if(!is_null($role))

<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Редактирование роли</h3>
      </div>

        {!! Form::model($role, [
            'method' => 'PATCH',
            'action' => ['RoleController@update', $role->id],
            'id' => 'form-admin',
            'role' => 'form',
            'data-confirm' => 'Вы действительно хотите изменить роль?',
        ]) !!}
          @include('pages.roles.form', ['buttonText' => 'Сохранить', 'buttonName' => 'edit_role'])
        {!! Form::close() !!}

    </div>
  </div>
</div>
@endif

@endsection
