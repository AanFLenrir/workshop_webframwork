@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4 fw-semibold">
                    <i class="fas fa-cash-register me-2"></i>Point of Sales
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Barang</label>
                        <input type="text" id="inputKode" class="form-control" placeholder="Scan / ketik kode barang..." autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang</label>
                        <input type="text" id="inputNama" class="form-control bg-warning-subtle" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga Barang</label>
                        <input type="text" id="inputHarga" class="form-control bg-warning-subtle" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah</label>
                        <input type="number" id="inputJumlah" class="form-control" value="1" min="1">
                    </div>
                    <button type="button" id="btnTambah" class="btn btn-success w-100" disabled onclick="tambahItem()">
                        <i class="fas fa-plus me-2"></i>Tambahkan
                    </button>
                </div>
            </div>
            <div class="card shadow-sm border-0 rounded-4 mt-4">
                <div class="card-header bg-white border-bottom fw-semibold text-primary">
                    <i class="fas fa-wallet me-2"></i>Metode Pembayaran
                </div>
                <div class="card-body d-flex flex-column gap-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="paymentMethod" id="pmTunai" value="tunai" checked>
                        <label class="form-check-label fw-semibold" for="pmTunai">💵 Tunai</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="paymentMethod" id="pmVA" value="virtual_account">
                        <label class="form-check-label fw-semibold" for="pmVA">🏦 Virtual Account <small class="text-muted">(BCA, BNI, BRI, Mandiri)</small></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="paymentMethod" id="pmQRIS" value="qris">
                        <label class="form-check-label fw-semibold" for="pmQRIS">📱 QRIS / GoPay</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-bottom fw-semibold text-primary d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-shopping-cart me-2"></i>Keranjang Belanja</span>
                    <a href="{{ route('pos.riwayat') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-history me-1"></i>Riwayat Transaksi
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover align-middle" id="tabelPOS">
                        <thead class="table-primary">
                            <tr>
                                <th>Kode</th><th>Nama</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th><th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyPOS">
                            <tr id="emptyRow">
                                <td colspan="6" class="text-center text-muted">Belum ada item</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-success fw-bold">
                                <td colspan="4" class="text-end">Total</td>
                                <td colspan="2" id="totalHarga">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="text-end mt-3">
                        <button type="button" id="btnBayar" class="btn btn-success px-4" disabled onclick="bayar()">
                            <i class="fas fa-money-bill-wave me-2"></i>Bayar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
let hargaBarang=0,namaBarang='',kodeBarang='',idBarang=null,barangDitemukan=false;

document.getElementById('inputKode').addEventListener('keydown',function(e){
    if(e.key!=='Enter')return;
    const kode=this.value.trim();
    if(!kode)return;
    document.getElementById('inputNama').value='';
    document.getElementById('inputHarga').value='';
    document.getElementById('inputJumlah').value=1;
    document.getElementById('btnTambah').disabled=true;
    barangDitemukan=false;
    axios.post('{{ route("pos.cari") }}',{kode:kode,_token:'{{ csrf_token() }}'})
    .then(function(res){
        const b=res.data.data;
        namaBarang=b.nama;hargaBarang=b.harga;kodeBarang=b.id_barang;idBarang=b.id_barang;barangDitemukan=true;
        document.getElementById('inputNama').value=b.nama;
        document.getElementById('inputHarga').value='Rp '+parseInt(b.harga).toLocaleString('id-ID');
        document.getElementById('inputJumlah').value=1;
        document.getElementById('btnTambah').disabled=false;
    })
    .catch(function(){Swal.fire('Tidak Ditemukan','Kode barang tidak ada di database.','error');});
});

function tambahItem(){
    if(!barangDitemukan)return;
    const jumlah=parseInt(document.getElementById('inputJumlah').value);
    if(jumlah<1){alert('Jumlah minimal 1');return;}
    const btn=document.getElementById('btnTambah');
    btn.disabled=true;btn.innerHTML='<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    setTimeout(function(){
        const subtotal=hargaBarang*jumlah;
        const existing=document.querySelector(`tr[data-kode="${kodeBarang}"]`);
        if(existing){
            const tdJ=existing.querySelector('.td-jumlah');
            const tdS=existing.querySelector('.td-subtotal');
            const nJ=parseInt(tdJ.querySelector('input').value)+jumlah;
            const nS=hargaBarang*nJ;
            tdJ.querySelector('input').value=nJ;
            tdS.textContent='Rp '+nS.toLocaleString('id-ID');
            tdS.dataset.val=nS;
        }else{
            const emptyRow=document.getElementById('emptyRow');
            if(emptyRow)emptyRow.remove();
            const tr=document.createElement('tr');
            tr.dataset.kode=kodeBarang;tr.dataset.id=idBarang;tr.dataset.harga=hargaBarang;
            tr.innerHTML=`<td><code>${kodeBarang}</code></td><td>${namaBarang}</td>
                <td>Rp ${parseInt(hargaBarang).toLocaleString('id-ID')}</td>
                <td class="td-jumlah"><input type="number" class="form-control form-control-sm" value="${jumlah}" min="1" style="width:80px" onchange="updateSubtotal(this)"></td>
                <td class="td-subtotal" data-val="${subtotal}">Rp ${subtotal.toLocaleString('id-ID')}</td>
                <td><button class="btn btn-danger btn-sm" onclick="hapusRow(this)"><i class="fas fa-trash"></i></button></td>`;
            document.getElementById('tbodyPOS').appendChild(tr);
        }
        updateTotal();
        document.getElementById('inputKode').value='';document.getElementById('inputNama').value='';
        document.getElementById('inputHarga').value='';document.getElementById('inputJumlah').value=1;
        barangDitemukan=false;idBarang=null;
        btn.disabled=true;btn.innerHTML='<i class="fas fa-plus me-2"></i>Tambahkan';
        document.getElementById('inputKode').focus();
        document.getElementById('btnBayar').disabled=false;
    },300);
}

