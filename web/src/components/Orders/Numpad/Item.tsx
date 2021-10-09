import React, { FC } from 'react';

type Props = {};

const Item: FC<Props> = (props) => {
	return (
		<div className='h-14 w-14 bg-gray-200 flex items-center justify-center font-bold text-2xl cursor-pointer hover:bg-gray-800 hover:text-white'>
			{props.children}
		</div>
	);
};

export default Item;
