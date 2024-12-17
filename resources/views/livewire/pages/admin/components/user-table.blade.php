<?php
// resources/views/livewire/pages/admin/users/index.blade.php
 
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\User;
use Carbon\Carbon;

new class extends Component {
    use WithPagination;
    
    public function with(): array
    {
        return [
            'users' => User::with('universitas')
                ->select('id', 'name', 'email', 'avatar', 'universitas_id', 'role', 'active_status', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get()
        ];
    }
}; ?>

<main>
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-body">
                <table id="usersTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Universitas</th>
                            <th>Tanggal Bergabung</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2">
                                        <img class="avatar-img img-fluid" src="{{ asset('assets/img/avatar/' . $user->avatar) }}" alt="{{ $user->name }}" />
                                    </div>
                                    {{ $user->name }}
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @switch($user->role)
                                    @case('admin')
                                        <span class="badge bg-danger">Admin</span>
                                        @break
                                    @case('tutor')
                                        <span class="badge bg-purple">Tutor</span>
                                        @break
                                    @default
                                        <span class="badge bg-blue">User</span>
                                @endswitch
                                <span class="badge {{ $user->active_status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->active_status ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td>
                                @if($user->role === 'admin' || $user->role === 'tutor')
                                    -
                                @else
                                    {{ $user->universitas?->universitas_name ?? 'Belum ada universitas' }}
                                @endif
                            </td>
                            <td>{{ Carbon::parse($user->created_at)->translatedFormat('d F Y') }}</td>
                            <td>
                                <a class="btn btn-datatable btn-icon btn-transparent-dark" href="{{ route('admin.users.edit', $user->id) }}">
                                    <i data-feather="edit"></i>
                                </a>
                                <a class="btn btn-datatable btn-icon btn-transparent-dark" href="{{ route('admin.users.edit', $user->id) }}">
                                    <i data-feather="eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>