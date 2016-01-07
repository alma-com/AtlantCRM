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
	@include('pages.users.create_ajax')
</div>
@endsection
