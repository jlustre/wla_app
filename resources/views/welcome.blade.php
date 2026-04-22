
@extends('layouts.app')

@section('title', 'Wealth Legacy Alliance | Build Your Future')

@section('mobile-nav')
  <div class="flex flex-col space-y-4 text-center">
    <a href="#hero" class="text-slate-300 hover:text-white">Home</a>
    <a href="#about" class="text-slate-300 hover:text-white">About</a>
    <a href="#features" class="text-slate-300 hover:text-white">Features</a>
    <a href="#how" class="text-slate-300 hover:text-white">How It Works</a>
    <a href="#testimonials" class="text-slate-300 hover:text-white">Testimonials</a>
    <a href="#contact" class="text-slate-300 hover:text-white">Contact</a>
  </div>
@endsection

@section('mobile-auth-buttons')
    <div class="flex flex-col space-y-3">
        <button class="w-full py-3 text-white border border-slate-700 rounded-xl">Login</button>
        <button class="w-full py-3 bg-emerald-600 text-white rounded-xl font-bold">Register</button>
    </div>
@endsection

@section('content')

  @include('landing.hero')
  @include('landing.about')
  @include('landing.features')
  @include('landing.how')
  @include('landing.testimonials')
  @include('landing.contact')

  <section class="w-full">
    @include('landing.cta')

    @include('landing.footer')
  </section>
@endsection
      
