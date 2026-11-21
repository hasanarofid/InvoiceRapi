export const formatIDR = (val: number | string | null | undefined): string =>
  new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(Number(val || 0));

export const formatDateID = (val: string | null | undefined): string => {
  if (!val) return '-';
  const date = new Date(val);
  if (Number.isNaN(date.getTime())) return String(val);
  return new Intl.DateTimeFormat('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  }).format(date);
};

export const formatQty = (val: number | string | null | undefined): string => {
  const num = Number(val || 0);
  return new Intl.NumberFormat('id-ID', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 2,
  }).format(num);
};
