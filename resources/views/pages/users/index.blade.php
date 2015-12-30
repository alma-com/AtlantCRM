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
			
			<div class="box-body no-padding">
				<div class="mailbox-controls">
					<button type="button" class="btn btn-default btn-sm checkbox-toggle" data-name="user[]"><i class="fa fa-square-o"></i></button>
					<div class="btn-group">
						<button type="button" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></button>
						<button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
					</div>
				</div>
			</div>
		
			@if(count($users) > 25)
				<table class="data-table table table-bordered table-striped">
			@else
				<table class="data-table-small table table-bordered table-striped">
			@endif
				<thead>
				<tr>
					<th></th>
					<th>Роль</th>
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
					<td><input type="checkbox" name="user[]" value="{{ $user->id }}"></td>
					<td></td>
					<td>{{ $user->email }}</td>
					<td>{{ $user->name }}</td>
					<td> </td>
					<td></td>
					<td></td>
				</tr>
				@endforeach
				</tbody>
			</table>
			
			<div class="box-body no-padding">
				<div class="mailbox-controls">
					<button type="button" class="btn btn-default btn-sm checkbox-toggle" data-name="user[]"><i class="fa fa-square-o"></i></button>
					<div class="btn-group">
						<button type="button" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></button>
						<button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
					</div>
				</div>
			</div>
			
		@endif
	</div>
</div>


@endsection
