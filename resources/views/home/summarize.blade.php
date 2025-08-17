@extends('layouts.app')

@section('content')
<style>
  .tsr-wrap { max-width: 1100px; margin: 180px auto 60px; padding: 0 20px; }
  .tsr-title { text-align: center; font-size: 2.1rem; font-weight: 800; color: #222; margin-bottom: 10px; }
  .tsr-sub { text-align: center; color: #555; margin-bottom: 24px; }

  .tsr-form { display: grid; gap: 12px; }
  .tsr-textarea { width: 100%; min-height: 220px; resize: vertical; border: 1px solid #ced4da; border-radius: 10px; padding: 14px; }
  .tsr-row { display: flex; gap: 10px; align-items: center; }
  .tsr-row input { width: 140px; border-radius: 8px; border: 1px solid #ced4da; padding: 10px 14px; }
  .tsr-btn { border-radius: 8px; font-weight: 700; padding: 10px 20px; border: none; cursor: pointer; background: #0d6efd; color: #fff; }

  .tsr-card { background: #fff; border: 1px solid #eee; border-radius: 10px; padding: 16px; box-shadow: 0 4px 10px rgb(0 0 0 / 0.08); margin-top: 18px; }
  .tsr-item { margin: 6px 0; color: #333; }

  .tsr-explain { margin-top: 24px; background: #fff; border: 1px solid #eee; border-radius: 10px; padding: 16px; box-shadow: 0 4px 10px rgb(0 0 0 / 0.06); }
  .tsr-explain h3 { font-size: 1.15rem; font-weight: 700; margin-bottom: 6px; }
  .tsr-explain ul { padding-left: 20px; margin: 0; }
  .tsr-explain li { margin: 4px 0; color: #444; }

  @media(max-width: 768px){ .tsr-wrap{ margin-top: 140px; } .tsr-row{ flex-direction: column; align-items: flex-start; } .tsr-row input{ width: 100%; } }
</style>

<div class="tsr-wrap">
  <h1 class="tsr-title">Quick Text Summarizer</h1>
  <p class="tsr-sub">Paste any long text (course descriptions, articles, notes) and get a concise summary using TextRank.</p>

  <form class="tsr-form" method="POST" action="{{ url('/summarize') }}">
    @csrf
    <textarea class="tsr-textarea" name="text" placeholder="Paste text here...">{{ old('text', $input ?? '') }}</textarea>
    <div class="tsr-row">
      <label for="sentences"><strong>Summary length (sentences)</strong></label>
      <input id="sentences" name="sentences" type="number" min="1" max="8" value="{{ old('sentences', $sentences ?? 3) }}" />
      <button type="submit" class="tsr-btn">Summarize</button>
    </div>
  </form>

  @if(isset($summary) && is_array($summary) && count($summary) > 0)
    <div class="tsr-card">
      <h3 style="font-weight: 800; margin-bottom: 6px;">Summary</h3>
      @foreach($summary as $s)
        <div class="tsr-item">{{ $loop->iteration }}. {{ $s }}</div>
      @endforeach
    </div>
  @elseif(isset($summary))
    <div class="tsr-card"><em>No summary to show. Please paste some text above.</em></div>
  @endif

  <div class="tsr-explain">
    <h3>How TextRank works</h3>
    <ul>
      <li><strong>1. Split into sentences</strong>: We detect sentences and lightly clean/normalize them.</li>
      <li><strong>2. Build a similarity graph</strong>: Each sentence is a node; edges are weighted by cosine similarity between sentence word-frequency vectors (after stopword removal).</li>
      <li><strong>3. Run PageRank</strong>: We iterate a damping process over the graph to estimate the importance of each sentence in the context of all others.</li>
      <li><strong>4. Select top sentences</strong>: The highest-ranked sentences are picked and ordered as they appear to form a coherent summary.</li>
    </ul>
  </div>
</div>
@endsection