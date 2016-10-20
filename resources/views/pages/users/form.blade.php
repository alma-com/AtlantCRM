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

@access('change role users')
  {{-- Роли --}}
  @if(!is_null($roles))
    <div class="box-header with-border">
      <h3 class="box-title">Роли</h3>
    </div>

    <div class="box-body">
      <div class="row">
        <div class="form-group">

          @foreach($roles as $role)
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  @if($role['name'] === 'user')
                    <input type="hidden" name="roles[]" value="{{ $role['id'] }}">
                    {!! Form::checkbox('roles[]', $role['id'], 1, ['disabled' => 'disabled']) !!}{{ $role['display_name'] }}
                  @else
                    {!! Form::checkbox('roles[]',  $role['id'], (isset($user) && $user->hasRole( $role['id']) ? 1 : 0)) !!}{{ $role['display_name'] }}
                  @endif
                </label>
              </div>
            </div>
          @endforeach

        </div>
      </div>
    </div>
  @endif
@endaccess

<div class="box-footer">
  {!! Form::submit($buttonText, ['name' => $buttonName, 'class' => 'btn btn-success']) !!}
</div>
