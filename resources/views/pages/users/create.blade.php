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
      <form id="form-admin" role="form" method="POST" action="{{ route('users.store') }}" data-confirm="Вы действительно хотите добавить пользователя?">
        {!! csrf_field() !!}

        <div class="box-body">

          {{-- Имя --}}
          <div class="form-group is-required{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name">Имя</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
          </div>

          {{-- E-mail --}}
          <div class="form-group is-required{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email">E-mail</label>
            <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}">
          </div>

          {{-- Пароль --}}
          <div class="form-group is-required{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password">Пароль</label>
            <input type="password" class="form-control" id="password" name="password">
          </div>

          {{-- Еще раз пароль --}}
          <div class="form-group is-required{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
            <label for="password_confirmation">Еще раз пароль</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
          </div>

        </div>

        {{-- Роли --}}
        @if(!is_null($roles))
          <div class="box-header with-border">
            <h3 class="box-title">Роли</h3>
          </div>

          <div class="box-body">
            <div class="row">
              <div class="form-group">

                @foreach($roles as $key => $role)
                  <div class="col-md-12">
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}">
                        {{ $role->display_name }}
                      </label>
                    </div>
                  </div>
                @endforeach

              </div>
            </div>
          </div>
        @endif

        <div class="box-footer">
          <button type="submit" class="btn btn-success" name="create_user" >Добавить</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
