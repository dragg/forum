<div id="reply-{{ $reply->id }}" class="panel panel-default">
  <div class="panel-heading">
    <div class="level">
      <h5 class="flex">
        <a href="{{ route('profile', $reply->owner) }}">
          {{ $reply->owner->name }}
        </a> said {{ $reply->created_at->diffForHumans() }}
      </h5>

      <div>
        <form method="POST" action="{{ route('replies.favorite', [$reply]) }}">
          {{ csrf_field() }}

          <button type="submit" class="btn btn-default" {{ $reply->isFavorited(auth()->id()) ? 'disabled' : '' }}>
            {{ $reply->favorites_count }} {{ str_plural('Favorite', $reply->favorites_count) }}
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="reply-body panel-body">{{ $reply->body }}</div>

  @can('update', $reply)
    <div class="panel-footer">
      <form action="{{ route('replies.delete', $reply) }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}

        <button type="submit" class="btn btn-danger btn-xs">Delete</button>
      </form>
    </div>
  @endcan
</div>
