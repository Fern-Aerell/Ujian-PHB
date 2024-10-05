export function stringFormatDate(str: string): string
{
    return new Date(str).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}

export function stringFormatDateWithDay(str: string): string
{
    return new Date(str).toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
}