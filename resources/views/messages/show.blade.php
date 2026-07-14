@foreach ($messages as $msg)
    {{ $msg->sender_id }}: {{ $msg->body }}
@endforeach
