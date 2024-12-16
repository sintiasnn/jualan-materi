<?php

use App\Models\PaketList;
use App\Models\RedeemCode;
use App\Models\TransaksiUser; // Import TransaksiUser model
use Livewire\Volt\Component;
use App\Http\Controllers\TransactionController;

new class extends Component
{
    public $paket;
    public $promoCode = '';
    public $discount = 0;
    public $total = 0;
    public $promoMessage = ''; // Pesan untuk pengguna
    public $promoMessageClass = 'text-danger'; // Default error class
    public $inputDisabled = false; // Menyimpan status apakah input sudah dinonaktifkan
    public $promoCodeValidated = false; // Status untuk menandakan validasi telah dilakukan

    public function mount($id)
    {
        // Ambil data paket berdasarkan ID
        $this->paket = PaketList::find($id); // Menggunakan find() agar dapat cek null
        
        // Cek jika paket tidak ditemukan
        if (!$this->paket) {
            return redirect()->route('user.beli'); // Redirect ke halaman beli jika paket tidak ditemukan
        }

        // Cek apakah user sudah membeli paket ini
        $userHasPackage = TransaksiUser::where('user_id', auth()->id()) // Pastikan untuk menggunakan ID user yang sedang login
            ->where('paket_id', $this->paket->id) // Cek paket_id yang sama
            ->whereIn('status', ['success', 'pending']) // Status transaksi harus 'success' atau 'pending'
            ->exists(); // Menggunakan exists untuk mengecek apakah ada data yang cocok

        // Jika user sudah membeli paket ini, alihkan ke halaman beli
        if ($userHasPackage) {
            return redirect()->route('user.beli'); // Ubah 'user.beli' sesuai dengan rute yang sesuai di aplikasi Anda
        }

        // Hitung total harga setelah diskon
        $this->total = $this->paket->harga - $this->discount;
    }

    public function applyPromoCode()
    {
        // Menandakan bahwa promoCode sudah divalidasi
        $this->promoCodeValidated = true;

        // Validasi kode promo yang dimasukkan
        if (empty($this->promoCode)) {
            $this->promoMessage = 'Kode promo tidak boleh kosong!';
            $this->promoMessageClass = 'text-danger';
            $this->inputDisabled = false; // Jangan nonaktifkan input jika kosong
            return;
        }

        // Cari kode promo yang aktif di database
        $redeemCode = RedeemCode::where('code', $this->promoCode)
            // ->where('activation_status', '0')  // Pastikan hanya kode yang aktif yang diambil
            ->first();

        // Jika kode promo ditemukan
        if ($redeemCode) {
            // Cek apakah kode promo sudah kadaluarsa
            if ($redeemCode->isExpired()) {
                $this->promoMessage = 'Kode promo sudah kadaluarsa!';
                $this->promoMessageClass = 'text-danger';
                $this->discount = 0;
                $this->inputDisabled = false; // Jangan nonaktifkan input jika kadaluarsa
            } elseif ($redeemCode->hasDiscount()) {
                // Cek apakah kode promo sudah digunakan
                if ($redeemCode->activation_status == 1) {
                    $this->promoMessage = 'Kode promo sudah digunakan!';
                    $this->promoMessageClass = 'text-danger';
                    $this->discount = 0;
                    $this->inputDisabled = false; // Jangan nonaktifkan input jika sudah digunakan
                } elseif ($redeemCode->related_id === null || $redeemCode->related_id == $this->paket->id) {
                    // Terapkan diskon jika kode promo valid
                    $this->discount = $redeemCode->discount_amount;
                    $this->promoMessage = 'Kode promo berhasil digunakan!';
                    $this->promoMessageClass = 'text-success';
                    $this->inputDisabled = true; // Nonaktifkan input setelah sukses
                } else {
                    $this->promoMessage = 'Kode promo ini tidak berlaku untuk paket ini!';
                    $this->promoMessageClass = 'text-danger';
                    $this->discount = 0;
                    $this->inputDisabled = false; // Jangan nonaktifkan input jika tidak valid
                }
            } else {
                $this->promoMessage = 'Kode promo tidak valid!';
                $this->promoMessageClass = 'text-danger';
                $this->discount = 0;
                $this->inputDisabled = false; // Jangan nonaktifkan input jika tidak valid
            }
        } else {
            $this->promoMessage = 'Kode promo tidak ditemukan!';
            $this->promoMessageClass = 'text-danger';
            $this->discount = 0;
            $this->inputDisabled = false; // Jangan nonaktifkan input jika tidak ditemukan
        }

        // Hitung total harga setelah diskon
        $this->total = $this->paket->harga - $this->discount;
    }

    public $isProcessingPayment = false;

    public function bayarSekarang()
    {
        $this->isProcessingPayment = true;

        try {
            // Generate transaction ID
            $transactionId = 'TRX-' . time();
            
            // Create transaction record
            $transaksi = new TransaksiUser();
            $transaksi->user_id = auth()->id();
            $transaksi->paket_id = $this->paket->id;
            $transaksi->kode_transaksi = $transactionId;
            $transaksi->total_amount = $this->total;
            $transaksi->status = 'pending';
            $transaksi->tanggal_pembelian = now();
            $transaksi->save();

            logger("Transaction created with ID: " . $transaksi->id);

            // Create instance of TransactionController and call directly
            $controller = app(TransactionController::class);
            $response = $controller->createPayment(request(), $transaksi->id);
            
            // Convert response to array
            $data = $response->getData(true);
            
            logger("Payment response: " . json_encode($data));

            if (isset($data['redirect_url'])) {
                return redirect()->to($data['redirect_url']);
            } else {
                $this->addError('payment', 'No redirect URL in response');
                logger("No redirect URL in response: " . json_encode($data));
            }

        } catch (\Exception $e) {
            logger("Payment error: " . $e->getMessage());
            $this->addError('payment', 'Error: ' . $e->getMessage());
        } finally {
            $this->isProcessingPayment = false;
        }
    }

    public function render(): mixed
    {
        // Mengembalikan tampilan dengan parameter
        return view('livewire.pages.user.components.checkout-paket', [
            'paket' => $this->paket,
            'discount' => $this->discount,
            'total' => $this->total,
            'promoMessage' => $this->promoMessage,
            'promoMessageClass' => $this->promoMessageClass,
            'inputDisabled' => $this->inputDisabled, // Menyertakan status input
            'promoCodeValidated' => $this->promoCodeValidated, // Menyertakan status validasi kode promo
        ]);
    }
}
?>

