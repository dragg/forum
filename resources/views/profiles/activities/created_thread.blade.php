@component('profiles.activities.activity')

  @slot('header')
    {{ $profileUser->name }} published a <a
        href="{{ route('threads.show', [$activity->subject->channel,$activity->subject]) }}">
      "{{ $activity->subject->title }}"
    </a>
  @endslot

  @slot('body')
    {{ $activity->subject->body }}
  @endslot

@endcomponent
