<?php
use Livewire\Volt\Component;
use App\Models\ActiveSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public $sessionToTerminate = null;

    public function terminateSession($sessionId)
    {
        try {
            DB::beginTransaction();

            $session = ActiveSession::find($sessionId);
            if ($session) {
                $tokenToDelete = $session->token;
                $session->delete();

                if (config('session.driver') === 'database') {
                    DB::table('sessions')->where('id', $tokenToDelete)->delete();
                }

                cache()->put("forced_logout_{$session->user_id}_{$tokenToDelete}", now(), now()->addMinutes(5));

                DB::commit();
                
                $this->dispatch('swal', [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Sesi berhasil dihentikan!'
                ]);
                
                $this->dispatch('refreshDatatable');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Gagal menghentikan sesi!'
            ]);
        }
        
        $this->sessionToTerminate = null;
    }
}; ?>

<div>
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-body">
                <table id="sessionsTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Perangkat</th>
                            <th>Status</th>
                            <th>URL Terakhir</th>
                            <th>Aktif Terakhir</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal inside the root div -->
    <div class="modal fade" id="terminateSessionModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="terminateSessionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="terminateSessionModalLabel">Konfirmasi Terminasi Sesi</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah anda yakin ingin menghentikan sesi user ini?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-danger" type="button" wire:click="terminateSession({{ $sessionToTerminate }})" data-bs-dismiss="modal">Terminate</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:initialized', function() {
    let table = $('#sessionsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '{{ route("datatables.active-sessions") }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        },
        columns: [
            { 
                data: 'user',
                render: function(data) {
                    return `
                        <div class="d-flex align-items-center">
                            <div class="avatar me-2">
                                <img class="avatar-img img-fluid" src="/assets/img/avatar/${data.avatar}" alt="${data.name}" />
                            </div>
                            ${data.name}
                        </div>
                    `;
                }
            },
            { 
                data: 'user',
                render: function(data) {
                    const badgeClass = {
                        'admin': 'bg-danger',
                        'tutor': 'bg-purple',
                        'user': 'bg-blue'
                    }[data.role] || 'bg-blue';
                    
                    const roleText = data.role.charAt(0).toUpperCase() + data.role.slice(1);
                    return `<span class="badge ${badgeClass}">${roleText}</span>`;
                }
            },
            { 
                data: 'device_info',
                render: function(data) {
                    const deviceIcon = {
                        'windows': 'fa-windows',
                        'mac': 'fa-apple',
                        'iphone': 'fa-mobile',
                        'ios': 'fa-mobile',
                        'android': 'fa-android',
                        'linux': 'fa-linux'
                    }[data.platform.toLowerCase()] || 'fa-desktop';
                    
                    return `
                        <div>
                            <i class="fas ${deviceIcon} me-2"></i>
                            ${data.platform}
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-globe me-1"></i>
                            ${data.browser}
                        </small>
                    `;
                }
            },
            { 
                data: 'token',
                render: function(data) {
                    return data === '{{ session()->getId() }}' 
                        ? '<span class="badge bg-success">Perangkat Saat Ini</span>'
                        : '<span class="badge bg-success">Aktif</span>';
                }
            },
            { 
                data: 'last_url',
                render: function(data) {
                    return `
                        <div class="text-wrap" style="max-width: 250px;">
                            <small class="text-sm">${data || '-'}</small>
                        </div>
                    `;
                }
            },
            { 
                data: 'last_active_at',
                render: function(data) {
                    return `
                        ${data.formatted}
                        <br>
                        <small class="text-muted">${data.diffForHumans}</small>
                    `;
                }
            },
            {
                data: 'id',
                orderable: false,
                render: function(data, type, row) {
                    return row.token !== '{{ session()->getId() }}' ? `
                        <button class="btn btn-sm btn-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#terminateSessionModal" 
                                onclick="Livewire.dispatch('set-session-to-terminate', { id: '${data}' })">
                            <i class="fas fa-sign-out-alt me-1"></i>
                            Terminasi
                        </button>
                    ` : '';
                }
            }
        ],
        order: [[5, 'desc']],
        language: {
            processing: 'Loading...',
            search: 'Cari:',
            searchPlaceholder: 'Cari data...',
            paginate: {
                previous: 'Sebelumnya',
                next: 'Selanjutnya',
            },
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Tidak ada data yang ditemukan",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)"
        }
    });

    // Refresh DataTable when event is dispatched
    Livewire.on('refreshDatatable', () => {
        table.ajax.reload();
    });
});
</script>