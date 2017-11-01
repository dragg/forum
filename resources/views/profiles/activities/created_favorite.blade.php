@component('profiles.activities.activity')

  @slot('header')
    <a href="{{ model_show_path($activity->subject->favorited) }}">
      {{ $profileUser->name }} favorited a reply
    </a>
  @endslot

  @slot('body')
    {{ $activity->subject->favorited->body }}
  @endslot

@endcomponent
