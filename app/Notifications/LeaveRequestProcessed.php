<?php

namespace App\Notifications;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestProcessed extends Notification implements ShouldQueue
{
    use Queueable;

    protected $leave;

    /**
     * Buat instance notifikasi baru.
     */
    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
    }

    /**
     * Tentukan channel pengiriman notifikasi (bisa email, database, dll).
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Buat representasi email dari notifikasi.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $startDate = $this->leave->start_date->translatedFormat('d F Y');
        $endDate = $this->leave->end_date->translatedFormat('d F Y');
        $status = $this->leave->status;
        $type = $this->leave->type;

        if ($status === 'Approved') {
            return (new MailMessage)
                ->subject('Pengajuan Cuti Disetujui')
                ->greeting('Halo, ' . $notifiable->name . '!')
                ->line("Kabar baik! Pengajuan {$type} Anda untuk tanggal {$startDate} hingga {$endDate} telah disetujui.")
                ->action('Lihat Riwayat Cuti', route('leaves.index'))
                ->line('Selamat beristirahat!');
        } else {
            return (new MailMessage)
                ->subject('Pengajuan Cuti Ditolak')
                ->greeting('Halo, ' . $notifiable->name . '.')
                ->line("Mohon maaf, pengajuan {$type} Anda untuk tanggal {$startDate} hingga {$endDate} tidak dapat disetujui saat ini.")
                ->line('Alasan Penolakan: ' . ($this->leave->rejection_reason ?? 'Tidak ada alasan spesifik.'))
                ->action('Lihat Riwayat Cuti', route('leaves.index'))
                ->line('Silakan hubungi HR jika ada pertanyaan lebih lanjut.');
        }
    }
}