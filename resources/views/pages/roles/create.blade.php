@extends('layouts.app')

@section('title')
  Атлант - Добавление роли
@endsection

@section('content-header')
<h1>Добавление роли<small>пользователя</small></h1>
<ol class="breadcrumb">
  <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Атлант</a></li>
  <li><a href="{{ route('users.index') }}">Пользователи</a></li>
  <li><a href="{{ route('roles.index') }}">Управление ролями</a></li>
  <li>Добавление роли</li>
</ol>
@endsection


@section('content')
<div class="btn-group margin-bottom">
  <a href="{{ route('roles.index') }}" class="btn btn-default">
    <i class="fa fa-angle-double-left"></i>
    Управление ролями
  </a>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Добавление роли</h3>
      </div>
      {!! Form::open([
          'method' => 'POST',
          'action' => ['RoleController@store'],
          'id' => 'form-admin',
          'role' => 'form',
          'data-confirm' => 'Вы действительно хотите добавить роль?',
      ]) !!}
        @include('pages.roles.form', ['buttonText' => 'Добавить'])
      {!! Form::close() !!}

    </div>
  </div>



</div>
@endsection
