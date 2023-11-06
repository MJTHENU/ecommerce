@extends('layouts.frontend')

@section('title', __('Register'))
@php $gtext = gtext(); @endphp

@section('meta-content')
<meta name="keywords" content="{{ $gtext['og_keywords'] }}" />
<meta name="description" content="{{
	$gtext['og_description'] }}" /> <meta property="og:title" content="{{ $gtext['og_title'] }}" />
<meta property="og:site_name" content="{{ $gtext['site_name'] }}" /> <meta property="og:description" content="{{
	$gtext['og_description'] }}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ url()->current() }}" /> <meta property="og:image" content="{{
	asset('public/media/'.$gtext['og_image']) }}" /> <meta property="og:image:width" content="600" /> <meta
	property="og:image:height" content="315" /> @if($gtext['fb_publish']==1)
<meta name="fb:app_id" property="fb:app_id" content="{{ $gtext['fb_app_id'] }}" /> @endif <meta name="twitter:card"
	content="summary_large_image"> @if($gtext['twitter_publish']==1) <meta name="twitter:site" content="{{
	$gtext['twitter_id'] }}"> <meta name="twitter:creator" content="{{ $gtext['twitter_id'] }}"> @endif <meta
	name="twitter:url" content="{{ url()->current() }}"> <meta name="twitter:title" content="{{ $gtext['og_title'] }}">
<meta name="twitter:description" content="{{ $gtext['og_description'] }}"> <meta name="twitter:image" content="{{
	asset('public/media/'.$gtext['og_image']) }}"> @endsection @section('header') @include('frontend.partials.header')
	@endsection @section('content') <main class="main"> <!-- Page Breadcrumb --> <div class="breadcrumb-section">
<div class="container"> <div class="row align-items-center"> <div class="col-lg-6"> <nav aria-label="breadcrumb"> <ol
	class="breadcrumb">
	<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li> <li class="breadcrumb-item active"
		aria-current="page">{{ __('Register') }}</li> </ol>
		</nav> </div> <div class="col-lg-6"> <div class="page-title"> <h1>{{ __('Register') }}</h1> </div>
		</div> </div> </div> </div> <!-- /Page Breadcrumb/ -->

		<!-- Inner Section -->
<section class="inner-section inner-section-bg"> <div class="container"> <div class="row">
	<div class="col-lg-12"> <div class="row mt10 mb5"> <div class="col-md-12 text-center">
		<a href="{{ route('frontend.register') }}" class="btn white-btn text-initial mr10 mb5 font-bold active">{{ __('I
		am a customer') }}</a>
		<!--<a href="{{ route('frontend.seller-register') }}" class="btn white-btn text-initial mb5 font-bold">{{ __('I
		am a seller') }}</a>-->
	</div>
	</div>
	<div class="register">
	<h4>{{ __('Create an customer account') }}</h4>
	<!--<div class="col-md-6 offset-md-3">
	<a href="{{route('login.google')}}" class="btn btn-danger btn-block">Login with Google</a>
	<a href="{{route('login.facebook')}}" class="btn btn-primary btn-block">Login with Facebook</a>
	<a href="{{route('login.github')}}" class="btn btn-dark btn-block">Login with Github</a> </div>--> 
	<p>{{ __('Please fill in the information below') }}</p>

		@if(Session::has('success'))
		<div class="alert alert-success">
			{{Session::get('success')}}
			</div>
			@endif
			@if(Session::has('fail'))
			<div class="alert alert-danger">
			{{Session::get('fail')}}
			</div>
			@endif
			<form class="form" method="POST" action="{{ route('frontend.customer-register') }}">
				@csrf
				<div class="form-group">
				<input name="name" type="text" class="form-control @error('name') is-invalid @enderror"
					placeholder="{{ __('Name') }}" value="{{ old('name') }}" required />
				@if ($errors->has('name'))
				<span class="text-danger">{{ $errors->first('name') }}</span>
				@endif
				</div>
				<div class="form-group">
				<input name="email" type="email" class="form-control @error('email') is-invalid @enderror"
					placeholder="{{ __('Email Address') }}" value="{{ old('email') }}" required />
				@if ($errors->has('email'))
				<span class="text-danger">{{ $errors->first('email') }}</span>
				@endif
				</div>
				<div class="form-group">
				<input name="password" type="password" class="form-control @error('password') is-invalid @enderror"
					placeholder="{{ __('Password') }}" required />
				@if ($errors->has('password'))
				<span class="text-danger">{{ $errors->first('password') }}</span>
				@endif
				</div>
				<div class="form-group">
				<input name="password_confirmation" type="password" class="form-control"
					placeholder="{{ __('Confirm password') }}" required />
				</div>
				@if($gtext['is_recaptcha'] == 1)
				<div class="form-group">
					<div class="g-recaptcha" data-sitekey="{{ $gtext['sitekey'] }}"></div>
					@if ($errors->has('g-recaptcha-response'))
					<span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
					@endif
					</div>
					@endif
					<input type="submit" class="btn theme-btn full" value="{{ __('Register') }}">
					</form>
					@if (Route::has('frontend.reset'))
					<h3><a href="{{ route('frontend.reset') }}">{{ __('Forgot your password?') }}</a></h3>
					@endif
					@if (Route::has('frontend.login'))
					<h3><a href="{{ route('frontend.login') }}">{{ __('Back to login') }}</a></h3>
					@endif
				</div>
				</div> </div> </div>
				</section> <!-- /Inner Section/ --> </main>

		@endsection

		@push('scripts')
		@if($gtext['is_recaptcha'] == 1)
		<script src='https://www.google.com/recaptcha/api.js' async defer></script>
		@endif
		@endpush