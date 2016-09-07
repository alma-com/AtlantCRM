{!! Form::hidden('id') !!}

  <div class="box-body">
    <div class="row">

      <div class="col-md-6">
        <div class="form-group">
          {!! Form::label('display_name', 'Название:') !!}
          {!! Form::text('display_name', null, ['class' => 'form-control']) !!}
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group">
          {!! Form::label('name', 'Уникальный код:') !!}
          {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
      </div>

      <div class="col-md-12">
        <div class="form-group">
          {!! Form::label('description', 'Описание:') !!}
          {!! Form::text('description', null, ['class' => 'form-control']) !!}
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
                  {!! Form::checkbox(
                    'permissions[]',
                    $permission->id,
                    (isset($role) && $role->access($permission->id) ? 1 : 0))
                  !!}
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
    {!! Form::submit($buttonText, ['name' => $buttonName, 'class' => 'btn btn-success']) !!}
  </div>
</form>
