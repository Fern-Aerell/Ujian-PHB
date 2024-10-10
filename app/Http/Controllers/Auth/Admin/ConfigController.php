<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Error;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConfigController extends Controller
{
    public function index()
    {
        return Inertia::render('Auth/Admin/Config/Config');
    }

    public function update_activity_data(Request $request)
    {

        $config = Config::first();

        if (!$config) {
            throw new Error('Config not found');
        }

        $request->validate([
            'activity_type' => 'required|string|max:255',
            'activity_title' => 'required|string|max:255',
            'activity_title_abbreviation' => 'required|string|max:255',
        ]);

        $config->activity_type = $request->activity_type;
        $config->activity_title = $request->activity_title;
        $config->activity_title_abbreviation = $request->activity_title_abbreviation;
        $config->save();

        return redirect()->back();
    }

    public function update_exam_schedule_data(Request $request)
    {

        $config = Config::first();

        if (!$config) {
            throw new Error('Config not found');
        }

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

        $config->exam_date_start = $request->exam_date_start;
        $config->exam_date_end = $request->exam_date_end;
        $config->holiday_date = $request->holiday_date;
        $config->save();

        return redirect()->back();
    }

    public function update_exam_time_data(Request $request)
    {

        $config = Config::first();

        if (!$config) {
            throw new Error('Config not found');
        }

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

        $config->exam_time_start = $request->exam_time_start;
        $config->exam_time_end = $request->exam_time_end;
        $config->save();

        return redirect()->back();
    }

    public function update_school_data(Request $request)
    {

        $config = Config::first();

        if (!$config) {
            throw new Error('Config not found');
        }

        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'school_name' => 'required|string|max:255'
        ]);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $path = $file->storeAs('logo', 'logo.png', 'public');
            $config->logo = 'storage/' . $path;
        }

        $config->school_name = $request->school_name;
        $config->save();

        return redirect()->back();
    }
}
