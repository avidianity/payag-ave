import React, { FC } from 'react';

type Props = {
	title?: string;
	onClose?: () => void;
};

const Head: FC<Props> = ({ title, onClose }) => {
	return (
		<div className='px-8 py-4 flex'>
			<span className='text-2xl'>{title}</span>
			<span
				className='ml-auto self-center pt-1 cursor-pointer'
				onClick={(e) => {
					e.preventDefault();
					onClose?.();
				}}>
				<i className='fas fa-times text-2xl'></i>
			</span>
		</div>
	);
};

export default Head;
