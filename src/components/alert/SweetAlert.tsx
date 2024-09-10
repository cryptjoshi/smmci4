import React from 'react';
import Swal from 'sweetalert2';
import withReactContent from 'sweetalert2-react-content';

export type sweettype = {
    title:string;
    text: string;
    icon: string;
    showCancelButton: boolean,
    confirmButtonText: string;
    cancelButtonText: string;
}

const MySwal = withReactContent(Swal);
//@ts-ignore
const AlertButton: React.FC<sweettype> = (props:sweettype) => {
    const {title,text,icon,showCancelButton,confirmButtonText,cancelButtonText} = props
  const handleClick = () => {
    MySwal.fire({
      title: title,
      text: 'This action cannot be undone!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, continue!',
      cancelButtonText: 'No, cancel!',
      // customClass: {
      //   container: 'swal2-container',
      //   popup: 'swal2-popup',
      //  // header: 'swal2-header',
      //   title: 'swal2-title',
      //   closeButton: 'swal2-close-button',
      //   icon: 'swal2-icon',
      //   image: 'swal2-image',
      //   content: 'swal2-content',
      //   input: 'swal2-input',
      //   actions: 'swal2-actions',
      //   confirmButton: 'swal2-confirm-button',
      //   cancelButton: 'swal2-cancel-button',
      //   footer: 'swal2-footer',
      // },
    }).then((result:any) => {
      if (result.isConfirmed) {
        MySwal.fire('Confirmed!', 'Your action was successful.', 'success');
      } else if (result.isDismissed) {
        MySwal.fire('Cancelled', 'Your action was cancelled.', 'error');
      }
    });
  };

  return (
    <button onClick={handleClick}>
      Show Alert
    </button>
  );
};

export default AlertButton;
