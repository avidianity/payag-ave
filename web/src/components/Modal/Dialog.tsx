import React, { FC } from 'react';

type Props = {};

const Dialog: FC<Props> = ({ children }) => {
	return (
		<div
			className='opacity-100 rounded-md z-20 bg-white w-10/12 md:w-7/12 lg:w-5/12 fixed left-1/2 top-1/2 mx-auto overflow-hidden outline-0 transition-opacity ease-linear'
			style={{ transform: 'translate(-50%, -50%)' }}>
			{children}
		</div>
	);
};

export default Dialog;
