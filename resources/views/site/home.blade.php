@extends('layouts.site')

@section('title', trans('meta.title'))
@section('description', trans('meta.description'))

@section('content')
    @include('site.partials.hero')
    @include('site.partials.about')
    @include('site.partials.skills')
    @include('site.partials.tasks')
    @include('site.partials.contact')
@endsection
