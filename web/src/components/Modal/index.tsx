import React, { FC, Fragment } from 'react';

type Props = {
	onClose?: () => void;
};

const Modal: FC<Props> = ({ children, onClose }) => {
	return (
		<Fragment>
			{children}
			<div
				className='bg-black cursor-pointer opacity-50 h-screen w-screen z-10 fixed top-0 left-0 overflow-x-hidden overflow-y-auto outline-0 transition-opacity ease-linear'
				onClick={(e) => {
					e.preventDefault();
					onClose?.();
				}}></div>
		</Fragment>
	);
};

export default Modal;
