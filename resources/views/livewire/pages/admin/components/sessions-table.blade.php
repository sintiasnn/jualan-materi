<?php
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\ActiveSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

new class extends Component {
    use WithPagination;
    
    public $sessionToTerminate = null;

    public function with(): array
    {
        return [
            'sessions' => ActiveSession::with('user')
                ->select('id', 'user_id', 'token', 'device_name', 'last_url', 'last_active_at', 'created_at')
                ->orderBy('last_active_at', 'desc')
                ->get()
        ];
    }

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
                
                // Changed to use 'swal'
                $this->dispatch('swal', [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'Sesi berhasil dihentikan!'
                ]);
                
                // Refresh the DataTable
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

<main>
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-body">
                <table id="sessionsTable" class="table table-striped table-bordered">
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
                    <tbody>
                        @foreach($sessions as $session)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2">
                                        <img class="avatar-img img-fluid" src="{{ asset('assets/img/avatar/' . $session->user->avatar) }}" alt="{{ $session->user->name }}" />
                                    </div>
                                    {{ $session->user->name }}
                                </div>
                            </td>
                            <td>
                                @switch($session->user->role)
                                    @case('admin')
                                        <span class="badge bg-danger">Admin</span>
                                        @break
                                    @case('tutor')
                                        <span class="badge bg-purple">Tutor</span>
                                        @break
                                    @default
                                        <span class="badge bg-blue">User</span>
                                @endswitch
                            </td>
                            <td>
                                @php
                                    $deviceInfo = $session->getDeviceInfo();
                                @endphp
                                <div>{{ $deviceInfo['platform'] }}</div>
                                <small class="text-muted">{{ $deviceInfo['browser'] }}</small>
                            </td>
                            <td>
                                @if($session->token === session()->getId())
                                    <span class="badge bg-success">Perangkat Saat Ini</span>
                                @else
                                    <span class="badge bg-success">Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-wrap" style="max-width: 250px;">
                                    @if($session->last_url)
                                        <small class="text-sm">{{ $session->last_url }}</small>
                                    @else
                                        <small class="text-sm">-</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                {{ Carbon::parse($session->last_active_at)->translatedFormat('d F Y H:i') }}
                                <br>
                                <small class="text-muted">{{ Carbon::parse($session->last_active_at)->diffForHumans() }}</small>
                            </td>
                            <td>
                                @if($session->token !== session()->getId())
                                    <button class="btn btn-sm btn-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#terminateSessionModal" 
                                            wire:click="$set('sessionToTerminate', '{{ $session->id }}')">
                                        Terminate
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Terminate Session Modal -->
    <div class="modal fade" id="terminateSessionModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="terminateSessionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="terminateSessionModalLabel">Konfirmasi Terminate Session</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah anda yakin ingin menghentikan sesi ini?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-danger" type="button" wire:click="terminateSession({{ $sessionToTerminate }})" data-bs-dismiss="modal">Terminate</button>
                </div>
            </div>
        </div>
    </div>
</main>