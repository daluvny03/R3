<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransTransaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Generate QRIS untuk transaksi
     */
    public function createQrisTransaction($transaction, $items)
    {
        $orderId = 'ERTIGA-' . $transaction->id . '-' . time();
        
        $itemDetails = [];
        foreach ($items as $item) {
            $itemDetails[] = [
                'id' => $item['product_id'],
                'price' => (int) $item['harga_satuan'],
                'quantity' => (int) $item['jumlah'],
                'name' => substr($item['nama_produk'], 0, 50) // Midtrans limit 50 karakter
            ];
        }

        $params = [
            'transaction_details' => [
                'payment_type' => 'qris',
                'order_id' => $orderId,
                'gross_amount' => (int) $transaction->total_harga,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $transaction->kasir->name,
                'email' => $transaction->kasir->email,
            ],
            // 'enabled_payments' => ['qris'], // Hanya QRIS
            // 'qris' => [
            //     'acquirer' => 'gopay' // atau 'airpay'
            // ],
            // 'expiry' => [
            //     'start_time' => date('Y-m-d H:i:s O'),
            //     'unit' => 'minutes',
            //     'duration' => 15 // QRIS expire dalam 15 menit
            // ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            return [
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $orderId,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Cek status transaksi
     */
    public function checkTransactionStatus($orderId)
    {
        try {
            $status = MidtransTransaction::status($orderId);
            return [
                'success' => true,
                'status' => $status
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Handle notifikasi dari Midtrans (webhook)
     */
    public function handleNotification($notification)
    {
        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status ?? null;

        $status = '';

        if ($transactionStatus == 'capture') {
            $status = ($fraudStatus == 'accept') ? 'success' : 'pending';
        } elseif ($transactionStatus == 'settlement') {
            $status = 'selesai';
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $status = 'failed';
        } elseif ($transactionStatus == 'pending') {
            $status = 'pending';
        }

        return [
            'order_id' => $orderId,
            'status' => $status,
            'transaction_status' => $transactionStatus,
            'payment_type' => $notification->payment_type ?? null,
        ];
    }
}