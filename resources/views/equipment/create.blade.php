@extends('layouts.app')

@section('title', 'Add equipment')
@section('heading', 'Add equipment')
@section('subheading', 'Create a new inventory record.')

@section('actions')
    <a href="{{ route('equipment.index') }}" class="btn"><x-icon name="arrow-left" /> Back to list</a>
@endsection

@section('content')
    @include('equipment._form', [
        'action' => route('equipment.store'),
        'submit' => 'Add equipment',
    ])
@endsection
