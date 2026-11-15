import Swal from 'sweetalert2';

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3200,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.onmouseenter = Swal.stopTimer;
    toast.onmouseleave = Swal.resumeTimer;
  },
});

export const swalSuccess = (title: string, text?: string) => {
  return Toast.fire({
    icon: 'success',
    title,
    text,
  });
};

export const swalError = (title: string, text?: string) => {
  return Swal.fire({
    icon: 'error',
    title,
    text,
    confirmButtonText: 'Mengerti',
    confirmButtonColor: '#6D28D9',
  });
};

export const swalConfirm = async (
  title: string,
  text: string,
  confirmButtonText = 'Ya, Lanjutkan'
): Promise<boolean> => {
  const result = await Swal.fire({
    icon: 'warning',
    title,
    text,
    showCancelButton: true,
    confirmButtonText,
    cancelButtonText: 'Batal',
    confirmButtonColor: '#DC2626',
    cancelButtonColor: '#64748B',
    reverseButtons: true,
  });
  return result.isConfirmed;
};
