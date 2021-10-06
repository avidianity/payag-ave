import React, { FC } from 'react';
import CircleRipple from './CircleRipple';

type Props = {};

const RippleButton: FC<Props> = (props) => {
	return (
		<div className='relative inline-flex rounded-md shadow-sm'>
			<button
				color='yellow'
				className='inline-flex items-center px-4 py-2 border border-purple-400 text-base leading-6 font-medium rounded-md text-purple-800 bg-white hover:text-purple-700 focus:border-purple-300 transition ease-in-out duration-150'>
				Transactions
			</button>
			<CircleRipple />
		</div>
	);
};

export default RippleButton;
