<?php

use App\Models\PaketList;
use App\Models\RedeemCode;
use App\Models\TransaksiUser;
use Livewire\Volt\Component;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\DB;

new class extends Component
{
    public $paket;
    public $promoCode = '';
    public $promoDiscount = 0;
    public $packageDiscount = 0;
    public $total = 0;
    public $promoMessage = '';
    public $promoMessageClass = 'text-danger';
    public $inputDisabled = false;
    public $promoCodeValidated = false;
    public $redeemCodeId = null;
    public $isProcessingPayment = false;

    public function mount($id)
    {
        $this->paket = PaketList::find($id);
        
        if (!$this->paket) {
            return redirect()->route('user.beli');
        }

        $userHasPackage = TransaksiUser::where('user_id', auth()->id())
            ->where('paket_id', $this->paket->id)
            ->whereIn('status', ['success', 'pending'])
            ->exists();

        if ($userHasPackage) {
            return redirect()->route('user.beli');
        }

        $this->packageDiscount = $this->paket->discount ?? 0;
        $this->calculateTotal();
    }

    private function calculateTotal()
{
    // If package is free tier, force total to 0
    if ($this->paket->tier === 'free') {
        $this->total = 0;
    } else {
        $this->total = max(0, $this->paket->harga - $this->packageDiscount - $this->promoDiscount);
    }
}

    public function applyPromoCode()
    {
        $this->promoCodeValidated = true;

        if (empty($this->promoCode)) {
            $this->promoMessage = 'Kode promo tidak boleh kosong!';
            $this->promoMessageClass = 'text-danger';
            $this->inputDisabled = false;
            return;
        }

        try {
            DB::beginTransaction();

            $redeemCode = RedeemCode::lockForUpdate()
                ->where('code', $this->promoCode)
                ->first();

            if (!$redeemCode) {
                throw new \Exception('Kode promo tidak ditemukan!');
            }

            // Check if user has used this code before
            $hasUsedCode = TransaksiUser::where('user_id', auth()->id())
                ->where('redeem_code_id', $redeemCode->id)
                ->whereIn('status', ['success', 'pending'])
                ->exists();

            if ($hasUsedCode) {
                throw new \Exception('Anda sudah pernah menggunakan kode promo ini!');
            }

            if ($redeemCode->isExpired()) {
                throw new \Exception('Kode promo sudah kadaluarsa!');
            }

            if (!$redeemCode->hasAvailableQuota()) {
                throw new \Exception('Kode promo sudah mencapai batas penggunaan!');
            }

            if ($redeemCode->activation_status == 1) {
                throw new \Exception('Kode promo sudah digunakan!');
            }

            if ($redeemCode->related_id !== null && $redeemCode->related_id != $this->paket->id) {
                throw new \Exception('Kode promo ini tidak berlaku untuk paket ini!');
            }

            $this->redeemCodeId = $redeemCode->id;
            $this->promoDiscount = $redeemCode->discount_amount;
            $this->promoMessage = 'Kode promo berhasil digunakan!';
            $this->promoMessageClass = 'text-success';
            $this->inputDisabled = true;

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->promoMessage = $e->getMessage();
            $this->promoMessageClass = 'text-danger';
            $this->promoDiscount = 0;
            $this->inputDisabled = false;
            $this->redeemCodeId = null;
        }

        $this->calculateTotal();
    }

    public function bayarSekarang()
    {
        $this->isProcessingPayment = true;

        try {
            DB::beginTransaction();

            $transactionId = 'TRX-' . time();
            
            $transaksi = new TransaksiUser();
            $transaksi->user_id = auth()->id();
            $transaksi->paket_id = $this->paket->id;
            $transaksi->kode_transaksi = $transactionId;
            $transaksi->total_amount = $this->total;
            $transaksi->redeem_code_id = $this->redeemCodeId;
            $transaksi->tanggal_pembelian = now();

            // If total is 0, create successful transaction directly
            if ($this->total === 0) {
                $transaksi->status = 'success';
                $transaksi->gateway_waktu_pembayaran = now();
                $transaksi->gateway_status_message = 'Free transaction';
                $transaksi->save();

                logger("Free transaction created with ID: " . $transaksi->id);
                
                DB::commit();
                
                return redirect()->route('user.transaksi')->with('success-free', 'Paket berhasil ditambahkan');
            }

            // If not free, proceed with normal payment flow
            $transaksi->status = 'pending';
            $transaksi->save();

            logger("Transaction created with ID: " . $transaksi->id);

            $controller = app(TransactionController::class);
            $response = $controller->createPayment(request(), $transaksi->id);
            
            $data = $response->getData(true);
            
            logger("Payment response: " . json_encode($data));

            if (!isset($data['redirect_url'])) {
                throw new \Exception('No redirect URL in response');
            }

            DB::commit();
            
            return redirect()->to($data['redirect_url']);

        } catch (\Exception $e) {
            DB::rollBack();
            logger("Payment error: " . $e->getMessage());
            $this->addError('payment', 'Error: ' . $e->getMessage());
        } finally {
            $this->isProcessingPayment = false;
        }
    }
    
    public function render(): mixed
    {
        return view('livewire.pages.user.components.checkout-paket', [
            'paket' => $this->paket,
            'promoDiscount' => $this->promoDiscount,
            'packageDiscount' => $this->packageDiscount,
            'total' => $this->total,
            'promoMessage' => $this->promoMessage,
            'promoMessageClass' => $this->promoMessageClass,
            'inputDisabled' => $this->inputDisabled,
            'promoCodeValidated' => $this->promoCodeValidated,
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
                            @if($paket->tier !== 'free')
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
                        @endif

                            <!-- Price Details -->
                            <h5 class="mb-3 font-weight-bold">Rincian Harga</h5>
                            <ul class="list-unstyled">
                                <li class="d-flex justify-content-between">
                                    <span>Harga:</span>
                                    <strong>Rp {{ number_format($paket->harga, 0, ',', '.') }}</strong>
                                </li>
                                @if($packageDiscount > 0)
                                <li class="d-flex justify-content-between">
                                    <span>Diskon Paket:</span>
                                    <strong>Rp {{ number_format($packageDiscount, 0, ',', '.') }}</strong>
                                </li>
                                @endif
                                @if($promoDiscount > 0)
                                <li class="d-flex justify-content-between">
                                    <span>Diskon Promo:</span>
                                    <strong>Rp {{ number_format($promoDiscount, 0, ',', '.') }}</strong>
                                </li>
                                @endif
                                <li class="d-flex justify-content-between">
                                    <span>Total Harus Dibayar:</span>
                                    <strong class="text-danger">
                                        @if($total === 0)
                                            Gratis
                                        @else
                                            Rp {{ number_format($total, 0, ',', '.') }}
                                        @endif
                                    </strong>
                                </li>                                
                            </ul>

                            <!-- Payment Button -->
                            <div class="card-footer bg-white text-center">
                                <button 
                                type="button"
                                data-bs-toggle="modal"
                                data-bs-target="{{ $total === 0 ? '#confirmFreeModal' : '#confirmPaidModal' }}"
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
                                        @if($total === 0)
                                            Dapatkan Gratis!
                                        @else
                                            Beli Sekarang!
                                        @endif
                                    </span>
                                    <span wire:loading wire:target="bayarSekarang">
                                        @if($total === 0)
                                            Memproses...
                                        @else
                                            Memproses Pembayaran...
                                        @endif
                                    </span>
                                </div>
                            </button>

                                <!-- Error message -->
                                @error('payment')
                                    <div class="text-danger mt-2 small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirmation Modal for Free Package -->
                            <div class="modal fade" id="confirmFreeModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="confirmFreeModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmFreeModalLabel">Konfirmasi Pendaftaran Paket</h5>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah anda yakin ingin mendaftar paket <strong>{{ $paket->nama_paket }}</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Batal</button>
                                            <button class="btn btn-success" type="button" wire:click="bayarSekarang" data-bs-dismiss="modal">Ya, lanjutkan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>      
                            <!-- Confirmation Modal for Paid Package -->
                            <div class="modal fade" id="confirmPaidModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="confirmPaidModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmPaidModalLabel">Konfirmasi Pembayaran Paket</h5>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah anda yakin ingin membeli paket <strong>{{ $paket->nama_paket }}</strong>?</p>
                                            <p class="mt-2">Total pembayaran: <strong class="text-danger">Rp {{ number_format($total, 0, ',', '.') }}</strong></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Batal</button>
                                            <button class="btn btn-success" type="button" wire:click="bayarSekarang" data-bs-dismiss="modal">Ya, lanjutkan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>               
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
