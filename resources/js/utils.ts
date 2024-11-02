export function stringFormatDate(str: string): string
{
    return new Date(str).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}

export function stringFormatDateWithDay(str: string): string
{
    return new Date(str).toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
}

export function getCurrentDateTimeString(): string {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0'); // Bulan dimulai dari 0, jadi ditambah 1
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

export function getDatesExcludingHolidays(startDate: string, endDate: string, holidayDate: string): Date[] {
    const start = new Date(startDate);
    const end = new Date(endDate);
    end.setDate(end.getDate() - 1); // Mencegah tanggal akhir masuk

    const holidays = holidayDate.split(",").map(day => parseInt(day, 10));
    const dates: Date[] = [];
    
    while (start <= end) {
        const day = start.getDate();

        // Cek apakah hari ini ada di holiday list
        if (!holidays.includes(day)) {
            // Set jam, menit, dan detik ke 0
            const dateWithTime = new Date(start);
            dateWithTime.setHours(0, 0, 0, 0);
            dates.push(dateWithTime); // Tambahkan salinan objek Date dengan waktu 00:00:00
        }

        // Tambah satu hari
        start.setDate(start.getDate() + 1);
    }

    return dates;
}

export function formatDateForDatabase(date: Date): string {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Bulan ditambahkan 1 karena di JavaScript bulan dimulai dari 0
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

export function formatDateToIndonesianLongDateString(date: Date): string {
    const options: Intl.DateTimeFormatOptions = {
        weekday: 'long',
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    };

    return new Intl.DateTimeFormat('id-ID', options).format(date);
}