<?php

namespace App\Http\Controllers;

use App\Models\ActiveSession;
use App\Models\ClassContent;
use App\Models\TransaksiUser;
use App\Models\User;
use App\Models\RefUniversitasList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\DataTablesTrait;

class DatatablesController extends Controller
{
    use DataTablesTrait;

    public function activeSessions(Request $request)
    {
        $params = $this->getBaseQuery($request);

        $query = ActiveSession::with('user')
            ->select('active_sessions.id', 'user_id', 'token', 'device_name', 'last_url', 'last_active_at', 'created_at');

        // Define searchable columns including relations
        $searchableColumns = [
            'device_name',
            'last_url',
            'user.name'  // Search in related user's name
        ];

        // Define orderable columns
        $orderableColumns = [
            'device_name',
            'last_url',
            'last_active_at',
            'user'  // For ordering by user name
        ];

        // Define relation columns for ordering
        $relationColumns = [
            'user' => 'users.name'  // Maps 'user' column to users.name for ordering
        ];

        // Apply search
        $query = $this->applySearch($query, $params['search'], $searchableColumns);

        // Apply order
        $query = $this->applyOrder($query, $params['order'], $params['columns'], $orderableColumns, $relationColumns);

        $results = $query->skip($params['start'])
            ->take($params['length'])
            ->get();

        return $this->formatResponse(
            $params['draw'],
            $query,
            $results,
            fn($session) => [
                'user' => [
                    'name' => $session->user->name,
                    'avatar' => $session->user->avatar,
                    'role' => $session->user->role
                ],
                'device_info' => $session->getDeviceInfo(),
                'token' => $session->token,
                'last_url' => $session->last_url,
                'last_active_at' => [
                    'formatted' => Carbon::parse($session->last_active_at)->translatedFormat('d F Y H:i'),
                    'diffForHumans' => Carbon::parse($session->last_active_at)->diffForHumans()
                ],
                'id' => $session->id
            ]
        );
    }

