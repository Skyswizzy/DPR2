@extends('layouts.app')

@section('title', 'Edit '.$equipment->equipment_name)
@section('heading', 'Edit equipment')
@section('subheading', $equipment->equipment_name)

@section('actions')
    <a href="{{ route('equipment.show', $equipment) }}" class="btn"><x-icon name="arrow-left" /> Back to details</a>
@endsection

@section('content')
    @include('equipment._form', [
        'action' => route('equipment.update', $equipment),
        'submit' => 'Save changes',
        'equipment' => $equipment,
    ])
@endsection
