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

<div class="row">
  <div class="col-md-8">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Редактирование пользователя</h3>
      </div>

      @if(count($user) > 0)

        <form id="form-admin" role="form" method="POST" action="{{ route('users.update', $user->id) }}" data-confirm="Вы действительно хотите изменить пользователя?">
          {!! csrf_field() !!}
          <input name="_method" type="hidden" value="PUT">

          <div class="box-body">

            {{-- Имя --}}
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label for="name">Имя</label>
              <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
            </div>

            {{-- E-mail --}}
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email">E-mail</label>
              <input type="text" class="form-control" id="email" name="email" value="{{ $user->email }}">
            </div>

            {{-- Пароль --}}
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
              <label for="password">Пароль</label>
              <input type="password" class="form-control" id="password" name="password">
            </div>

            {{-- Еще раз пароль --}}
            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
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
                          @if($user->hasRole($role->id))
                            <input checked type="checkbox" name="roles[]" value="{{ $role->id }}">
                          @else
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}">
                          @endif
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
            <button type="submit" class="btn btn-success" name="update_user" >Сохранить</button>
          </div>
        </form>
      @endif
    </div>
  </div>
</div>
@endsection
