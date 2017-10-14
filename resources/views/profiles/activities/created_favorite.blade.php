@component('profiles.activities.activity')

  @slot('header')
    <a href="{{ model_show_path($activity->subject->favorited) }}">
      {{ $profileUser->name }} favorited a reply
    </a>
    {{--<a href="{{ route('threads.show', [$activity->subject->thread->channel,$activity->subject->thread]) }}">
      "{{ $activity->subject->thread->title }}"
    </a>--}}
  @endslot

  @slot('body')
    {{ $activity->subject->favorited->body }}
  @endslot

@endcomponent
