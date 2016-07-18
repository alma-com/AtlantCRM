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
      <form id="form-admin" role="form" method="POST" action="{{ route('roles.store') }}" data-confirm="Вы действительно хотите добавить роль?">
        {!! csrf_field() !!}

        <div class="box-body">
          <div class="row">

            <div class="col-md-6">
              {{-- Название --}}
              <div class="form-group is-required{{ $errors->has('display_name') ? ' has-error' : '' }}">
                <label for="role">Название</label>
                <input type="text" class="form-control" id="display_name" name="display_name" value="{{ old('display_name') }}">
              </div>
            </div>

            <div class="col-md-6">
              {{-- Уникальный код --}}
              <div class="form-group is-required{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="role">Уникальный код</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
              </div>
            </div>

            <div class="col-md-12">
              {{-- Описание --}}
              <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                <label for="family">Описание</label>
                <input type="text" class="form-control" id="description" name="description" value="{{ old('description') }}">
              </div>
            </div>

          </div>
        </div>

        @if(!is_null($groups))
          @foreach($groups as $key => $group)

          <div class="box-header with-border">
            <h3 class="box-title">{{ $group->display_name }}</h3>
          </div>

          <div class="box-body">
            <div class="row">
              <div class="form-group">

                @foreach($group->permissions as $key => $permission)
                  <div class="col-md-4">
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}">
                        {{ $permission->display_name }}
                      </label>
                    </div>
                  </div>
                @endforeach

              </div>
            </div>
          </div>

          @endforeach
        @endif

        <div class="box-footer">
          <button type="submit" class="btn btn-success" name="create_user" >Добавить</button>
        </div>
      </form>
    </div>
  </div>



</div>
@endsection
