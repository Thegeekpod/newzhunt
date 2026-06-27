@extends('layouts.admin')

@section('title', 'Opinion Polls')
@section('header_title', 'Opinion Polls')


@section('content')


<div class="polls-layout">
  <!-- Left Side: Create Poll -->
  <div class="admin-card">
    <h2 class="card-title" style="margin-bottom: 20px;">Create New Poll</h2>
    
    <form action="{{ route('admin.polls.store') }}" method="POST" id="poll-form">
      @csrf
      
      <div class="form-group">
        <label for="question" class="form-label">Poll Question <span style="color: var(--danger)">*</span></label>
        <input type="text" name="question" id="question" class="form-control" placeholder="e.g., Do you think the budget has enough tax concessions?" required>
      </div>
      
      <div class="form-group" id="options-group">
        <label class="form-label">Choices <span style="color: var(--danger)">*</span></label>
        <div style="display: flex; flex-direction: column; gap: 10px;" id="options-wrapper">
          <input type="text" name="options[]" class="form-control" placeholder="Choice 1" required>
          <input type="text" name="options[]" class="form-control" placeholder="Choice 2" required>
        </div>
        
        <button type="button" class="btn-admin btn-admin-secondary" id="add-option-btn" style="margin-top: 10px; width: 100%; justify-content: center; padding: 6px 12px; font-size: 12px;">
          <i class="fas fa-plus"></i> Add Another Choice
        </button>
      </div>
      
      <button type="submit" class="btn-admin btn-admin-primary" style="width: 100%; justify-content: center; margin-top: 10px;">
        <i class="fas fa-save"></i> Create Poll
      </button>
    </form>
  </div>

  <!-- Right Side: Polls List -->
  <div class="admin-card">
    <h2 class="card-title" style="margin-bottom: 20px;">Existing Polls</h2>
    
    @forelse($polls as $poll)
      <div class="poll-item-card" style="border-left: 4px solid {{ $poll->is_active ? 'var(--success)' : 'var(--border-color)' }}">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; gap: 12px;">
          <div>
            <h4 style="font-size: 15px; font-weight: 700; color: #ffffff;">{{ $poll->question }}</h4>
            <span style="font-size: 12px; color: var(--text-muted); display: inline-block; margin-top: 4px;">
              <i class="fas fa-users"></i> Total Votes: {{ number_format($poll->total_votes) }}
            </span>
          </div>
          
          <div style="display: flex; gap: 8px; align-items: center; flex-shrink: 0;">
            <!-- Active Toggle -->
            <form action="{{ route('admin.polls.update', $poll->id) }}" method="POST">
              @csrf
              @method('PUT')
              <button type="submit" class="btn-admin {{ $poll->is_active ? 'btn-admin-danger' : 'btn-admin-primary' }}" style="padding: 6px 10px; font-size: 12px;">
                {{ $poll->is_active ? 'Deactivate' : 'Activate' }}
              </button>
            </form>
            
            <!-- Delete -->
            <form action="{{ route('admin.polls.destroy', $poll->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this poll?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn-admin btn-admin-secondary" style="padding: 6px 10px; font-size: 12px; color: var(--danger)">
                <i class="fas fa-trash"></i>
              </button>
            </form>
          </div>
        </div>
        
        <!-- Options progress list -->
        <div style="margin-top: 10px;">
          @foreach($poll->options as $opt)
            @php
                $pct = $poll->total_votes > 0 ? round(($opt->vote_count / $poll->total_votes) * 100) : 0;
            @endphp
            <div class="option-bar-container">
              <div class="option-label-flex">
                <span>{{ $opt->option_text }}</span>
                <span style="font-weight: 600;">
                  {{ $pct }}% ({{ number_format($opt->vote_count) }} votes)
                </span>
              </div>
              <div class="option-bar-bg">
                <div class="option-bar-fill" style="width: {{ $pct }}%; background-color: {{ $poll->is_active ? 'var(--primary)' : 'var(--text-muted)' }}"></div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @empty
      <div style="color: var(--text-muted); font-size: 14px; text-align: center; padding: 30px;">No polls created yet.</div>
    @endforelse
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Dynamic choice input adder
    const addBtn = document.getElementById('add-option-btn');
    const wrapper = document.getElementById('options-wrapper');
    let optionCount = 2;
    
    if (addBtn && wrapper) {
      addBtn.addEventListener('click', () => {
        optionCount++;
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'options[]';
        input.className = 'form-control';
        input.placeholder = `Choice ${optionCount}`;
        input.required = true;
        
        wrapper.appendChild(input);
      });
    }
  });
</script>
@endsection
