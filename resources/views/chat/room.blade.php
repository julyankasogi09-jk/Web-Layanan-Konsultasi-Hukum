@extends('layouts.app')

@section('title', 'Ruang Konsultasi Online - ' . $formulir->no_permohonan)
@section('page-title', 'Konsultasi Daring #' . $formulir->no_permohonan)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <!-- Header Chat -->
            <div class="p-3 text-white d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div style="background: #c9a84c; border-radius: 50%; width:44px; height:44px; display:flex; align-items:center; justify-content:center; font-weight:bold; color:#0f172a;">
                        <i class="fas fa-comments fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Ruang Konsultasi Hukum: {{ $formulir->no_permohonan }}</h6>
                        <small class="text-light opacity-75">
                            Klien: <strong>{{ $formulir->klien->nama_lengkap }}</strong> | 
                            Advokat: <strong>{{ $formulir->pengacara->nama_lengkap ?? 'Sekretaris Kantor' }}</strong>
                        </small>
                    </div>
                </div>
                <div>
                    @if(auth()->user()->isKlien())
                        <a href="{{ route('klien.detail', $formulir->id) }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Kasus
                        </a>
                    @elseif(auth()->user()->isAdmin())
                        <a href="{{ route('admin.perkara.detail', $formulir->id) }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Perkara
                        </a>
                    @else
                        <a href="{{ route('pengacara.detail', $formulir->id) }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Perkara
                        </a>
                    @endif
                </div>
            </div>

            <!-- Box Pesan -->
            <div id="chatBox" class="p-4 bg-light" style="height: 480px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px;">
                @forelse($pesanList as $msg)
                    @php $isMe = ($msg->pengirim_id === auth()->id()); @endphp
                    <div class="d-flex flex-column {{ $isMe ? 'align-items-end' : 'align-items-start' }}" data-id="{{ $msg->id }}">
                        <div class="d-flex align-items-baseline gap-2 mb-1">
                            @if(!$isMe)
                                <span class="badge bg-secondary" style="font-size: 10px;">{{ $msg->pengirim->nama_lengkap }} ({{ ucfirst($msg->pengirim->role->nama_role) }})</span>
                            @endif
                            <small class="text-muted" style="font-size: 10.5px;">{{ $msg->created_at->format('H:i') }}</small>
                        </div>
                        <div class="p-3 rounded-3 shadow-sm text-break" style="max-width: 75%; font-size: 13.5px; line-height: 1.5; {{ $isMe ? 'background: #0f172a; color: #fff;' : 'background: #ffffff; color: #1e293b; border: 1px solid #e2e8f0;' }}">
                            {{ $msg->pesan }}
                        </div>
                    </div>
                @empty
                    <div id="emptyNotice" class="text-center py-5 text-muted">
                        <i class="fas fa-comments fs-1 mb-2 d-block opacity-25"></i>
                        Belum ada pesan. Mulai percakapan konsultasi dengan mengetik pesan di bawah ini.
                    </div>
                @endforelse
            </div>

            <!-- Footer Kirim Pesan -->
            <div class="card-footer bg-white p-3 border-top">
                <form id="chatForm" class="d-flex gap-2">
                    @csrf
                    <input type="text" id="pesanInput" class="form-control" placeholder="Tuliskan pesan konsultasi Anda di sini..." autocomplete="off" required>
                    <button type="submit" id="btnKirim" class="btn btn-primary px-4 fw-semibold" style="background-color: #0f172a; border-color: #0f172a;">
                        <i class="fas fa-paper-plane me-1"></i> Kirim
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chatBox = document.getElementById('chatBox');
    const chatForm = document.getElementById('chatForm');
    const pesanInput = document.getElementById('pesanInput');
    const btnKirim = document.getElementById('btnKirim');
    const emptyNotice = document.getElementById('emptyNotice');
    const formulirId = {{ $formulir->id }};
    const currentUserId = {{ auth()->id() }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function scrollToBottom() {
        chatBox.scrollTop = chatBox.scrollHeight;
    }
    scrollToBottom();

    let lastId = 0;
    const existingMessages = chatBox.querySelectorAll('[data-id]');
    if (existingMessages.length > 0) {
        lastId = parseInt(existingMessages[existingMessages.length - 1].getAttribute('data-id'));
    }

    // Kirim Pesan
    chatForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const text = pesanInput.value.trim();
        if (!text) return;

        btnKirim.disabled = true;
        pesanInput.disabled = true;

        fetch(`{{ url('/chat') }}/${formulirId}/kirim`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ pesan: text })
        })
        .then(response => response.json())
        .then(data => {
            if (data.id) {
                if (emptyNotice) emptyNotice.remove();
                appendMessage(data.id, data.pesan, data.pengirim, data.created_at, true);
                lastId = data.id;
                pesanInput.value = '';
                scrollToBottom();
            }
        })
        .catch(err => console.error('Gagal mengirim pesan:', err))
        .finally(() => {
            btnKirim.disabled = false;
            pesanInput.disabled = false;
            pesanInput.focus();
        });
    });

    // Polling Pesan Baru setiap 3 detik
    function fetchNewMessages() {
        fetch(`{{ url('/chat') }}/${formulirId}/fetch?last_id=${lastId}`)
        .then(res => res.json())
        .then(list => {
            if (list.length > 0) {
                if (emptyNotice) emptyNotice.remove();
                list.forEach(m => {
                    const isMe = (m.pengirim_id === currentUserId);
                    appendMessage(m.id, m.pesan, m.pengirim, m.created_at, isMe);
                    lastId = m.id;
                });
                scrollToBottom();
            }
        })
        .catch(err => console.error('Fetch error:', err));
    }

    setInterval(fetchNewMessages, 3000);

    function appendMessage(id, text, pengirim, waktu, isMe) {
        // Cek apakah pesan sudah ada
        if (document.querySelector(`[data-id="${id}"]`)) return;

        const wrap = document.createElement('div');
        wrap.className = `d-flex flex-column ${isMe ? 'align-items-end' : 'align-items-start'}`;
        wrap.setAttribute('data-id', id);

        const header = document.createElement('div');
        header.className = 'd-flex align-items-baseline gap-2 mb-1';
        if (!isMe) {
            header.innerHTML = `<span class="badge bg-secondary" style="font-size: 10px;">${pengirim}</span>`;
        }
        header.innerHTML += `<small class="text-muted" style="font-size: 10.5px;">${waktu}</small>`;

        const bubble = document.createElement('div');
        bubble.className = 'p-3 rounded-3 shadow-sm text-break';
        bubble.style.maxWidth = '75%';
        bubble.style.fontSize = '13.5px';
        bubble.style.lineHeight = '1.5';
        if (isMe) {
            bubble.style.background = '#0f172a';
            bubble.style.color = '#fff';
        } else {
            bubble.style.background = '#ffffff';
            bubble.style.color = '#1e293b';
            bubble.style.border = '1px solid #e2e8f0';
        }
        bubble.textContent = text;

        wrap.appendChild(header);
        wrap.appendChild(bubble);
        chatBox.appendChild(wrap);
    }
});
</script>
@endpush

