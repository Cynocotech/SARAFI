const FA = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];

export function toFarsi(str) {
    return String(str).replace(/\d/g, (d) => FA[+d]);
}

export function farsiNum(value, decimals = 0) {
    if (value === null || value === undefined || value === '') return '—';
    const num = Number(value);
    if (isNaN(num)) return '—';
    return toFarsi(num.toLocaleString('en', { minimumFractionDigits: decimals, maximumFractionDigits: decimals }));
}
