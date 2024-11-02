<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Events\UpdateExamTimeCard;
use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\KelasKategori;
use App\Models\Mapel;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class ConfigController extends Controller
{
    public function index()
    {
        return Inertia::render('Auth/Admin/Config/Config', [
            "kelas" => Kelas::all(),
            "kelas_kategoris" => KelasKategori::all(),
            "mapels" => Mapel::all(),
            "jadwals" => Jadwal::with(['mapel', 'kelas', 'kelasKategori'])->get(),
        ]);
    }

    public function update_activity_data(Request $request)
    {

        $request->validate([
            'activity_type' => 'required|string|max:255',
            'activity_title' => 'required|string|max:255',
            'activity_title_abbreviation' => 'required|string|max:255',
            'semester' => ['required', 'string', Rule::enum(\App\Enums\Semester::class)],
            'school_year_start' => 'required|integer|min:1945|max:9999',
            'school_year_end' => [
                'required',
                'integer',
                'min:1945',
                'max:9999',
                'gt:school_year_start',
            ],
        ]);

        $config = Config::first();

        if (!$config) {
            throw new Error('Config not found');
        }

        $config->activity_type = $request->activity_type;
        $config->activity_title = $request->activity_title;
        $config->activity_title_abbreviation = $request->activity_title_abbreviation;
        $config->semester = $request->semester;
        $config->school_year_start = $request->school_year_start;
        $config->school_year_end = $request->school_year_end;
        $config->save();

        return redirect()->back();
    }

    public function update_exam_schedule_data(Request $request)
    {

        $request->validate([
            'exam_date_start' => 'required|date|regex:/^\d{4}-\d{2}-\d{2}$/',
            'exam_date_end' => 'required|date|regex:/^\d{4}-\d{2}-\d{2}$/|after_or_equal:exam_date_start',
            'holiday_date' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    if (empty(trim($value))) {
                        return;
                    }

                    $holidays = array_map('intval', explode(',', $value));
                    $startDate = new \DateTime($request->exam_date_start);
                    $endDate = new \DateTime($request->exam_date_end);

                    $isValid = true;
                    $formattedHolidays = [];

                    foreach ($holidays as $day) {
                        $holidayDate = clone $startDate;
                        $holidayDate->setDate($holidayDate->format('Y'), $holidayDate->format('m'), $day);

                        if ($holidayDate < $startDate || $holidayDate > $endDate) {
                            $isValid = false;
                            break;
                        }

                        $formattedDate = $holidayDate->format('Y-m-d');
                        if (in_array($formattedDate, $formattedHolidays)) {
                            $isValid = false;
                            break;
                        }

                        $formattedHolidays[] = $formattedDate;
                    }

                    if (!$isValid) {
                        $fail('Tanggal libur tidak valid. Ada tanggal yang duplikat atau di luar rentang ujian.');
                    }
                },
            ]
        ]);

        $config = Config::first();

        if (!$config) {
            throw new Error('Config not found');
        }

        $config->exam_date_start = $request->exam_date_start;
        $config->exam_date_end = $request->exam_date_end;
        $config->holiday_date = $request->holiday_date;
        $config->save();

        broadcast(new UpdateExamTimeCard)->toOthers();

        return redirect()->back();
    }

    public function update_exam_time_data(Request $request)
    {

        $request->validate([
            'exam_time_start' => [
                'required',
                'string',
                'regex:/^\d{2}:\d{2}(:\d{2})?$/',
                function ($attribute, $value, $fail) use ($request) {
                    $startTime = \DateTime::createFromFormat('H:i', $value);
                    $endTime = \DateTime::createFromFormat('H:i', $request->exam_time_end);

                    if ($startTime >= $endTime) {
                        $fail('Waktu mulai ujian harus lebih awal dari waktu selesai.');
                    }
                },
            ],
            'exam_time_end' => [
                'required',
                'string',
                'regex:/^\d{2}:\d{2}(:\d{2})?$/',
                'after:exam_time_start',
            ],
        ]);

        $config = Config::first();

        if (!$config) {
            throw new Error('Config not found');
        }

        $config->exam_time_start = $request->exam_time_start;
        $config->exam_time_end = $request->exam_time_end;
        $config->save();

        broadcast(new UpdateExamTimeCard)->toOthers();

        return redirect()->back();
    }

    public function update_school_data(Request $request)
    {

        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'school_name' => 'required|string|max:255'
        ]);

        $config = Config::first();

        if (!$config) {
            throw new Error('Config not found');
        }


        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $path = $file->storeAs('logo', 'logo.png', 'public');
            $config->logo = 'storage/' . $path;
        }

        $config->school_name = $request->school_name;
        $config->save();

        return redirect()->back();
    }

    public function update_slider_data(Request $request)
    {
        // Validasi input
        $request->validate([
            'images' => 'nullable|array|max:9',
            'images.*' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (preg_match('/^data:image\/[a-zA-Z]+;base64,/', $value)) {
                        // Cek ukuran file base64
                        $data = substr($value, strpos($value, ',') + 1);
                        $fileSize = (int)(strlen($data) * 3 / 4);
                        $fileSize -= substr($data, -2) === '==' ? 2 : (substr($data, -1) === '=' ? 1 : 0);

                        if ($fileSize > 2 * 1024 * 1024) {
                            $fail("Ukuran file {$attribute} tidak boleh lebih dari 2 MB.");
                        }
                    } elseif (!str_starts_with($value, 'storage/slider/')) {
                        $fail("Format {$attribute} tidak valid.");
                    }
                },
            ],
        ]);

        // Cek konfigurasi
        $config = Config::firstOrFail();

        $rawImages = $request->images;

        // Backup folder 'slider' ke 'temp'
        $this->backupSliderFolder();

        // Hapus semua file di folder 'slider'
        Storage::disk('public')->deleteDirectory('slider');
        Storage::disk('public')->makeDirectory('slider');

        // Proses gambar baru
        foreach ($rawImages as $index => $image) {
            $newPath = 'slider/slider' . ($index + 1) . '.webp';

            if (str_starts_with($image, 'storage/slider/')) {
                // Pindahkan dari 'temp' ke 'slider'
                $sourcePath = str_replace('storage/slider', 'temp', $image);
                Storage::disk('public')->copy($sourcePath, $newPath);
            } elseif ($this->isValidBase64Image($image)) {
                // Simpan gambar baru dari base64
                $this->saveBase64ImageToStorage($image, $newPath);
            } else {
                throw new \Exception('Format data URI tidak valid.');
            }

            $rawImages[$index] = 'storage/' . $newPath;
        }

        // Hapus folder 'temp' setelah selesai
        Storage::disk('public')->deleteDirectory('temp');

        // Simpan konfigurasi
        $config->slider_images = json_encode($rawImages);
        $config->save();

        return redirect()->back();
    }

    // Fungsi untuk backup folder slider ke temp
    private function backupSliderFolder()
    {
        $files = Storage::disk('public')->allFiles('slider');
        foreach ($files as $file) {
            $destination = str_replace('slider/', 'temp/', $file);
            Storage::disk('public')->copy($file, $destination);
        }
    }

    // Fungsi untuk mengecek apakah gambar base64 valid
    private function isValidBase64Image($image)
    {
        return preg_match('/^data:image\/[a-zA-Z]+;base64,/', $image);
    }

    // Fungsi untuk menyimpan gambar base64 ke storage
    private function saveBase64ImageToStorage($dataUri, $path)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $dataUri, $type)) {
            $data = substr($dataUri, strpos($dataUri, ',') + 1);
            $data = base64_decode($data);

            if ($data === false) {
                throw new \Exception('Gagal melakukan decode data base64.');
            }

            Storage::disk('public')->put($path, $data);
        } else {
            throw new \Exception('Format data URI tidak valid.');
        }
    }

    public function store_kelas_data(Request $request)
    {
        $request->validate([
            'bilangan' => 'required|integer|unique:' . Kelas::class,
            'romawi' => 'required|string|uppercase|unique:' . Kelas::class,
            'pengucapan' => [
                'required',
                'string',
                'unique:' . Kelas::class,
                'regex:/^([A-Z][a-z]*\s*)+$/'
            ],
        ]);

        kelas::create($request->all());
        return redirect()->back();
    }

    public function update_kelas_data(Request $request, int $id)
    {
        $request->validate([
            'bilangan' => ['required','integer',Rule::unique(Kelas::class)->ignore($id)],
            'romawi' => ['required','string','uppercase',Rule::unique(Kelas::class)->ignore($id)],
            'pengucapan' => [
                'required',
                'string',
                Rule::unique(Kelas::class)->ignore($id),
                'regex:/^([A-Z][a-z]*\s*)+$/'
            ],
        ]);
        $kelas = Kelas::find($id);
        if(!$kelas) return abort(404, 'Kelas tidak ditemukan');
        $kelas->update($request->all());
        $kelas->save();
        return redirect()->back();
    }

    public function delete_kelas_data(Request $request, int $id)
    {
        $kelas = Kelas::find($id);
        if (!$kelas) return abort(404, 'Kelas tidak ditemukan');
        $kelas->delete();
        return redirect()->back();
    }

    public function store_kelas_kategori_data(Request $request)
    {
        $request->validate([
            'kepanjangan' => [
                'required',
                'string',
                'unique:' . KelasKategori::class,
                'regex:/^([A-Z][a-z]*\s*)+$/',
            ],
            'kependekan' => 'required|string|uppercase|unique:' . KelasKategori::class
        ]);
        KelasKategori::create($request->all());
        return redirect()->back();
    }

    public function update_kelas_kategori_data(Request $request, int $id)
    {
        $request->validate([
            'kepanjangan' => [
                'required',
                'string',
                Rule::unique(KelasKategori::class)->ignore($id),
                'regex:/^([A-Z][a-z]*\s*)+$/',
            ],
            'kependekan' => ['required','string','uppercase', Rule::unique(KelasKategori::class)->ignore($id)],
        ]);
        $kelas_kategori = KelasKategori::find($id);
        if (!$kelas_kategori) return abort(404, 'Kelas kategori tidak ditemukan');
        $kelas_kategori->update($request->all());
        $kelas_kategori->save();
        return redirect()->back();
    }

    public function delete_kelas_kategori_data(Request $request, int $id)
    {
        $kelas_kategori = KelasKategori::find($id);
        if (!$kelas_kategori) return abort(404, 'Kelas kategori tidak ditemukan');
        $kelas_kategori->delete();
        return redirect()->back();
    }

    public function store_mapel_data(Request $request)
    {
        $request->validate([
            'kepanjangan' => [
                'required',
                'string',
                'unique:' . Mapel::class,
                'regex:/^([A-Z][a-z]*\s*)+$/',
            ],
            'kependekan' => 'required|string|uppercase|unique:' . Mapel::class,
        ]);
        Mapel::create($request->all());
        return redirect()->back();
    }

    public function update_mapel_data(Request $request, int $id)
    {
        $request->validate([
            'kepanjangan' => [
                'required',
                'string',
                Rule::unique(Mapel::class)->ignore($id),
                'regex:/^[A-Z][a-zA-Z0-9&\s]*$/',
            ],
            'kependekan' => [
                'required',
                'string',
                'uppercase',
                Rule::unique(Mapel::class)->ignore($id),
            ]
        ]);

        $mapel = Mapel::find($id);
        if (!$mapel) return abort(404, 'Mapel tidak ditemukan');
        $mapel->update($request->all());
        $mapel->save();
        return redirect()->back();
    }

    public function delete_mapel_data(Request $request, int $id)
    {
        $mapel = Mapel::find($id);
        if (!$mapel) return abort(404, 'Mapel tidak ditemukan');
        $mapel->delete();
        return redirect()->back();
    }
}
