@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 lg:px-8 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-800">Notifikasi</h1>
            <p class="text-sm text-gray-500">Notifikasi untuk akun Anda</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('notifications.index', ['unread' => $onlyUnread ? 0 : 1]) }}"
               class="px-3 py-2 rounded-lg text-sm border {{ $onlyUnread ? 'bg-red-50 border-red-200 text-red-700' : 'bg-white border-gray-200 text-gray-700' }}">
                {{ $onlyUnread ? 'Tampilkan Semua' : 'Hanya Unread' }}
            </a>
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button type="submit" class="px-3 py-2 rounded-lg text-sm bg-gray-800 text-white hover:bg-gray-900">
                    Tandai Semua Dibaca
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        @forelse($notifications as $n)
            <div class="px-4 lg:px-6 py-4 border-b border-gray-100 flex items-start justify-between gap-4 {{ $n->read_at ? '' : 'bg-red-50/30' }}">
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="font-semibold text-gray-800 truncate">{{ data_get($n->data, 'title', 'Notifikasi') }}</p>
                        @if(!$n->read_at)
                            <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-700">Unread</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-600 mt-1 break-words">{{ data_get($n->data, 'message', '-') }}</p>
                    <p class="text-xs text-gray-400 mt-2">{{ $n->created_at?->diffForHumans() }}</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    @if($url = data_get($n->data, 'url'))
                        <a href="{{ $url }}" class="px-3 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-50">
                            Buka
                        </a>
                    @endif
                    @if(!$n->read_at)
                        <form method="POST" action="{{ route('notifications.read', $n->id) }}">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-lg text-sm bg-red-600 text-white hover:bg-red-700">
                                Tandai Dibaca
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="px-6 py-10 text-center text-gray-500">
                Tidak ada notifikasi.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
