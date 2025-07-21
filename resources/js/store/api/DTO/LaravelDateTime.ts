/**
 * Представляет дату и время в формате Laravel
 */
export interface LaravelDateTime {
    date: string; // Формат: "YYYY-MM-DD HH:MM:SS.000000"
    timezone_type: number;
    timezone: string;
}
