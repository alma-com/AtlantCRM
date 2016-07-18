<div class="row">
  <div class="col-xs-8">
    @if (Session::has('error'))
      <div class="alert alert-danger alert-dismissible" >
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i> Ошибка!</h4>
        {{ Session::get('error') }}
      </div>
    @endif

    @if (Session::has('warning'))
      <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-warning"></i> Предупреждение!</h4>
        {{ Session::get('warning') }}
      </div>
    @endif

    @if (Session::has('info'))
      <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-info"></i> Информация!</h4>
        {{ Session::get('info') }}
      </div>
    @endif

    @if (Session::has('success'))
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> Успешно!</h4>
        {{ Session::get('success') }}
      </div>
    @endif
  </div>
</div>