    public function users(Request $request)
    {
        try {
            $params = $this->getBaseQuery($request);

            $query = User::with('universitas')
                ->select('users.id', 'name', 'email', 'avatar', 'universitas_id', 'role', 'active_status', 'created_at');

            // Apply filters
            if ($request->filled('roleFilter')) {
                $query->where('role', $request->roleFilter);
            }

            if ($request->filled('universitasFilter')) {
                $query->where('universitas_id', $request->universitasFilter);
            }

            // Fix the status filter condition
            if ($request->filled('statusFilter')) {
                $query->where('active_status', $request->statusFilter);
            }

            // Apply search
            if (!empty($params['search'])) {
                $query->where(function($q) use ($params) {
                    $q->where('name', 'like', "%{$params['search']}%")
                    ->orWhere('email', 'like', "%{$params['search']}%")
                    ->orWhereHas('universitas', function($q) use ($params) {
                        $q->where('universitas_name', 'like', "%{$params['search']}%");
                    });
                });
            }

            // Apply order
            $orderColumn = $params['order'][0]['column'] ?? 4;
            $orderDir = $params['order'][0]['dir'] ?? 'desc';

            switch($orderColumn) {
                case 0: // name
                    $query->orderBy('name', $orderDir);
                    break;
                case 1: // email
                    $query->orderBy('email', $orderDir);
                    break;
                case 2: // role
                    $query->orderBy('role', $orderDir);
                    break;
                case 3: // universitas
                    $query->leftJoin('ref_universitas_list', 'users.universitas_id', '=', 'ref_universitas_list.id')
                        ->orderBy('ref_universitas_list.universitas_name', $orderDir);
                    break;
                default:
                    $query->orderBy('created_at', $orderDir);
            }

            \Log::info('Final Query:', [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

            $totalRecords = $query->count();

            $results = $query->skip($params['start'])
                ->take($params['length'])
                ->get();

            return response()->json([
                'draw' => (int)$params['draw'],
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $results->map(fn($user) => [
                    'id' => $user->id,
                    'name' => [
                        'full' => $user->name,
                        'avatar' => $user->avatar
                    ],
                    'email' => $user->email,
                    'role' => [
                        'name' => $user->role,
                        'active' => $user->active_status
                    ],
                    'universitas' => $user->role === 'admin' || $user->role === 'tutor'
                        ? '-'
                        : ($user->universitas?->universitas_name ?? 'Belum ada universitas'),
                    'created_at' => Carbon::parse($user->created_at)->translatedFormat('d F Y'),
                    'actions' => [
                        'edit_url' => route('admin.users.edit', $user->id),
                        'view_url' => route('admin.users.edit', $user->id)
                    ]
                ])
            ]);

        } catch (\Exception $e) {
            \Log::error('DataTables Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'An error occurred while fetching data'
            ], 500);
        }
    }

    public function universitas(Request $request)
    {
        try {
            $params = $this->getBaseQuery($request);

            // Remove the select() call since withCount adds a virtual column
            $query = RefUniversitasList::withCount('users');

            // Apply search
            if (!empty($params['search'])) {
                $query->where(function($q) use ($params) {
                    $q->where('universitas_name', 'like', "%{$params['search']}%")
                    ->orWhere('singkatan', 'like', "%{$params['search']}%");
                });
            }

            // Apply order
            $orderColumn = $params['order'][0]['column'] ?? 0;
            $orderDir = $params['order'][0]['dir'] ?? 'asc';

            switch($orderColumn) {
                case 1:
                    $query->orderBy('singkatan', $orderDir);
                    break;
                case 2:
                    $query->orderBy('users_count', $orderDir);
                    break;
                default:
                    $query->orderBy('universitas_name', $orderDir);
            }

            $totalRecords = $query->count();

            $results = $query->skip($params['start'])
                ->take($params['length'])
                ->get();

            // Add debug logging
            \Log::info('Query results:', $results->toArray());

            return response()->json([
                'draw' => (int)$params['draw'],
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $results->map(fn($univ) => [
                    'name' => $univ->universitas_name,
                    'singkatan' => $univ->singkatan,
                    'users_count' => $univ->users_count,
                    'actions' => [
                        'edit_url' => route('admin.universitas.edit', $univ->id),
                        'view_url' => route('admin.universitas.show', $univ->id)
                    ]
                ])
            ]);

        } catch (\Exception $e) {
            \Log::error('DataTables Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'An error occurred while fetching data'
            ], 500);
        }
    }
    public function transactions(Request $request)
    {
        try {
            $params = $this->getBaseQuery($request);

            $query = TransaksiUser::with('paket')
                ->where('user_id', auth()->id())
                ->select('id', 'kode_transaksi', 'tanggal_pembelian', 'status', 'redirect_url', 'paket_id');

            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            if (!empty($params['search'])) {
                $query->where(function($q) use ($params) {
                    $q->where('kode_transaksi', 'like', "%{$params['search']}%")
                    ->orWhereHas('paket', function($q) use ($params) {
                        $q->where('nama_paket', 'like', "%{$params['search']}%");
                    });
                });
            }

            $totalRecords = $query->count();

            // Apply order
            $orderColumn = $params['order'][0]['column'] ?? 2;
            $orderDir = $params['order'][0]['dir'] ?? 'desc';

            if ($orderColumn == 2) {
                $query->orderBy('tanggal_pembelian', $orderDir);
            }

            $results = $query->skip($params['start'])
                ->take($params['length'])
                ->get();

            return response()->json([
                'draw' => (int)$params['draw'],
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $results->map(function($transaksi, $index) use ($params) {
                    return [
                        'DT_RowIndex' => $params['start'] + $index + 1,
                        'kode_transaksi' => $transaksi->kode_transaksi,
                        'tanggal_pembelian' => $transaksi->tanggal_pembelian->format('Y-m-d'),
                        'nama_paket' => $transaksi->paket->nama_paket ?? '-',
                        'harga_paket' => $transaksi->paket->harga ? 'Rp ' . number_format($transaksi->paket->harga, 0, ',', '.') : '-',
                        'status' => $transaksi->status,
                        'action' => $this->getActionButton($transaksi)
                    ];
                })
            ]);

        } catch (\Exception $e) {
            \Log::error('DataTables Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'An error occurred while fetching data'
            ], 500);
        }
    }

    public function materi(Request $request){
        try {
            $params = $this->getBaseQuery($request);

            // Remove the select() call since withCount adds a virtual column
            $query = ClassContent::query();

            // Apply search
            if (!empty($params['search'])) {
                $query->where(function($q) use ($params) {
                    $q->where('nama_materi', 'like', "%{$params['search']}%")
                        ->orWhere('nama_submateri', 'like', "%{$params['search']}%");
                });
            }

            // Apply order
            $orderColumn = $params['order'][0]['column'] ?? 0;
            $orderDir = $params['order'][0]['dir'] ?? 'asc';

            switch($orderColumn) {
                case 1:
                    $query->orderBy('nama_materi', $orderDir);
                    break;
                case 2:
                    $query->orderBy('nama_submateri', $orderDir);
                    break;
                default:
                    $query->orderBy('nama_materi', $orderDir);
            }

            $totalRecords = $query->count();

            $results = $query->skip($params['start'])
                ->take($params['length'])
                ->get();

            // Add debug logging
            \Log::info('Query results:', $results->toArray());

            return response()->json([
                'draw' => (int)$params['draw'],
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $results->map(fn($content, $index) => [
                    'id' => $content->id,
                    'DT_RowIndex' => $params['start'] + $index + 1,
                    'kode_materi' => $content->kode_materi,
                    'nama_materi' => $content->nama_materi,
                    'video_url' => $content->video_url,
                    'created_at' => Carbon::parse($content->created_at)->translatedFormat('d F Y'),
                    'actions' => [
                        'edit_url' => route('materi.edit', $content->id),
                        'view_url' => route('materi.show', $content->id)
                    ]
                ])
            ]);

        } catch (\Exception $e) {
            \Log::error('DataTables Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'An error occurred while fetching data'
            ], 500);
        }
    }

    private function getActionButton($transaksi)
    {
        if ($transaksi->status === 'success') {
            $route = ($transaksi->paket->tipe ?? '') == 'tryout' ? '/user/tryout' : '/user/kelas';
            return '<a href="' . $route . '" class="btn btn-sm btn-success">Buka Paket</a>';
        } elseif ($transaksi->status === 'pending' && !empty($transaksi->redirect_url)) {
            return '<a href="' . $transaksi->redirect_url . '" class="btn btn-sm btn-primary">Bayar</a>';
        }
        return '';
    }
}
