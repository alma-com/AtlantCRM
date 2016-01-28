@extends('layouts.app')

@section('title')
	Атлант - Пользователи системы
@endsection

@section('content-header')
<h1>Пользователи<small>системы</small></h1>
<ol class="breadcrumb">
	<li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Атлант</a></li>
	<li>Пользователи</li>
</ol>
@endsection


@section('content')
<div class="btn-group margin-bottom">
	<a href="{{ route('users.create') }}" class="btn btn-info">
		<i class="fa fa-btn fa-plus"></i>
		Добавить пользователя
	</a>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header">
              <h3 class="box-title">Список пользователей</h3>
            </div>
			<div class="box-body">
				@if(count($users) > 0)
			        <form id="form-items" role="form" method="POST">
                    {!! csrf_field() !!}
					@if(count($users) > 25)
						<table class="data-table table table-bordered table-striped"  data-order='[[ 1, "asc" ]]'>
					@else
						<table class="data-table-small table table-bordered table-striped" data-order='[[ 1, "asc" ]]'>
					@endif
						<thead>
							<tr>
								<th class="no-sort">
									<input type="checkbox" class="checkbox-toggle" name="checkbox-toggle" data-name="item[]" value="">
								</th>
								<th>ФИО</th>
								<th>E-mail</th>
								<th>Роль</th>
								<th>Организация</th>
							</tr>
						</thead>
						<tbody>
							@foreach($users as $user)
								<tr data-item="{{ $user->id }}">
									<td>
										<input type="checkbox" name="item[]" value="{{ $user->id }}">
									</td>
									<td>
										<div class="td-text"><a href="{{ route('users.edit', $user->id) }}">{{ $user->name }}</a></div>
										<div class="td-input"><input type="text" class="form-control" name="name[{{ $user->id }}]" value="{{ $user->name }}"></div>
									</td>
									<td>
										<div class="td-text">{{ $user->email }}</div>
										<div class="td-input"><input type="text"  class="form-control" name="email[{{ $user->id }}]" value="{{ $user->email }}"></div>
									</td>
									<td></td>
									<td></td>
								</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<td colspan="5">
									<div class="box-body no-padding">
										<div class="table-controls">
											<input type="checkbox" class="checkbox-toggle" name="checkbox-toggle" data-name="item[]" value="">
											<div class="btn-group">
												<button type="button" class="btn btn-default btn-sm disabled" onclick="editTable(this, 'show')"><i class="fa fa-pencil"></i></button>
                                                <button type="button" class="btn btn-default btn-sm disabled" onclick="actionCall(this, '{{ route('users.destroyAll') }}', 'Вы действительно хотите удалить пользователей?')">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>			
											</div>
												
											<div class="btn-group-edit">	
												<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Сохранить</button>
												<button type="button" class="btn btn-default" onclick="editTable(this, 'hide')">Отмена</button>
											</div>
										</div>
									</div>
								</td>
							</tr>
						</tfoot>
					</table>
				</form>
				@endif
			</div>
		</div>
	</div>
</div>

@endsection
