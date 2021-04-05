<x-app>
    <chat :initial-messages="{{ $chat->messages }}" :user="{{ current_user() }}" :recipient="{{ $recipient }}"
          :chat-id="{{ json_encode($chat->id) }}"></chat>
</x-app>
