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
				<table class="data-table table table-bordered table-striped"  data-order='[[ 1, "asc" ]]'>
			@else
			{{-- <table class="data-table-small table table-bordered table-striped" data-order='[[ 1, "asc" ]]'> --}}
				<table class="data-table table table-bordered table-striped"  data-order='[[ 1, "asc" ]]'>
			@endif
				<thead>
					<tr>
						<th class="no-sort">
							<input type="checkbox" class="checkbox-toggle" name="checkbox-toggle" data-name="user[]" value="">
						</th>
						<th>ФИО</th>
						<th>E-mail</th>
						<th>Роль</th>
					</tr>
				</thead>
				<tbody>
					@foreach($users as $user)
						<tr>
							<td><input type="checkbox" name="user[]" value="{{ $user->id }}"></td>
							<td><a href="{{ route('users.edit', $user->id) }}">{{ $user->name }}</a></td>
							<td>{{ $user->email }}</td>
							<td></td>
						</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4">							
							<div class="box-body no-padding">
								<div>
									<input type="checkbox" class="checkbox-toggle" name="checkbox-toggle" data-name="user[]" value="">
									<div class="btn-group">
										<button type="button" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></button>
										<button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
									</div>
								</div>
							</div>
						</td>
					</tr>
				</tfoot>
			</table>
			
		@endif
	</div>
</div>


@endsection
