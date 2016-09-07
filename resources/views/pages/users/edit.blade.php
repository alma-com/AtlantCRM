@extends('layouts.app')

@section('title')
  Атлант - Редактирование пользователя
@endsection

@section('content-header')
<h1>Редактирование пользователя<small>системы</small></h1>
<ol class="breadcrumb">
  <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Атлант</a></li>
  <li><a href="{{ route('users.index') }}">Пользователи</a></li>
  <li>Редактирование пользователя</li>
</ol>
@endsection


@section('content')
<div class="btn-group margin-bottom">
  <a href="{{ route('users.index') }}" class="btn btn-default">
    <i class="fa fa-btn fa-angle-double-left"></i>
    Список пользователей
  </a>
</div>

@if(count($user) > 0)

<div class="row">
  <div class="col-md-8">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Редактирование пользователя</h3>
      </div>

        {!! Form::model($user, [
            'method' => 'PATCH',
            'action' => ['UserController@update', $user->id],
            'id' => 'form-admin',
            'role' => 'form',
            'data-confirm' => 'Вы действительно хотите изменить пользователя?',
        ]) !!}
          @include('pages.users.form', ['buttonText' => 'Сохранить', 'buttonName' => 'edit_user'])
        {!! Form::close() !!}

    </div>
  </div>
</div>
@endif
@endsection
