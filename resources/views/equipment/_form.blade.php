{{--
    Shared by "create" and "edit".
    Expects: $action (form URL), $submit (button label)
    Optional: $equipment (when editing)
    old() is checked first, so after a failed validation the user's typing is kept;
    otherwise the saved value (edit) or a default (create) is used.
--}}
@php($e = $equipment ?? null)

<form method="POST" action="{{ $action }}" class="panel form-card" novalidate>
    @csrf
    @if ($e)
        @method('PUT')
    @endif

    @if ($errors->any())
        <div class="alert-error" role="alert">
            <strong>{{ $errors->count() === 1 ? 'There is 1 problem to fix' : 'There are '.$errors->count().' problems to fix' }}</strong>
            <ul>
                @foreach ($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-grid">
        <div class="field full">
            <label for="equipment_name">Equipment name</label>
            <input type="text" id="equipment_name" name="equipment_name" maxlength="255"
                   value="{{ old('equipment_name', $e?->equipment_name) }}"
                   placeholder="e.g. Carbon fiber paddle"
                   class="{{ $errors->has('equipment_name') ? 'is-invalid' : '' }}">
            @error('equipment_name') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="category">Category</label>
            <select id="category" name="category" class="{{ $errors->has('category') ? 'is-invalid' : '' }}">
                <option value="">Choose a category</option>
                @foreach (\App\Models\Equipment::CATEGORIES as $value => $label)
                    <option value="{{ $value }}" @selected(old('category', $e?->category) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('category') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="unit_price">Unit price</label>
            <div class="price-input">
                <span>₱</span>
                <input type="number" id="unit_price" name="unit_price" step="0.01" min="0" inputmode="decimal"
                       value="{{ old('unit_price', $e?->unit_price) }}"
                       placeholder="0.00"
                       class="{{ $errors->has('unit_price') ? 'is-invalid' : '' }}">
            </div>
            @error('unit_price') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="field full">
            <label for="description">Description</label>
            <textarea id="description" name="description" maxlength="2000"
                      placeholder="Materials, size, weight, pack count and other specifications"
                      class="{{ $errors->has('description') ? 'is-invalid' : '' }}">{{ old('description', $e?->description) }}</textarea>
            @error('description') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="field full">
            <span class="label" id="status-label">Availability</span>
            @php($currentStatus = old('availability_status', $e?->availability_status ?? 'available'))
            <div class="segmented {{ $errors->has('availability_status') ? 'is-invalid' : '' }}" role="radiogroup" aria-labelledby="status-label">
                @foreach (\App\Models\Equipment::STATUSES as $value => $label)
                    <label>
                        <input type="radio" name="availability_status" value="{{ $value }}" @checked($currentStatus === $value)>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            @error('availability_status') <p class="field-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">{{ $submit }}</button>
        <a href="{{ $e ? route('equipment.show', $e) : route('equipment.index') }}" class="btn">Cancel</a>
    </div>
</form>