<div>
    <section>
        <div class="container-xl px-4 mt-n10">
            <div class="row">
                <!-- Paket Detail -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">Detail Paket</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="{{ asset('assets/img/paket/' . $paket->image) }}" class="img-fluid" alt="{{ $paket->nama_paket }}" style="border-radius: 10px;">
                                </div>
                                <div class="col-md-6">
                                    <h5>{{ $paket->nama_paket }}</h5>
                                    <p>{{ $paket->deskripsi }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">Pembayaran</div>
                        <div class="card-body">
                            <!-- Promo Code -->
                            <h5 class="mb-3 font-weight-bold">Punya Kode Promo?</h5>
                            <div class="input-group mb-3">
                                <input 
                                    type="text" 
                                    class="form-control 
                                        @if($promoMessageClass == 'text-danger' && !empty($promoCode)) is-invalid @endif 
                                        @if($promoMessageClass == 'text-success') is-valid @endif" 
                                    placeholder="Masukan kode promo" 
                                    wire:model="promoCode" 
                                    @if($promoMessageClass == 'text-success') disabled @endif
                                >
                                <button class="btn btn-primary" type="button" wire:click="applyPromoCode" @if($promoMessageClass == 'text-success') disabled @endif>Gunakan</button>
                                <!-- Pesan Kode Promo -->
                            @if ($promoMessage)
                            <span class="text-sm {{ $promoMessageClass }} mt-2">{{ $promoMessage }}</span>
                        @endif
                            </div>

                            

                            <!-- Price Details -->
                            <h5 class="mb-3 font-weight-bold">Rincian Harga</h5>
                            <ul class="list-unstyled">
                                <li class="d-flex justify-content-between">
                                    <span>Harga:</span>
                                    <strong>Rp {{ number_format($paket->harga, 0, ',', '.') }}</strong>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span>Diskon:</span>
                                    <strong>Rp {{ number_format($discount, 0, ',', '.') }}</strong>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span>Total Harus Dibayar:</span>
                                    <strong class="text-danger">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                                </li>
                            </ul>

                            <!-- Payment Button -->
                            <div class="card-footer bg-white text-center">
                                <button 
                                    wire:click="bayarSekarang"
                                    class="btn btn-sm btn-success w-100"
                                    wire:loading.attr="disabled"
                                    wire:target="bayarSekarang">
                                    
                                    <div class="d-flex align-items-center justify-content-center">
                                        <!-- Loading spinner -->
                                        <div wire:loading wire:target="bayarSekarang" class="me-2">
                                            <div class="spinner-border spinner-border-sm text-white" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Button text -->
                                        <span wire:loading.remove wire:target="bayarSekarang">
                                            Beli Sekarang!
                                        </span>
                                        <span wire:loading wire:target="bayarSekarang">
                                            Memproses Pembayaran...
                                        </span>
                                    </div>
                                </button>
                            
                                <!-- Error message -->
                                @error('payment')
                                    <div class="text-danger mt-2 small">{{ $message }}</div>
                                @enderror
                            </div>                         
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
