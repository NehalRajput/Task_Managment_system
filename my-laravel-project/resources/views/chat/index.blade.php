@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Users List -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ auth()->user()->role === 'admin' ? 'Interns' : 'Admins' }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" id="users-list">
                        @if(auth()->user()->role === 'admin')
                            @foreach($interns as $intern)
                                <a href="#" class="list-group-item list-group-item-action user-item" 
                                   data-user-id="{{ $intern->id }}"
                                   onclick="selectUser({{ $intern->id }}, '{{ $intern->name }}')">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $intern->name }}</h6>
                                            <small class="text-muted">{{ $intern->email }}</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill unread-count" 
                                              id="unread-{{ $intern->id }}" style="display: none;">0</span>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            @foreach($admins as $admin)
                                <a href="#" class="list-group-item list-group-item-action user-item"
                                   data-user-id="{{ $admin->id }}"
                                   onclick="selectUser({{ $admin->id }}, '{{ $admin->name }}')">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $admin->name }}</h6>
                                            <small class="text-muted">{{ $admin->email }}</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill unread-count"
                                              id="unread-{{ $admin->id }}" style="display: none;">0</span>
                                    </div>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0" id="chat-header">Select a user to start chatting</h5>
                </div>
                <div class="card-body">
                    <div class="chat-messages" style="height: 400px; overflow-y: auto;">
                        <!-- Messages will be loaded here -->
                    </div>
                    <div class="chat-input mt-3">
                        <form id="message-form" onsubmit="return false;">
                            <div class="input-group">
                                <input type="text" class="form-control" id="message-input" 
                                       placeholder="Type your message..." disabled>
                                <button class="btn btn-primary" type="submit" id="send-button" disabled>
                                    Send
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .chat-messages {
        padding: 1rem;
    }
    .message {
        margin-bottom: 1rem;
        max-width: 80%;
    }
    .message.sent {
        margin-left: auto;
    }
    .message.received {
        margin-right: auto;
    }
    .message-content {
        padding: 0.5rem 1rem;
        border-radius: 1rem;
    }
    .sent .message-content {
        background-color: #007bff;
        color: white;
    }
    .received .message-content {
        background-color: #e9ecef;
    }
    .user-item.active {
        background-color: #f8f9fa;
    }
    .unread-count {
        font-size: 0.8rem;
    }
</style>
@endpush

@push('scripts')
<script>
    let selectedUserId = null;
    let selectedUserName = null;

    function selectUser(userId, userName) {
        selectedUserId = userId;
        selectedUserName = userName;
        
        // Update UI
        document.querySelectorAll('.user-item').forEach(item => {
            item.classList.remove('active');
        });
        event.currentTarget.classList.add('active');
        
        // Update chat header
        document.getElementById('chat-header').textContent = `Chat with ${userName}`;
        
        // Enable input
        document.getElementById('message-input').disabled = false;
        document.getElementById('send-button').disabled = false;
        
        // Load chat history
        loadChatHistory(userId);
        
        // Hide unread count
        document.getElementById(`unread-${userId}`).style.display = 'none';
    }

    // Handle message form submission
    document.getElementById('message-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value.trim();
        
        if (message && selectedUserId) {
            sendMessage(selectedUserId, message);
            messageInput.value = '';
        }
    });

    // Initialize chat
    document.addEventListener('DOMContentLoaded', function() {
        // Add meta tags for user info
        const metaUserId = document.createElement('meta');
        metaUserId.name = 'user-id';
        metaUserId.content = '{{ auth()->id() }}';
        document.head.appendChild(metaUserId);

        const metaUserRole = document.createElement('meta');
        metaUserRole.name = 'user-role';
        metaUserRole.content = '{{ auth()->user()->role }}';
        document.head.appendChild(metaUserRole);
    });
</script>
@endpush
@endsection 