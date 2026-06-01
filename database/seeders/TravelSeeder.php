<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Gallery;
use App\Models\Package;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TravelSeeder extends Seeder
{
    public function run(): void
    {
        Setting::query()->updateOrCreate(
            ['id' => 1],
            [
                'website_name' => 'Amanah Umroh Haji',
                'phone' => '081234567890',
                'whatsapp' => '6281234567890',
                'email' => 'info@amanahumroh.com',
                'address' => 'Jl. Contoh Travel Umroh No. 10, Indonesia',
                'google_maps' => null,
                'instagram' => 'https://instagram.com/',
                'facebook' => 'https://facebook.com/',
                'tiktok' => 'https://tiktok.com/',
                'youtube' => 'https://youtube.com/',
                'meta_title' => 'Travel Umroh dan Haji Terpercaya',
                'meta_description' => 'Paket umroh dan haji terpercaya dengan pelayanan terbaik, nyaman, aman, dan profesional.',
            ]
        );

        $packages = [
            [
                'type' => 'umroh',
                'title' => 'Paket Umroh Reguler 12 Hari',
                'price' => 29500000,
                'duration_days' => 12,
                'departure_date' => now()->addMonths(2)->toDateString(),
                'airline' => 'Garuda Indonesia',
                'makkah_hotel' => 'Hotel Bintang 4 Makkah',
                'madinah_hotel' => 'Hotel Bintang 4 Madinah',
                'seat' => 45,
                'is_featured' => true,
            ],
            [
                'type' => 'umroh',
                'title' => 'Paket Umroh Plus Thaif',
                'price' => 33500000,
                'duration_days' => 13,
                'departure_date' => now()->addMonths(3)->toDateString(),
                'airline' => 'Saudia Airlines',
                'makkah_hotel' => 'Hotel Dekat Masjidil Haram',
                'madinah_hotel' => 'Hotel Dekat Masjid Nabawi',
                'seat' => 40,
                'is_featured' => true,
            ],
            [
                'type' => 'umroh',
                'title' => 'Paket Umroh Ramadhan',
                'price' => 38500000,
                'duration_days' => 12,
                'departure_date' => now()->addMonths(4)->toDateString(),
                'airline' => 'Qatar Airways',
                'makkah_hotel' => 'Hotel Area Ajyad',
                'madinah_hotel' => 'Hotel Area Markaziyah',
                'seat' => 35,
                'is_featured' => true,
            ],
            [
                'type' => 'haji',
                'title' => 'Paket Haji Khusus',
                'price' => 185000000,
                'duration_days' => 25,
                'departure_date' => now()->addMonths(8)->toDateString(),
                'airline' => 'Garuda Indonesia',
                'makkah_hotel' => 'Hotel Premium Makkah',
                'madinah_hotel' => 'Hotel Premium Madinah',
                'seat' => 30,
                'is_featured' => true,
            ],
            [
                'type' => 'haji',
                'title' => 'Paket Haji Furoda',
                'price' => 285000000,
                'duration_days' => 23,
                'departure_date' => now()->addMonths(9)->toDateString(),
                'airline' => 'Saudia Airlines',
                'makkah_hotel' => 'Hotel Bintang 5 Makkah',
                'madinah_hotel' => 'Hotel Bintang 5 Madinah',
                'seat' => 25,
                'is_featured' => true,
            ],
        ];

        foreach ($packages as $package) {
            Package::query()->updateOrCreate(
                ['slug' => Str::slug($package['title'])],
                array_merge($package, [
                    'slug' => Str::slug($package['title']),
                    'facilities' => "Tiket pesawat PP\nVisa umroh/haji sesuai paket\nHotel Makkah dan Madinah\nMakan 3 kali sehari\nTransportasi bus AC\nMuthawif dan tour leader\nAir zam-zam\nPerlengkapan ibadah",
                    'itinerary' => "Hari 1: Keberangkatan dari Indonesia\nHari 2: Tiba di Madinah\nHari 3-5: Ibadah dan ziarah Madinah\nHari 6: Perjalanan menuju Makkah\nHari 7-10: Pelaksanaan ibadah dan ziarah Makkah\nHari 11: Persiapan kepulangan\nHari 12: Tiba di Indonesia",
                    'requirements' => "KTP\nKartu Keluarga\nPaspor\nPas foto\nBuku vaksin jika diperlukan\nMembayar uang muka pendaftaran",
                    'description' => 'Paket perjalanan ibadah dengan pelayanan nyaman, aman, dan dibimbing oleh pembimbing berpengalaman dari awal keberangkatan sampai kembali ke tanah air.',
                    'status' => 'published',
                ])
            );
        }

        $testimonials = [
            [
                'name' => 'Ibu Siti Aminah',
                'city' => 'Surabaya',
                'package_name' => 'Umroh Reguler',
                'rating' => 5,
                'message' => 'Alhamdulillah perjalanan sangat nyaman, pembimbing sabar, dan semua fasilitas sesuai dengan yang dijelaskan.',
                'is_active' => true,
            ],
            [
                'name' => 'Bapak Ahmad Fauzi',
                'city' => 'Jakarta',
                'package_name' => 'Umroh Plus Thaif',
                'rating' => 5,
                'message' => 'Pelayanannya ramah, jadwal jelas, hotel nyaman, dan perjalanan ibadah terasa sangat terbantu.',
                'is_active' => true,
            ],
            [
                'name' => 'Ibu Nurhayati',
                'city' => 'Bandung',
                'package_name' => 'Haji Khusus',
                'rating' => 5,
                'message' => 'Travel sangat profesional. Dari proses pendaftaran sampai kepulangan semuanya diarahkan dengan baik.',
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::query()->updateOrCreate(
                ['name' => $testimonial['name']],
                $testimonial
            );
        }

        $articles = [
            [
                'title' => 'Tips Memilih Travel Umroh Terpercaya',
                'category' => 'Panduan Umroh',
                'excerpt' => 'Beberapa hal penting yang perlu diperhatikan sebelum memilih travel umroh agar perjalanan ibadah lebih aman dan nyaman.',
                'content' => "Memilih travel umroh terpercaya sangat penting agar perjalanan ibadah berjalan lancar.\n\nPastikan travel memiliki legalitas yang jelas, alamat kantor resmi, jadwal keberangkatan transparan, serta testimoni jamaah yang baik.\n\nSelain itu, calon jamaah juga perlu memperhatikan fasilitas yang diberikan, seperti hotel, maskapai, konsumsi, transportasi, dan pembimbing ibadah.",
            ],
            [
                'title' => 'Dokumen yang Perlu Disiapkan Sebelum Umroh',
                'category' => 'Persiapan Umroh',
                'excerpt' => 'Sebelum berangkat umroh, jamaah perlu menyiapkan beberapa dokumen penting seperti paspor, KTP, dan dokumen pendukung lainnya.',
                'content' => "Persiapan dokumen menjadi bagian penting sebelum melaksanakan ibadah umroh.\n\nDokumen yang biasanya dibutuhkan antara lain KTP, Kartu Keluarga, paspor, pas foto, serta dokumen kesehatan jika diperlukan.\n\nDengan menyiapkan dokumen lebih awal, proses pendaftaran dan keberangkatan akan menjadi lebih mudah.",
            ],
            [
                'title' => 'Perbedaan Umroh Reguler dan Umroh Plus',
                'category' => 'Informasi Paket',
                'excerpt' => 'Umroh reguler dan umroh plus memiliki perbedaan dari sisi rute perjalanan, durasi, fasilitas, dan biaya.',
                'content' => "Umroh reguler biasanya berfokus pada perjalanan ibadah ke Makkah dan Madinah.\n\nSementara itu, umroh plus biasanya menambahkan destinasi lain seperti Thaif, Turki, Dubai, Mesir, atau negara lainnya sesuai program travel.\n\nPerbedaan ini membuat harga dan durasi perjalanan juga berbeda.",
            ],
        ];

        foreach ($articles as $article) {
            Article::query()->updateOrCreate(
                ['slug' => Str::slug($article['title'])],
                [
                    'title' => $article['title'],
                    'slug' => Str::slug($article['title']),
                    'category' => $article['category'],
                    'excerpt' => $article['excerpt'],
                    'content' => $article['content'],
                    'status' => 'published',
                ]
            );
        }

        $galleries = [
            [
                'title' => 'Dokumentasi Keberangkatan Jamaah',
                'type' => 'foto',
                'description' => 'Dokumentasi keberangkatan jamaah menuju tanah suci.',
                'is_active' => true,
            ],
            [
                'title' => 'Kegiatan Manasik Umroh',
                'type' => 'foto',
                'description' => 'Kegiatan bimbingan manasik sebelum keberangkatan.',
                'is_active' => true,
            ],
            [
                'title' => 'Dokumentasi Jamaah di Tanah Suci',
                'type' => 'foto',
                'description' => 'Momen perjalanan ibadah jamaah selama di Makkah dan Madinah.',
                'is_active' => true,
            ],
        ];

        foreach ($galleries as $gallery) {
            Gallery::query()->updateOrCreate(
                ['title' => $gallery['title']],
                $gallery
            );
        }
    }
}