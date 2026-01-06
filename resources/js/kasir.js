document.addEventListener('alpine:init', () => {
    Alpine.data('kasirApp', () => ({
        products: window.KASIR_DATA.products,
        cart: [],
        selectedCategory: null,
        searchQuery: '',
        filteredProducts: [],
        showPaymentModal: false,
        showConfirmationModal: false,
        showSuccessModal: false,
        selectedPaymentMethod: 'Tunai',
        cashReceived: 0,
        change: 0,
        currentDate: '',
        orderNumber: '1',
        lastTransactionId: null,
        showToast: false,
        toastMessage: '',
        toastType: 'success',
        activeTab: 'categories',
        savedOrders: [],
        showQrisModal: false,
        snapToken: null,
        transactionId: null,
        qrisStatus: null,
        qrisMessage: '',
        qrisCountdown: '15:00',
        qrisInterval: null,
        qrisData: {},
        isCheckingStatus: false,

        init() {
            this.filteredProducts = this.products;
            this.updateDateTime();
            setInterval(() => this.updateDateTime(), 1000);
            this.$watch('selectedCategory', () => this.filterProducts());
            this.$watch('searchQuery', () => this.filterProducts());
            this.loadSavedOrders();
        },
        updateDateTime() {
                    const now = new Date();
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                        'Oktober', 'November', 'Desember'
                    ];

                    this.currentDate =
                        `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
                },

                filterProducts() {
                    this.filteredProducts = this.products.filter(p => {
                        const matchCategory = !this.selectedCategory || p.kategori === this.selectedCategory;
                        const matchSearch = !this.searchQuery || p.nama_produk.toLowerCase().includes(this
                            .searchQuery.toLowerCase());
                        return matchCategory && matchSearch;
                    });
                },

                addToCart(product) {
                    const existingItem = this.cart.find(item => item.id === product.id);

                    if (existingItem) {
                        if (existingItem.qty < product.stok) {
                            existingItem.qty++;
                        } else {
                            this.toast('Stok tidak mencukupi!', 'error');
                        }
                    } else {
                        this.cart.push({
                            ...product,
                            qty: 1
                        });
                    }
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                incrementQty(index) {
                    if (this.cart[index].qty < this.cart[index].stok) {
                        this.cart[index].qty++;
                    } else {
                        this.toast('Stok tidak mencukupi!', 'error');
                    }
                },

                decrementQty(index) {
                    if (this.cart[index].qty > 1) {
                        this.cart[index].qty--;
                    }
                },

                clearCart() {
                    if (confirm('Hapus semua item dari keranjang?')) {
                        this.cart = [];
                    }
                },

                get totalAmount() {
                    return this.cart.reduce((sum, item) => sum + (item.harga_jual * item.qty), 0);
                },

                get canProceed() {
                    if (this.selectedPaymentMethod === 'Tunai') {
                        return this.cashReceived >= this.totalAmount;
                    }
                    return this.selectedPaymentMethod !== '';
                },

                calculateChange() {
                    if (this.cashReceived >= this.totalAmount) {
                        this.change = this.cashReceived - this.totalAmount;
                    } else {
                        this.change = 0;
                    }
                },

                quickCash(amount) {
                    this.cashReceived = amount;
                    this.calculateChange();
                },

                confirmPayment() {
                    if (!this.canProceed) return;

                    // 🔴 QRIS: LANGSUNG PROSES TRANSAKSI
                    if (this.selectedPaymentMethod.toLowerCase() === 'qris') {
                        this.showPaymentModal = false;
                        this.processTransaction();
                        return;
                    }
                    // 🟢 NON QRIS: pakai konfirmasi
                    this.showPaymentModal = false;
                    this.showConfirmationModal = true;
                },


                async processTransaction() {
                    if (this.cart.length === 0) return;

                    const items = this.cart.map(item => ({
                        product_id: item.id,
                        jumlah: item.qty
                    }));

                    const payments = [{
                        metode: this.selectedPaymentMethod,
                        jumlah: this.selectedPaymentMethod === 'Tunai'
                            ? this.cashReceived
                            : this.totalAmount
                    }];

                    let raw = "";

                    try {
                        const response = await fetch('/kasir/transaction/store', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': window.KASIR_DATA.csrfToken
                            },
                            body: JSON.stringify({ items, payments })
                        });

                        raw = await response.text();
                        console.log("RAW API RESPONSE:", raw);

                        const data = JSON.parse(raw);

                        if (!data.success) {
                            alert(data.message);
                            return;
                        }

                        // =========================
                        // 🔴 QRIS FLOW (STOP HERE)
                        // =========================
                        if (
                            this.selectedPaymentMethod.toLowerCase() === 'qris' &&
                            data.snap_token
                        ) {
                            this.openQrisModal({
                                snap_token: data.snap_token,
                                transaction_id: data.transaction_id
                            });
                            return; // ⛔ PENTING
                        }

                        // =========================
                        // 🟢 NON QRIS = SELESAI
                        // =========================
                        this.lastTransactionId = data.transaction_id;
                        this.showConfirmationModal = false;
                        this.showSuccessModal = true;

                    } catch (error) {
                        console.error("PARSE / API ERROR:", error);
                        console.warn("RAW RESPONSE:", raw);
                        alert("API ERROR — lihat console");
                    }
                },

                openQrisModal(data) {
                    this.qrisData = data;
                    this.showQrisModal = true;
                    this.qrisStatus = null;
                    this.qrisMessage = '';
                    this.qrisCountdown = '15:00';

                    // Render Midtrans Snap (EMBED)
                    setTimeout(() => {
                        snap.embed(data.snap_token, {
                            embedId: 'qris-container',

                            onSuccess: (result) => {
                                console.log('QRIS SUCCESS:', result);
                                this.qrisStatus = 'paid';
                                this.qrisMessage = 'Pembayaran berhasil';
                                clearInterval(this.qrisInterval);

                                setTimeout(() => {
                                    this.closeQrisModal();
                                    this.lastTransactionId = data.transaction_id;
                                    this.showSuccessModal = true;
                                }, 1500);
                            },

                            onPending: (result) => {
                                console.log('QRIS PENDING:', result);
                                this.qrisStatus = 'pending';
                                this.qrisMessage = 'Menunggu pembayaran...';
                            },

                            onError: (result) => {
                                console.error('QRIS ERROR:', result);
                                this.qrisStatus = 'failed';
                                this.qrisMessage = 'Pembayaran gagal';
                            },

                            onClose: () => {
                                console.log('QRIS MODAL CLOSED');
                                this.checkQrisStatus();
                            }
                        });
                    }, 100);

                    // Countdown 15 menit
                    let seconds = 900;
                    this.qrisInterval = setInterval(() => {
                        seconds--;
                        const m = Math.floor(seconds / 60);
                        const s = seconds % 60;
                        this.qrisCountdown = `${m}:${s.toString().padStart(2, '0')}`;

                        if (seconds <= 0) {
                            clearInterval(this.qrisInterval);
                            this.qrisStatus = 'expired';
                            this.qrisMessage = 'QRIS expired';
                        }
                    }, 1000);
                },

                async checkQrisStatus() {
                    if (!this.qrisData?.transaction_id) return;

                    this.isCheckingStatus = true;

                    try {
                        const response = await fetch(
                            `/kasir/check-qris/${this.qrisData.transaction_id}`
                        );
                        const data = await response.json();

                        if (!data.success) return;

                        this.qrisStatus = data.status;
                        this.qrisMessage = data.message;

                        if (data.status === 'paid') {
                            clearInterval(this.qrisInterval);

                            setTimeout(() => {
                                this.closeQrisModal();
                                this.lastTransactionId = this.qrisData.transaction_id;
                                this.showSuccessModal = true;
                            }, 1500);
                        }

                        if (data.status === 'failed') {
                            clearInterval(this.qrisInterval);
                            this.toast('Pembayaran gagal', 'error');
                        }

                    } catch (error) {
                        console.error('CHECK QRIS ERROR:', error);
                        this.toast('Gagal mengecek status QRIS', 'error');
                    } finally {
                        this.isCheckingStatus = false;
                    }
                },


                closeQrisModal() {
                    this.showQrisModal = false;
                    clearInterval(this.qrisInterval);
                },


                printReceipt() {
                    if (this.lastTransactionId) {
                        window.open(
                        window.KASIR_DATA.receiptUrl + '/' + this.lastTransactionId,
                        '_blank'
                    );

                    }
                    this.finishTransaction();
                },

                finishTransaction() {
                    this.cart = [];
                    this.showSuccessModal = false;
                    this.showPaymentModal = false;
                    this.showConfirmationModal = false;
                    this.selectedPaymentMethod = 'Tunai';
                    this.cashReceived = 0;
                    this.change = 0;
                    this.orderNumber = parseInt(this.orderNumber) + 1;
                    this.toast('Transaksi selesai!', 'success');
                },

                // Saved Orders Functions
                saveCurrentOrder() {
                    if (this.cart.length === 0) return;

                    const order = {
                        orderNumber: this.orderNumber,
                        items: JSON.parse(JSON.stringify(this.cart)),
                        total: this.totalAmount,
                        savedTime: new Date().toLocaleString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit',
                            day: '2-digit',
                            month: 'short'
                        })
                    };

                    this.savedOrders.push(order);
                    localStorage.setItem('savedOrders', JSON.stringify(this.savedOrders));

                    this.cart = [];
                    this.orderNumber = parseInt(this.orderNumber) + 1;
                    this.toast('Pesanan berhasil disimpan!', 'success');
                    this.activeTab = 'saved';
                },

                loadSavedOrders() {
                    const saved = localStorage.getItem('savedOrders');
                    if (saved) {
                        this.savedOrders = JSON.parse(saved);
                    }
                },

                loadSavedOrder(index) {
                    if (this.cart.length > 0) {
                        if (!confirm('Keranjang saat ini akan diganti. Lanjutkan?')) {
                            return;
                        }
                    }

                    this.cart = JSON.parse(JSON.stringify(this.savedOrders[index].items));
                    this.orderNumber = this.savedOrders[index].orderNumber;
                    this.deleteSavedOrder(index);
                    this.activeTab = 'categories';
                    this.toast('Pesanan berhasil dimuat!', 'success');
                },

                deleteSavedOrder(index) {
                    this.savedOrders.splice(index, 1);
                    localStorage.setItem('savedOrders', JSON.stringify(this.savedOrders));
                },

                formatNumber(num) {
                    return new Intl.NumberFormat('id-ID').format(num);
                },

                toast(message, type = 'success') {
                    this.toastMessage = message;
                    this.toastType = type;
                    this.showToast = true;
                    setTimeout(() => this.showToast = false, 4000);
                }
        // 🔽 SEMUA METHOD ANDA TETAP SAMA
    }))
});
