@extends('layouts.app')

@section('title')
  Атлант - Добавление пользователя
@endsection

@section('content-header')
<h1>Добавление пользователя<small>системы</small></h1>
<ol class="breadcrumb">
  <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Атлант</a></li>
  <li><a href="{{ route('users.index') }}">Пользователи</a></li>
  <li>Добавление пользователя</li>
</ol>
@endsection


@section('content')
<div class="btn-group margin-bottom">
  <a href="{{ route('users.index') }}" class="btn btn-default">
    <i class="fa fa-btn fa-angle-double-left"></i>
    Список пользователей
  </a>
</div>

<div class="row">
  <div class="col-md-8">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Добавление пользователя</h3>
      </div>

      {!! Form::open([
          'method' => 'POST',
          'action' => ['UserController@store'],
          'id' => 'form-admin',
          'role' => 'form',
          'data-confirm' => 'Вы действительно хотите добавить пользователя?',
      ]) !!}
        @include('pages.users.form', ['buttonText' => 'Добавить'])
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection
