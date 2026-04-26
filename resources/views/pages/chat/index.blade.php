@extends('layouts.chat')

@section('title', 'Chat - ' . ($proyek->kode_proyek ?? 'Proyek'))

@section('content')

@php
    /** @var \App\Models\User $authUser */
    $authUser = auth()->user();
    $statusColors = [
        'menunggu'   => 'bg-gray-100 text-gray-700',
        'penawaran'  => 'bg-blue-100 text-blue-800',
        'pembayaran' => 'bg-purple-100 text-purple-800',
        'pengiriman' => 'bg-orange-100 text-orange-800',
        'selesai'    => 'bg-green-100 text-green-800',
        'gagal'      => 'bg-red-100 text-red-800',
    ];
    $statusColor = $statusColors[strtolower($proyek->status ?? '')] ?? 'bg-gray-100 text-gray-700';
@endphp

{{-- Wrapper full-height flex column --}}
<div class="flex flex-col h-full">

    {{-- ─── Header Chat ────────────────────────────────────────────────────── --}}
    <div class="bg-red-800 px-4 py-3 text-white shadow-md flex-shrink-0">
        <div class="flex items-center gap-3">
            @php
                $backUrl = in_array($proyek->status, ['Menunggu', 'Penawaran'])
                    ? route('marketing.potensi')
                    : route('marketing.proyek');
            @endphp
            <a href="{{ $backUrl }}"
               class="flex-shrink-0 w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center hover:bg-white/30 transition">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div class="flex-shrink-0 w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-comments"></i>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-base font-bold leading-tight">{{ $proyek->kode_proyek }}</h1>
                </div>
                <p class="text-red-200 text-xs truncate">
                    {{ $proyek->instansi ?? '-' }}@if($proyek->kab_kota) · {{ $proyek->kab_kota }}@endif
                </p>
            </div>
            <div class="flex-shrink-0 text-xs flex items-center gap-1">
                <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $statusColor }}">
                    {{ ucfirst($proyek->status ?? '-') }}
                </span>
            </div>
        </div>
    </div>

    {{-- ─── Alert error ─────────────────────────────────────────────────────── --}}
    @if($errors->any())
    <div class="px-4 py-2 bg-red-50 border-b border-red-200 text-red-700 text-sm flex-shrink-0">
        <i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first() }}
    </div>
    @endif

    {{-- ─── Area Bubble (scrollable) ──────────────────────────────────────── --}}
    <div id="chatMessages"
         class="flex-1 overflow-y-auto p-4 space-y-1"
         style="background: #f0f2f5;">

        @php $lastDate = null; @endphp

        @forelse($chats as $chat)
            @php
                $chatDate  = \Carbon\Carbon::parse($chat['created_at']);
                $today     = \Carbon\Carbon::today();
                $yesterday = \Carbon\Carbon::yesterday();
                if ($chatDate->isSameDay($today))         $dateLabel = 'Hari ini';
                elseif ($chatDate->isSameDay($yesterday)) $dateLabel = 'Kemarin';
                else $dateLabel = $chatDate->locale('id')->isoFormat('D MMMM YYYY');
                $showDate = $lastDate !== $dateLabel;
                $lastDate = $dateLabel;
            @endphp

            {{-- Separator tanggal --}}
            @if($showDate)
            <div class="flex items-center justify-center py-2">
                <span class="bg-white text-gray-500 text-xs px-3 py-1 rounded-full shadow-sm border border-gray-200">
                    {{ $dateLabel }}
                </span>
            </div>
            @endif

            {{-- Bubble --}}
            <div class="flex items-end gap-2 {{ $chat['is_mine'] ? 'flex-row-reverse' : 'flex-row' }} group"
                 id="chat-{{ $chat['id'] }}">

                {{-- Avatar --}}
                @if(!$chat['is_mine'])
                <div class="flex-shrink-0 w-8 h-8 rounded-full overflow-hidden self-end mb-1">
                    @if($chat['user']['foto'])
                        <img src="{{ $chat['user']['foto'] }}" alt="{{ $chat['user']['nama'] }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full {{ $chat['user']['warna'] }} flex items-center justify-center">
                            <span class="text-white text-xs font-bold">{{ $chat['user']['inisial'] }}</span>
                        </div>
                    @endif
                </div>
                @endif

                {{-- Konten bubble --}}
                <div class="flex flex-col {{ $chat['is_mine'] ? 'items-end' : 'items-start' }} max-w-xs sm:max-w-md lg:max-w-lg">

                    @if(!$chat['is_mine'])
                    <span class="text-xs font-semibold text-gray-500 px-1 mb-0.5">{{ $chat['user']['nama'] }}</span>
                    @endif

                    <div class="relative">
                        <div class="rounded-2xl px-3 py-2 shadow-sm
                            {{ $chat['is_mine']
                                ? 'bg-red-600 text-white rounded-br-sm'
                                : 'bg-white text-gray-800 rounded-bl-sm border border-gray-100' }}">

                            {{-- Reply preview --}}
                            @if($chat['reply_to'])
                            <div class="mb-2 pl-2 border-l-2 {{ $chat['is_mine'] ? 'border-red-300' : 'border-gray-400' }} rounded text-xs opacity-80">
                                <p class="font-semibold">{{ $chat['reply_to']['user'] }}</p>
                                <p class="truncate max-w-xs">
                                    {{ $chat['reply_to']['message'] ?? ($chat['reply_to']['file'] ? '📎 ' . $chat['reply_to']['file'] : '...') }}
                                </p>
                            </div>
                            @endif

                            {{-- Teks --}}
                            @if($chat['message'])
                            <p class="text-sm whitespace-pre-wrap break-words leading-relaxed">{{ $chat['message'] }}</p>
                            @endif

                            {{-- Files --}}
                            @if(!empty($chat['files']))
                            <div class="{{ $chat['message'] ? 'mt-2' : '' }} space-y-1.5">
                                @foreach($chat['files'] as $file)
                                    @if($file['is_image'])
                                    <div class="rounded-xl overflow-hidden cursor-pointer max-w-[220px]"
                                         onclick="previewGambar('{{ $file['url'] }}', '{{ addslashes($file['file_name']) }}')">
                                        <img src="{{ $file['url'] }}" alt="{{ $file['file_name'] }}"
                                             class="w-full max-h-48 object-cover rounded-xl">
                                    </div>
                                    @else
                                    <a href="{{ route('chat.file.download', $file['id']) }}"
                                       class="flex items-center gap-2 p-2 rounded-xl transition
                                              {{ $chat['is_mine'] ? 'bg-red-700 hover:bg-red-800' : 'bg-gray-50 hover:bg-gray-100 border border-gray-200' }}">
                                        <div class="w-8 h-8 rounded-lg {{ $chat['is_mine'] ? 'bg-red-500' : 'bg-gray-200' }} flex items-center justify-center flex-shrink-0">
                                            @php
                                                $icon = match($file['ext']) {
                                                    'pdf'             => 'fa-file-pdf text-red-500',
                                                    'doc','docx'      => 'fa-file-word text-blue-500',
                                                    'xls','xlsx'      => 'fa-file-excel text-green-500',
                                                    'zip','rar','7z'  => 'fa-file-archive text-yellow-500',
                                                    'mp4','mov','avi' => 'fa-file-video text-purple-500',
                                                    default           => 'fa-file text-gray-500',
                                                };
                                            @endphp
                                            <i class="fas {{ $icon }} text-sm"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-medium truncate {{ $chat['is_mine'] ? 'text-white' : 'text-gray-800' }}">
                                                {{ $file['file_name'] }}
                                            </p>
                                            <p class="text-xs {{ $chat['is_mine'] ? 'text-red-200' : 'text-gray-400' }}">
                                                {{ $file['file_size'] }}
                                            </p>
                                        </div>
                                        <i class="fas fa-download text-xs {{ $chat['is_mine'] ? 'text-red-200' : 'text-gray-400' }} flex-shrink-0"></i>
                                    </a>
                                    @endif
                                @endforeach
                            </div>
                            @endif

                            {{-- Timestamp --}}
                            <p class="text-xs mt-1 {{ $chat['is_mine'] ? 'text-red-200 text-right' : 'text-gray-400' }}">
                                {{ \Carbon\Carbon::parse($chat['created_at'])->format('H:i') }}
                            </p>
                        </div>

                        {{-- Tombol reply --}}
                        <button onclick="setReply({{ $chat['id'] }}, '{{ addslashes($chat['user']['nama']) }}', '{{ addslashes(\Illuminate\Support\Str::limit($chat['message'] ?? ($chat['files'][0]['file_name'] ?? ''), 50)) }}')"
                                class="absolute top-2
                                       {{ $chat['is_mine'] ? 'right-full mr-1' : 'left-full ml-1' }}
                                       opacity-0 group-hover:opacity-100 transition-opacity
                                       w-7 h-7 bg-white rounded-full shadow flex items-center justify-center
                                       text-gray-400 hover:text-gray-700"
                                title="Balas">
                            <i class="fas fa-reply text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center h-full text-center py-20">
                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-comments text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500 font-medium">Belum ada pesan</p>
                <p class="text-gray-400 text-sm mt-1">Mulai percakapan untuk proyek ini</p>
            </div>
        @endforelse
    </div>

    {{-- ─── Footer Input (sticky bawah) ───────────────────────────────────── --}}
    <div class="bg-white border-t border-gray-200 flex-shrink-0">

        {{-- Reply preview --}}
        <div id="replyPreview" class="hidden px-4 pt-2">
            <div class="flex items-center gap-2 bg-gray-50 border-l-4 border-red-500 rounded-r-lg px-3 py-2">
                <div class="flex-1 min-w-0">
                    <p id="replyUser" class="text-xs font-semibold text-red-600"></p>
                    <p id="replyText" class="text-xs text-gray-500 truncate"></p>
                </div>
                <button onclick="clearReply()" class="text-gray-400 hover:text-gray-600 flex-shrink-0">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>

        {{-- File preview --}}
        <div id="filePreviewArea" class="hidden px-4 pt-2">
            <div id="filePreviewList" class="flex flex-wrap gap-2"></div>
        </div>

        {{-- ── Mention dropdown (posisi di atas textarea) ────────────────── --}}
        <div id="mentionDropdown"
             class="hidden absolute z-50 bg-white border border-gray-200 rounded-xl shadow-lg
                    overflow-hidden w-64 max-h-48 overflow-y-auto"
             style="bottom: 0; left: 0;">
            {{-- Diisi via JS --}}
        </div>

        {{-- Form kirim --}}
        {{-- Wrapper relatif agar dropdown bisa diposisikan relatif terhadap area input --}}
        <div class="relative" id="chatInputWrapper">

            <form id="chatForm"
                  action="{{ route('chat.proyek.send', $proyek->id_proyek) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="flex items-end gap-2 px-3 py-3">
                @csrf
                <input type="hidden" name="reply_to_id" id="replyToId" value="">

                <label for="fileInput"
                       class="flex-shrink-0 w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full
                              flex items-center justify-center cursor-pointer transition"
                       title="Lampirkan file">
                    <i class="fas fa-paperclip text-gray-500"></i>
                </label>
                <input type="file" id="fileInput" name="files[]" multiple class="hidden"
                       onchange="handleFileSelect(this)">

                <div class="flex-1 bg-gray-100 rounded-2xl px-4 py-2.5">
                    <textarea id="messageInput" name="message"
                              placeholder="Tulis pesan... (ketik @ untuk mention)"
                              rows="1"
                              class="w-full bg-transparent resize-none outline-none text-sm text-gray-800
                                     placeholder-gray-400 max-h-32 leading-relaxed"
                              oninput="autoResize(this); handleMentionInput(this)"
                              onkeydown="handleKeyDown(event)"></textarea>
                </div>

                <button type="button"
                        onclick="submitChat()"
                        class="flex-shrink-0 w-10 h-10 bg-red-600 hover:bg-red-700 rounded-full
                               flex items-center justify-center transition shadow">
                    <i class="fas fa-paper-plane text-white text-sm"></i>
                </button>
            </form>
        </div>
    </div>

</div>{{-- end wrapper --}}

{{-- ─── Modal Preview Gambar ───────────────────────────────────────────────── --}}
<div id="imageModal" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4"
     onclick="this.classList.add('hidden')">
    <div class="relative max-w-4xl max-h-full" onclick="event.stopPropagation()">
        <img id="previewImg" src="" alt="" class="max-w-full max-h-[85vh] object-contain rounded-xl">
        <p id="previewImgName" class="text-white text-center text-xs mt-2 opacity-60"></p>
        <button onclick="document.getElementById('imageModal').classList.add('hidden')"
                class="absolute -top-3 -right-3 w-8 h-8 bg-white rounded-full shadow
                       flex items-center justify-center text-gray-700 hover:text-gray-900">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ─────────────────────────────────────────────────────────────────────────────
// SCROLL & ANCHOR
// ─────────────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    // Jika ada anchor #chat-xxx di URL, scroll ke elemen tersebut
    const hash = window.location.hash;
    if (hash && hash.startsWith('#chat-')) {
        const el = document.querySelector(hash);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            // Highlight sebentar
            el.classList.add('ring-2', 'ring-red-400', 'ring-offset-1', 'rounded-2xl');
            setTimeout(() => el.classList.remove('ring-2', 'ring-red-400', 'ring-offset-1', 'rounded-2xl'), 2500);
            return;
        }
    }
    scrollToBottom();
});

let isSubmitting = false;

function scrollToBottom() {
    const el = document.getElementById('chatMessages');
    if (el) el.scrollTop = el.scrollHeight;
}

// ─────────────────────────────────────────────────────────────────────────────
// TEXTAREA HELPERS
// ─────────────────────────────────────────────────────────────────────────────
function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 128) + 'px';
}

// ─────────────────────────────────────────────────────────────────────────────
// MENTION AUTOCOMPLETE
// ─────────────────────────────────────────────────────────────────────────────
const mentionState = {
    active:        false,   // dropdown sedang terbuka?
    query:         '',      // teks setelah @
    startIndex:    -1,      // posisi @ di textarea
    users:         [],      // hasil fetch
    selectedIndex: -1,      // indeks item yang di-highlight
    fetchTimer:    null,    // debounce timer
};

const MENTION_URL = '{{ route('chat.users.search') }}';

/**
 * Dipanggil setiap kali isi textarea berubah (oninput).
 * Mendeteksi apakah kursor sedang setelah @ yang valid.
 */
function handleMentionInput(textarea) {
    const val    = textarea.value;
    const cursor = textarea.selectionStart;

    // Cari @ terakhir sebelum kursor
    const beforeCursor = val.slice(0, cursor);
    // @ valid: di awal string atau setelah whitespace
    const match = beforeCursor.match(/(?:^|(?<=\s))@(\S*)$/);

    if (!match) {
        closeMentionDropdown();
        return;
    }

    const query      = match[1];           // teks setelah @
    const atPosition = beforeCursor.lastIndexOf('@');

    mentionState.startIndex = atPosition;
    mentionState.query      = query;
    mentionState.active     = true;

    // Debounce fetch 200ms
    clearTimeout(mentionState.fetchTimer);
    mentionState.fetchTimer = setTimeout(() => fetchMentionUsers(query), 200);
}

