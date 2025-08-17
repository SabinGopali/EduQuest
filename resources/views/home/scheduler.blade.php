@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-10">
			<div class="card mb-4">
				<div class="card-header">Optimal Study Scheduler (Weighted Interval Scheduling)</div>
				<div class="card-body">
					<p>
						Use this tool to select the best set of non-overlapping study sessions, classes or activities that maximizes your total learning value under time conflicts.
					</p>
					<form method="POST" action="{{ url('/optimal-schedule/compute') }}">
						@csrf
						<div id="tasks-container"></div>
						<button type="button" class="btn btn-secondary" onclick="addTaskRow()">Add Task</button>
						<button type="submit" class="btn btn-primary">Compute Optimal Schedule</button>
					</form>
				</div>
			</div>

			<div class="card mb-4">
				<div class="card-header">How it works</div>
				<div class="card-body">
					<p>
						This feature implements the dynamic programming solution to the Weighted Interval Scheduling problem:
					</p>
					<ol>
						<li>Sort your tasks by their end time.</li>
						<li>For each task i, find the last task p(i) that finishes before task i starts.</li>
						<li>Build a DP table where DP[i] = max(value[i] + DP[p(i)], DP[i-1]).</li>
						<li>Backtrack the decisions to reconstruct the optimal, conflict-free set.</li>
					</ol>
					<p>
						This algorithm is powerful for students because it helps allocate limited time among many competing activities (lectures, study blocks, labs, part-time work) to maximize total benefit without overlaps.
					</p>
				</div>
			</div>

			@if(isset($result))
				<div class="card">
					<div class="card-header">Result</div>
					<div class="card-body">
						<p><strong>Optimal Total Value:</strong> {{ number_format($result['total_value'], 2) }}</p>
						@if(count($result['selected']) === 0)
							<p>No valid non-overlapping schedule found from the given tasks.</p>
						@else
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>#</th>
										<th>Name</th>
										<th>Start</th>
										<th>End</th>
										<th>Value</th>
									</tr>
								</thead>
								<tbody>
									@foreach($result['selected'] as $idx => $task)
										<tr>
											<td>{{ $idx + 1 }}</td>
											<td>{{ $task['name'] }}</td>
											<td>{{ $task['start'] > 0 ? date('Y-m-d H:i', $task['start']) : $task['start'] }}</td>
											<td>{{ $task['end'] > 0 ? date('Y-m-d H:i', $task['end']) : $task['end'] }}</td>
											<td>{{ $task['value'] }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						@endif
					</div>
				</div>
			@endif
		</div>
	</div>
</div>

<script>
let taskIndex = 0;

function addTaskRow() {
	const container = document.getElementById('tasks-container');
	const row = document.createElement('div');
	row.className = 'row g-3 align-items-end mb-2';
	row.innerHTML = `
		<div class="col-md-3">
			<label class="form-label">Name</label>
			<input name="tasks[${taskIndex}][name]" class="form-control" placeholder="e.g., Physics Revision" required />
		</div>
		<div class="col-md-3">
			<label class="form-label">Start</label>
			<input type="datetime-local" name="tasks[${taskIndex}][start]" class="form-control" />
		</div>
		<div class="col-md-3">
			<label class="form-label">End</label>
			<input type="datetime-local" name="tasks[${taskIndex}][end]" class="form-control" />
		</div>
		<div class="col-md-2">
			<label class="form-label">Value</label>
			<input type="number" step="0.01" min="0" name="tasks[${taskIndex}][value]" class="form-control" placeholder="e.g., 10" required />
		</div>
		<div class="col-md-1">
			<button type="button" class="btn btn-outline-danger" onclick="this.closest('.row').remove()">Remove</button>
		</div>
	`;
	container.appendChild(row);
	taskIndex++;
}

// Add two sample rows by default
window.addEventListener('DOMContentLoaded', () => {
	addTaskRow();
	addTaskRow();
});
</script>
@endsection