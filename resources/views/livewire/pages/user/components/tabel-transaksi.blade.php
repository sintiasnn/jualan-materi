<?php

use App\Models\TransaksiUser;
use Livewire\Volt\Component;

new class extends Component
{
    public $transactions = [];
    public $selectedTab = 'all';

    public function mount()
    {
        $this->loadTransactions();
    }

    public function loadTransactions()
    {
        $userId = auth()->id();
        
        $query = TransaksiUser::with('paket')
            ->where('user_id', $userId)
            ->orderBy('id', 'desc'); // Added ordering by ID descending

        if ($this->selectedTab === 'pending') {
            $query->where('status', 'pending');
        } elseif ($this->selectedTab === 'success') {
            $query->where('status', 'success');
        } elseif ($this->selectedTab === 'cancelled') {
            $query->where('status', 'cancelled');
        }

        $this->transactions = $query->get()->map(function ($transaksi) {
            return [
                'kode_transaksi' => $transaksi->kode_transaksi,
                'tanggal_pembelian' => $transaksi->tanggal_pembelian->format('Y-m-d'),
                'nama_paket' => $transaksi->paket->nama_paket ?? '-',
                'harga_paket' => $transaksi->paket->harga ? 'Rp ' . number_format($transaksi->paket->harga, 0, ',', '.') : '-',
                'status' => match ($transaksi->status) {
                    'pending' => '<span class="badge bg-warning text-dark">Belum Dibayar</span>',
                    'success' => '<span class="badge bg-success">Sukses</span>',
                    'cancelled' => '<span class="badge bg-danger">Batal</span>',
                    'failed' => '<span class="badge bg-danger">Gagal</span>',
                    default => '-',
                },
                'aksi' => $this->getActionButton($transaksi),
            ];
        });
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

    public function switchTab($tab)
    {
        $this->selectedTab = $tab;
        $this->loadTransactions();
    }
};
?>

<div class="container-xl px-4 mt-n10">
    <div class="card shadow-sm">
        <div class="card-header border-bottom">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ $selectedTab === 'all' ? 'active' : '' }}" 
                       wire:click="switchTab('all')" href="#all" data-bs-toggle="tab">Semua Transaksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $selectedTab === 'pending' ? 'active' : '' }}" 
                       wire:click="switchTab('pending')" href="#pending" data-bs-toggle="tab">Belum Dibayar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $selectedTab === 'success' ? 'active' : '' }}" 
                       wire:click="switchTab('success')" href="#success" data-bs-toggle="tab">Transaksi Selesai</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $selectedTab === 'cancelled' ? 'active' : '' }}" 
                       wire:click="switchTab('cancelled')" href="#cancelled" data-bs-toggle="tab">Transaksi Batal</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <table id="allTransactions" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Transaksi</th>
                        <th>Tanggal</th>
                        <th>Nama Paket</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $index => $transaction)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $transaction['kode_transaksi'] }}</td>
                            <td>{{ $transaction['tanggal_pembelian'] }}</td>
                            <td>{{ $transaction['nama_paket'] }}</td>
                            <td>{{ $transaction['harga_paket'] }}</td>
                            <td>{!! $transaction['status'] !!}</td>
                            <td>{!! $transaction['aksi'] !!}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada transaksi yang tersedia</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>            
        </div>
    </div>
</div>