async function fetchMentionUsers(query) {
    try {
        const res   = await fetch(MENTION_URL + '?q=' + encodeURIComponent(query));
        const users = await res.json();
        mentionState.users         = users;
        mentionState.selectedIndex = users.length > 0 ? 0 : -1;
        renderMentionDropdown();
    } catch (e) {
        // Jangan lempar error ke console — tutup saja dropdown
        closeMentionDropdown();
    }
}

function renderMentionDropdown() {
    const dropdown = document.getElementById('mentionDropdown');
    const wrapper  = document.getElementById('chatInputWrapper');
    const textarea = document.getElementById('messageInput');

    if (!mentionState.users.length) {
        closeMentionDropdown();
        return;
    }

    dropdown.innerHTML = '';

    mentionState.users.forEach((user, idx) => {
        const item = document.createElement('button');
        item.type  = 'button';
        item.className = [
            'flex items-center gap-2 w-full px-3 py-2 text-left',
            'hover:bg-gray-50 transition text-sm',
            idx === mentionState.selectedIndex ? 'bg-red-50' : '',
        ].join(' ');

        // Avatar
        const avatarWrap = document.createElement('div');
        avatarWrap.className = 'w-7 h-7 rounded-full overflow-hidden flex-shrink-0 bg-gray-200 flex items-center justify-center';
        if (user.foto) {
            const img  = document.createElement('img');
            img.src    = user.foto;
            img.alt    = user.nama;
            img.className = 'w-full h-full object-cover';
            avatarWrap.appendChild(img);
        } else {
            const span = document.createElement('span');
            span.className   = 'text-xs font-bold text-gray-500';
            span.textContent = user.nama.charAt(0).toUpperCase();
            avatarWrap.appendChild(span);
        }

        const info = document.createElement('div');
        info.className = 'flex-1 min-w-0';
        info.innerHTML = '<p class="font-medium text-gray-800 truncate">' + escapeHtml(user.nama) + '</p>'
                       + '<p class="text-xs text-gray-400 truncate">@' + escapeHtml(user.username) + '</p>';

        item.appendChild(avatarWrap);
        item.appendChild(info);

        item.addEventListener('mousedown', (e) => {
            // mousedown bukan click agar blur textarea tidak menutup dulu
            e.preventDefault();
            insertMention(user.nama);
        });

        dropdown.appendChild(item);
    });

    // Posisikan dropdown di atas textarea
    const textareaRect = textarea.getBoundingClientRect();
    const wrapperRect  = wrapper.getBoundingClientRect();
    const dropdownH    = Math.min(mentionState.users.length * 52, 192); // estimasi tinggi

    dropdown.style.bottom = (wrapperRect.bottom - textareaRect.top + 4) + 'px';
    dropdown.style.left   = '60px'; // sejajar dengan textarea (setelah icon clip)
    dropdown.style.width  = (textareaRect.width) + 'px';

    dropdown.classList.remove('hidden');
}

/**
 * Sisipkan @Nama Lengkap  di posisi @ hingga cursor.
 */
function insertMention(nama) {
    const textarea = document.getElementById('messageInput');
    const val      = textarea.value;
    const before   = val.slice(0, mentionState.startIndex);
    const after    = val.slice(textarea.selectionStart);

    // Sisipkan @Nama + spasi
    textarea.value = before + '@' + nama + ' ' + after;

    // Pindahkan kursor ke setelah mention
    const newCursor = before.length + nama.length + 2; // +2: @ dan spasi
    textarea.setSelectionRange(newCursor, newCursor);
    textarea.focus();

    autoResize(textarea);
    closeMentionDropdown();
}

function closeMentionDropdown() {
    mentionState.active        = false;
    mentionState.users         = [];
    mentionState.selectedIndex = -1;
    mentionState.startIndex    = -1;
    const dropdown = document.getElementById('mentionDropdown');
    if (dropdown) {
        dropdown.classList.add('hidden');
        dropdown.innerHTML = '';
    }
}

function escapeHtml(str) {
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

// ─────────────────────────────────────────────────────────────────────────────
// KEYBOARD HANDLER — mention-aware
// ─────────────────────────────────────────────────────────────────────────────
function handleKeyDown(e) {
    // Jika dropdown mention terbuka, intercept navigasi
    if (mentionState.active && mentionState.users.length > 0) {
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            mentionState.selectedIndex =
                (mentionState.selectedIndex + 1) % mentionState.users.length;
            renderMentionDropdown();
            return;
        }
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            mentionState.selectedIndex =
                (mentionState.selectedIndex - 1 + mentionState.users.length) % mentionState.users.length;
            renderMentionDropdown();
            return;
        }
        if (e.key === 'Enter') {
            e.preventDefault();
            if (mentionState.selectedIndex >= 0 && mentionState.users[mentionState.selectedIndex]) {
                insertMention(mentionState.users[mentionState.selectedIndex].nama);
            }
            return;
        }
        if (e.key === 'Escape') {
            e.preventDefault();
            closeMentionDropdown();
            return;
        }
    }

    // Dropdown tertutup: Enter (tanpa Shift) kirim chat
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        submitChat();
    }
}

