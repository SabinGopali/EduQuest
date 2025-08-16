@extends('layouts.app')

@section('content')
<style>
  .sm-container { max-width: 1200px; margin: 200px auto 60px; padding: 0 20px; }
  .sm-title { text-align: center; font-size: 2.2rem; font-weight: 800; color: #222; margin-bottom: 10px; }
  .sm-sub { text-align: center; color: #555; margin-bottom: 26px; }
  .sm-controls { max-width: 760px; margin: 0 auto 22px; display: flex; gap: 10px; }
  .sm-controls input { flex: 1; border-radius: 8px; border: 1px solid #ced4da; padding: 10px 14px; }
  .sm-btn { border-radius: 8px; font-weight: 600; padding: 10px 20px; border: none; cursor: pointer; transition: all 0.2s ease; }
  .sm-btn-primary { background-color: #0d6efd; color: white; }
  .sm-btn-primary:hover { background-color: #0b5ed7; }

  .sm-grid { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
  .sm-card { width: 340px; background: #fff; border: 1px solid #eee; border-radius: 10px; padding: 16px; box-shadow: 0 4px 10px rgb(0 0 0 / 0.08); }
  .sm-logo { height: 76px; width: 76px; object-fit: contain; border-radius: 50%; border: 2px solid #0d6efd; background: #fff; padding: 5px; display:block; margin: 6px auto 12px; }
  .sm-name { text-align: center; font-weight: 700; color: #222; margin-bottom: 6px; }
  .sm-meta { text-align: center; color: #666; font-size: 14px; margin-bottom: 8px; }
  .sm-cap { text-align: center; font-size: 12px; color: #444; margin-bottom: 10px; }
  .sm-list { margin: 0; padding-left: 18px; min-height: 28px; }
  .sm-empty { text-align: center; color: #999; font-style: italic; }

  .sm-unmatched { max-width: 900px; margin: 30px auto 0; background: #fff; border: 1px solid #eee; border-radius: 10px; padding: 18px; box-shadow: 0 4px 10px rgb(0 0 0 / 0.06); }
  .sm-unmatched h3 { font-size: 1.2rem; font-weight: 700; margin-bottom: 8px; }

  .sm-explain { max-width: 900px; margin: 24px auto 0; background: #fff; border: 1px solid #eee; border-radius: 10px; padding: 18px; box-shadow: 0 4px 10px rgb(0 0 0 / 0.06); }
  .sm-explain h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 6px; }
  .sm-explain ul { padding-left: 20px; margin: 0; }
  .sm-explain li { margin: 4px 0; color: #444; }

  @media(max-width: 768px){
    .sm-controls{ flex-direction: column; }
    .sm-card{ width: 100%; }
    .sm-container{ margin-top: 140px; }
  }
</style>

<div class="sm-container">
  <h1 class="sm-title">Stable Allocation (Gale–Shapley)</h1>
  <p class="sm-sub">Allocates students to approved colleges based on mutual content preferences. Not filtering/searching—this is a stable matching algorithm.</p>

  <form class="sm-controls" method="GET">
    <input type="number" min="0" step="1" name="capacity" value="{{ (int)($defaultCapacity ?? 3) }}" placeholder="Default seats per college (e.g., 3)" />
    <button class="sm-btn sm-btn-primary" type="submit">Run Allocation</button>
  </form>

  <div class="sm-grid">
    @forelse($assignments as $cid => $sids)
      @php $college = $collegesById[$cid] ?? null; @endphp
      @if($college)
        <div class="sm-card">
          <img class="sm-logo" src="{{ isset($college->logo) ? asset('storage/' . $college->logo) : asset('img/landing.jpg') }}" alt="Logo" />
          <div class="sm-name">{{ $college->name }}</div>
          <div class="sm-meta">{{ $college->address }}</div>
          <div class="sm-cap">Seats: {{ count($sids) }} / {{ $capacityMap[$cid] ?? 0 }}</div>
          @if(count($sids) === 0)
            <div class="sm-empty">No student assigned</div>
          @else
            <ul class="sm-list">
              @foreach($sids as $sid)
                @php $stu = $studentsById[$sid] ?? null; @endphp
                @if($stu)
                  <li>{{ $stu->name }} @if($stu->gpa) (GPA: {{ $stu->gpa }}) @endif</li>
                @endif
              @endforeach
            </ul>
          @endif
          <div class="sm-meta" style="margin-top:10px;">
            <a class="sm-btn sm-btn-primary" href="/college/detail/{{ $college->id }}">View</a>
          </div>
        </div>
      @endif
    @empty
      <div>No colleges available.</div>
    @endforelse
  </div>

  <div class="sm-unmatched">
    <h3>Unmatched Students</h3>
    @if(empty($unmatched))
      <div class="sm-empty">All students matched</div>
    @else
      <ul class="sm-list">
        @foreach($unmatched as $stu)
          <li>{{ $stu->name }} @if($stu->gpa) (GPA: {{ $stu->gpa }}) @endif</li>
        @endforeach
      </ul>
    @endif
  </div>

  <div class="sm-explain">
    <h3>How this works</h3>
    <ul>
      <li><strong>Preferences</strong>: We derive each student's ranking of colleges by comparing their interests/goals with each college's combined course descriptions using TF-IDF and cosine similarity.</li>
      <li><strong>Colleges' preferences</strong>: Each college ranks students by the same content similarity signal.</li>
      <li><strong>Stable matching</strong>: We run Gale–Shapley (many-to-one) so no student–college pair prefers each other over their assignment, given capacities.</li>
      <li><strong>Capacity</strong>: You can set a default number of seats per college in the form above.</li>
    </ul>
  </div>
</div>
@endsection