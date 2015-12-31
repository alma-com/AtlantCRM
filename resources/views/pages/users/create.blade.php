@extends('layouts.app')

@section('content-header')
<h1>Пользователи<small>системы</small></h1>
<ol class="breadcrumb">
	<li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Атлант</a></li>
	<li><a href="{{ route('users.index') }}">Пользователи</a></li>
	<li>Добавление пользователя</li>
</ol>
@endsection


@section('content')


<div class="row">
	<div class="col-md-5">
	
		<div class="box box-primary">
            <div class="box-header with-border">
				<h3 class="box-title">Добавление пользователя</h3>
            </div>


			<form id="form-admin" role="form" method="POST" action="{{ route('users.store') }}">
				{!! csrf_field() !!}
			
				<div class="box-body">
					
					{{-- Роль --}}
					<div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
						<label for="role">Роль</label>
						<input type="text" class="form-control" id="role" name="role" value="{{ old('role') }}">

						@if ($errors->has('role'))
							<span class="help-block"><strong>{{ $errors->first('role') }}</strong></span>
						@endif
					</div>
					
					{{-- Фамилия --}}
					<div class="form-group{{ $errors->has('family') ? ' has-error' : '' }}">
						<label for="family">Фамилия</label>
						<input type="text" class="form-control" id="family" name="family" value="{{ old('family') }}">

						@if ($errors->has('family'))
							<span class="help-block"><strong>{{ $errors->first('family') }}</strong></span>
						@endif
					</div>
					
					{{-- Имя --}}
					<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
						<label for="name">Имя</label>
						<input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">

						@if ($errors->has('name'))
							<span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
						@endif
					</div>
					
					{{-- Отчество --}}
					<div class="form-group{{ $errors->has('middle_name') ? ' has-error' : '' }}">
						<label for="middle_name">Отчество</label>
						<input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ old('middle_name') }}">

						@if ($errors->has('middle_name'))
							<span class="help-block"><strong>{{ $errors->first('middle_name') }}</strong></span>
						@endif
					</div>
					
					{{-- E-mail --}}
					<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
						<label for="email">E-mail</label>
						<input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}">

						@if ($errors->has('email'))
							<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
						@endif
					</div>
					
					{{-- Организация --}}
					<div class="form-group{{ $errors->has('company') ? ' has-error' : '' }}">
						<label for="company">Организация</label>
						<input type="text" class="form-control" id="company" name="company" value="{{ old('company') }}">

						@if ($errors->has('company'))
							<span class="help-block"><strong>{{ $errors->first('company') }}</strong></span>
						@endif
					</div>
					
					{{-- Пароль --}}
					<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
						<label for="password">Пароль</label>
						<input type="password" class="form-control" id="password" name="password">

						@if ($errors->has('password'))
							<span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
						@endif
					</div>
					
					{{-- Еще раз пароль --}}
					<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
						<label for="password_confirmation">Еще раз пароль</label>
						<input type="password" class="form-control" id="password_confirmation" name="password_confirmation">

						@if ($errors->has('password_confirmation'))
							<span class="help-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
						@endif
					</div>
					
				</div>
				


				<div class="box-footer">
					<button type="submit" class="btn btn-primary">Добавить</button>
				</div>
			</form>
		</div>
		  
	</div>
</div>


@endsection
