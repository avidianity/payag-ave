import React, { FC } from 'react';

type Props = {};

const CircleRipple: FC<Props> = (props) => {
	return (
		<span className='flex absolute h-3 w-3 top-0 right-0 -mt-1 -mr-1'>
			<span className='animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75'></span>
			<span className='relative inline-flex rounded-full h-3 w-3 bg-purple-500'></span>
		</span>
	);
};

export default CircleRipple;
