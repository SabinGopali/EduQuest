@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 120px;">
	<div class="row mb-4">
		<div class="col-12">
			<h2 class="mb-3">Find Colleges and Courses</h2>
			<form method="GET" action="{{ route('search.index') }}" class="row g-3">
				<div class="col-md-4">
					<input type="text" name="q" value="{{ old('q', $q) }}" class="form-control" placeholder="Search keywords (course, college, description)">
				</div>
				<div class="col-md-2">
					<select name="stream" class="form-select">
						<option value="">Stream</option>
						@foreach($streams as $s)
							<option value="{{ $s }}" {{ ($filters['stream'] ?? '') === $s ? 'selected' : '' }}>{{ $s }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-2">
					<select name="subStream" class="form-select">
						<option value="">Sub-Stream</option>
						@foreach($subStreams as $ss)
							<option value="{{ $ss }}" {{ ($filters['subStream'] ?? '') === $ss ? 'selected' : '' }}>{{ $ss }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-2">
					<select name="duration" class="form-select">
						<option value="">Duration</option>
						@foreach($durations as $d)
							<option value="{{ $d }}" {{ ($filters['duration'] ?? '') === $d ? 'selected' : '' }}>{{ $d }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-2">
					<input type="number" step="0.01" name="min_gpa" value="{{ old('min_gpa', $filters['min_gpa'] ?? '') }}" class="form-control" placeholder="Max GPA limit">
				</div>
				<div class="col-md-3">
					<input type="text" name="college" value="{{ old('college', $filters['college'] ?? '') }}" class="form-control" placeholder="College name">
				</div>
				<div class="col-md-2">
					<button type="submit" class="btn btn-primary w-100">Search</button>
				</div>
			</form>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			@if(empty($results))
				<div class="alert alert-info">No results found.</div>
			@else
				@foreach($results as $item)
					@php $college = $item['college']; @endphp
					<div class="card mb-3">
						<div class="card-body">
							<div class="d-flex justify-content-between align-items-start">
								<div>
									<h5 class="card-title mb-1">{{ $college->name }}</h5>
									<p class="mb-1 text-muted">{{ $college->address }}</p>
									<p class="mb-2">{{ Str::limit($college->description, 180) }}</p>
									<a href="{{ route('college.getByIdForStudent', ['id' => $college->id]) }}" class="btn btn-outline-secondary btn-sm">View Details</a>
								</div>
								<div class="text-end">
									<span class="badge bg-success">Score: {{ number_format($item['score'], 2) }}</span>
								</div>
							</div>
						</div>
					</div>
				@endforeach
			@endif
		</div>
	</div>
</div>
@endsection