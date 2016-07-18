{!! Form::hidden('id') !!}

<div class="box-body">

  <div class="form-group">
    {!! Form::label('name', 'Имя:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
  </div>

  <div class="form-group">
    {!! Form::label('email', 'E-mail:') !!}
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
  </div>

  <div class="form-group">
    {!! Form::label('password', 'Пароль:') !!}
    {!! Form::password('password', ['class' => 'form-control']) !!}
  </div>

  <div class="form-group">
    {!! Form::label('password_confirmation', 'Еще раз пароль:') !!}
    {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
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

        @foreach($roles as $id => $name)
          <div class="col-md-12">
            <div class="checkbox">
              <label>{!! Form::checkbox('roles[]', $id, (isset($user) && $user->hasRole($id) ? 1 : 0)) !!}{{ $name }}</label>
            </div>
          </div>
        @endforeach

      </div>
    </div>
  </div>
@endif

<div class="box-footer">
  {!! Form::submit($buttonText, ['name' => 'create_user', 'class' => 'btn btn-success']) !!}
</div>