// Tutup dropdown jika klik di luar
document.addEventListener('click', function (e) {
    const dropdown = document.getElementById('mentionDropdown');
    const textarea = document.getElementById('messageInput');
    if (dropdown && !dropdown.contains(e.target) && e.target !== textarea) {
        closeMentionDropdown();
    }
});

// ─────────────────────────────────────────────────────────────────────────────
// SUBMIT
// ─────────────────────────────────────────────────────────────────────────────
function submitChat() {
    if (isSubmitting) return;
    const form  = document.getElementById('chatForm');
    const msg   = document.getElementById('messageInput').value.trim();
    const files = document.getElementById('fileInput').files;
    if (!msg && files.length === 0) return;

    isSubmitting = true;
    form.submit();
}

// ─────────────────────────────────────────────────────────────────────────────
// REPLY
// ─────────────────────────────────────────────────────────────────────────────
function setReply(id, user, text) {
    document.getElementById('replyToId').value       = id;
    document.getElementById('replyUser').textContent = user;
    document.getElementById('replyText').textContent = text || '📎 File';
    document.getElementById('replyPreview').classList.remove('hidden');
    document.getElementById('messageInput').focus();
}

function clearReply() {
    document.getElementById('replyToId').value = '';
    document.getElementById('replyPreview').classList.add('hidden');
}

// ─────────────────────────────────────────────────────────────────────────────
// FILE PREVIEW
// ─────────────────────────────────────────────────────────────────────────────
function handleFileSelect(input) {
    const list  = document.getElementById('filePreviewList');
    const area  = document.getElementById('filePreviewArea');
    const files = Array.from(input.files);

    list.innerHTML = '';
    if (!files.length) { area.classList.add('hidden'); return; }
    area.classList.remove('hidden');

    files.forEach(file => {
        const isImage = file.type.startsWith('image/');
        const size    = file.size >= 1048576
            ? (file.size / 1048576).toFixed(1) + ' MB'
            : Math.round(file.size / 1024) + ' KB';

        const chip = document.createElement('div');
        chip.className = 'flex items-center gap-1.5 bg-gray-100 rounded-lg px-2 py-1 text-xs text-gray-700 max-w-[160px]';

        if (isImage) {
            const thumb = document.createElement('img');
            thumb.src   = URL.createObjectURL(file);
            thumb.className = 'w-6 h-6 rounded object-cover flex-shrink-0';
            chip.appendChild(thumb);
        } else {
            const icon = document.createElement('i');
            icon.className = 'fas fa-file text-gray-400 flex-shrink-0';
            chip.appendChild(icon);
        }

        const name = document.createElement('span');
        name.className   = 'truncate flex-1 min-w-0';
        name.title       = file.name;
        name.textContent = file.name.length > 16 ? file.name.slice(0, 14) + '…' : file.name;
        chip.appendChild(name);

        const sizeEl = document.createElement('span');
        sizeEl.className   = 'text-gray-400 flex-shrink-0';
        sizeEl.textContent = size;
        chip.appendChild(sizeEl);

        list.appendChild(chip);
    });
}

// ─────────────────────────────────────────────────────────────────────────────
// IMAGE PREVIEW MODAL
// ─────────────────────────────────────────────────────────────────────────────
function previewGambar(url, name) {
    document.getElementById('previewImg').src             = url;
    document.getElementById('previewImgName').textContent = name;
    document.getElementById('imageModal').classList.remove('hidden');
}
</script>
@endpush