function updateSubtotal(input){
    const tr=input.closest('tr');
    const subtotal=parseInt(tr.dataset.harga)*(parseInt(input.value)||1);
    const tdSub=tr.querySelector('.td-subtotal');
    tdSub.textContent='Rp '+subtotal.toLocaleString('id-ID');
    tdSub.dataset.val=subtotal;
    updateTotal();
}

function hapusRow(btn){
    btn.closest('tr').remove();
    if(document.getElementById('tbodyPOS').rows.length===0){
        const tr=document.createElement('tr');
        tr.id='emptyRow';
        tr.innerHTML='<td colspan="6" class="text-center text-muted">Belum ada item</td>';
        document.getElementById('tbodyPOS').appendChild(tr);
        document.getElementById('btnBayar').disabled=true;
    }
    updateTotal();
}

function updateTotal(){
    let total=0;
    document.querySelectorAll('.td-subtotal').forEach(function(td){total+=parseInt(td.dataset.val)||0;});
    document.getElementById('totalHarga').textContent='Rp '+total.toLocaleString('id-ID');
}

function resetKeranjang(){
    document.getElementById('tbodyPOS').innerHTML='<tr id="emptyRow"><td colspan="6" class="text-center text-muted">Belum ada item</td></tr>';
    document.getElementById('totalHarga').textContent='Rp 0';
    document.getElementById('inputKode').value='';document.getElementById('inputNama').value='';
    document.getElementById('inputHarga').value='';document.getElementById('inputJumlah').value=1;
    barangDitemukan=false;idBarang=null;
    const btn=document.getElementById('btnBayar');
    btn.disabled=true;btn.innerHTML='<i class="fas fa-money-bill-wave me-2"></i>Bayar';
}

function bayar(){
    const rows=document.querySelectorAll('#tbodyPOS tr[data-kode]');
    if(rows.length===0)return;
    const items=[];let total=0;
    rows.forEach(function(tr){
        const jumlah=parseInt(tr.querySelector('.td-jumlah input').value);
        const subtotal=parseInt(tr.querySelector('.td-subtotal').dataset.val);
        total+=subtotal;
        items.push({id:tr.dataset.id,qty:jumlah});
    });
    const paymentMethod=document.querySelector('input[name="paymentMethod"]:checked').value;
    const btn=document.getElementById('btnBayar');
    btn.disabled=true;btn.innerHTML='<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

    if(paymentMethod==='tunai'){
        axios.post('{{ route("pos.bayar") }}',{items:items,payment_method:'tunai',_token:'{{ csrf_token() }}'})
        .then(function(res){
            Swal.fire({icon:'success',title:'Berhasil!',
                html:`Transaksi tunai berhasil!<br><strong>Order:</strong> ${res.data.order_code}<br>
                      <strong>Customer:</strong> ${res.data.customer}<br>
                      <strong>Total:</strong> Rp ${parseInt(res.data.total).toLocaleString('id-ID')}`});
            resetKeranjang();
        })
        .catch(function(){
            Swal.fire('Error!','Transaksi gagal disimpan.','error');
            btn.disabled=false;btn.innerHTML='<i class="fas fa-money-bill-wave me-2"></i>Bayar';
        });
        return;
    }

    axios.post('{{ route("pos.bayar") }}',{items:items,payment_method:paymentMethod,_token:'{{ csrf_token() }}'})
    .then(function(res){
        const snapToken=res.data.snap_token;
        const orderCode=res.data.order_code;
        const customer=res.data.customer;
        const totalVal=res.data.total;
        snap.pay(snapToken,{
            onSuccess:function(result){
                const qrUrl='{{ route("qrcode.generate", ":code") }}'.replace(':code',orderCode);
                Swal.fire({
                    icon:'success',title:'✅ Pembayaran Berhasil!',
                    html:`<strong>Order:</strong> ${orderCode}<br>
                          <strong>Customer:</strong> ${customer}<br>
                          <strong>Total:</strong> Rp ${parseInt(totalVal).toLocaleString('id-ID')}<br>
                          <strong>Metode:</strong> ${result.payment_type}<br><br>
                          <p class="mb-1 fw-semibold">QR Code Pesanan:</p>
                          <img src="${qrUrl}" alt="QR Code ${orderCode}"
                               style="width:180px;height:180px;border:1px solid #dee2e6;border-radius:8px;padding:8px;">`,
                    confirmButtonText:'Selesai'
                });
                resetKeranjang();
            },
            onPending:function(){
                Swal.fire({icon:'info',title:'⏳ Menunggu Pembayaran',
                    html:`Order <strong>${orderCode}</strong> sedang menunggu pembayaran.<br>Silakan selesaikan pembayaran Anda.`});
                resetKeranjang();
            },
            onError:function(){
                Swal.fire('❌ Gagal!','Pembayaran gagal. Silakan coba lagi.','error');
                btn.disabled=false;btn.innerHTML='<i class="fas fa-money-bill-wave me-2"></i>Bayar';
            },
            onClose:function(){
                Swal.fire({icon:'warning',title:'Popup Ditutup',text:'Kamu menutup popup sebelum pembayaran selesai.',confirmButtonText:'OK'});
                btn.disabled=false;btn.innerHTML='<i class="fas fa-money-bill-wave me-2"></i>Bayar';
            }
        });
    })
    .catch(function(){
        Swal.fire('Error!','Gagal menghubungi server pembayaran.','error');
        btn.disabled=false;btn.innerHTML='<i class="fas fa-money-bill-wave me-2"></i>Bayar';
    });
}
</script>
@endpush
