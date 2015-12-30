@extends('layouts.app')

@section('content-header')
<h1>Пользователи<small>системы</small></h1>
<ol class="breadcrumb">
	<li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Атлант</a></li>
	<li>Пользователи</li>
</ol>
@endsection


@section('content')


<div class="box">
	<div class="box-body">
		@if(count($users) > 0)
			@if(count($users) > 25)
				<table class="data-table table table-bordered table-striped">
			@else
				<table class="data-table-small table table-bordered table-striped">
			@endif
				<thead>
				<tr>
					<th>ID</th>
					<th>E-mail</th>
					<th>Фамилия</th>
					<th>Имя</th>
					<th>Отчество</th>
					<th>Организация</th>
				</tr>
				</thead>
				<tbody>
				@foreach($users as $user)
				<tr>
					<td>{{ $user->id }}</td>
					<td>{{ $user->email }}</td>
					<td>{{ $user->name }}</td>
					<td> </td>
					<td></td>
					<td></td>
				</tr>
				@endforeach
				</tbody>
			</table>
		@endif
	</div>
</div>


@endsection
