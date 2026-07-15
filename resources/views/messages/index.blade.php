@foreach ($messages as $conversation)
    {{ $conversation->partner->name }} ({{ $conversation->unread_count }})
    {{ $conversation->latest_message->body }}
@endforeach
