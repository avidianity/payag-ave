import React, { FC } from 'react';

type Props = {
	onClick?: () => void;
};

const Item: FC<Props> = (props) => {
	return (
		<div
			className='h-16 w-16 bg-gray-200 flex items-center justify-center font-bold text-2xl cursor-pointer hover:bg-gray-800 hover:text-white'
			onClick={(e) => {
				e.preventDefault();
				props.onClick?.();
			}}>
			{props.children}
		</div>
	);
};

export default Item;
