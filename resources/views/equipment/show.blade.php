@extends('layouts.app')

@section('title', $equipment->equipment_name)
@section('heading', $equipment->equipment_name)
@section('subheading', $equipment->category_label)

@section('actions')
    <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <a href="{{ route('equipment.index') }}" class="btn"><x-icon name="arrow-left" /> Back to list</a>
        <a href="{{ route('equipment.edit', $equipment) }}" class="btn"><x-icon name="pencil" /> Edit</a>
        <button type="button" class="btn btn-danger"
                data-delete
                data-action="{{ route('equipment.destroy', $equipment) }}"
                data-name="{{ $equipment->equipment_name }}">
            <x-icon name="trash" /> Delete
        </button>
    </div>
@endsection

@section('content')
    <div class="panel detail">
        <dl>
            <div class="row">
                <dt>Unit price</dt>
                <dd class="price">₱{{ number_format($equipment->unit_price, 2) }}</dd>
            </div>
            <div class="row">
                <dt>Availability</dt>
                <dd><x-status-pill :status="$equipment->availability_status" /></dd>
            </div>
            <div class="row">
                <dt>Category</dt>
                <dd>{{ $equipment->category_label }}</dd>
            </div>
            <div class="row">
                <dt>Description</dt>
                <dd>{{ $equipment->description }}</dd>
            </div>
            <div class="row">
                <dt>Added by</dt>
                <dd>{{ $equipment->user->name }} <span class="muted">(&#64;{{ $equipment->user->username }})</span></dd>
            </div>
            <div class="row">
                <dt>Created</dt>
                <dd>{{ $equipment->created_at->format('M j, Y · g:i A') }}</dd>
            </div>
            <div class="row">
                <dt>Last updated</dt>
                <dd>{{ $equipment->updated_at->format('M j, Y · g:i A') }}</dd>
            </div>
        </dl>
    </div>
@endsection
