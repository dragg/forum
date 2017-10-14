<?php

function model_show_path(\Illuminate\Database\Eloquent\Model $model)
{
    switch ($modelClass = get_class($model)) {
        case \App\Models\Thread::class:
            return route('threads.show', [$model->channel, $model]);
        case \App\Models\Reply::class:
            return model_show_path($model->thread) . "#reply-{$model->id}";
        default:
            throw new InvalidArgumentException("Not defined path for {$modelClass}");
    }
}