@extends('welcome')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Авторизация</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Упс!</strong> Некоторые проблемы при авторизации.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-signin" action="{{url('auth/login')}}" method="post">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<label for="inputEmail" class="sr-only">Email</label>
						<input type="email" name="email" class="form-control" placeholder="Email address" value="{{old('email')}}" required autofocus>

						<label for="inputPassword" class="sr-only">Пароль</label>
						<input type="password" name="password" class="form-control" placeholder="Пароль" required>

						<div class="checkbox">
							<label>
								<input type="checkbox" value="remember"> Запомнить
							</label>
						</div>

						<button class="btn btn-lg btn-primary btn-block" type="submit">Войти в личный кабинет</button>
					</form>

					<p class="text-center">
						<a href="{{url('auth/register')}}">Зарегистрироваться</a>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
