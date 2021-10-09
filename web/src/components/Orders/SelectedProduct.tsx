import React, { FC } from 'react';
import { formatNumber } from '../../helpers';

type Props = {
	name: string;
	price: number;
	quantity: number;
	onSelect: (active: boolean) => void;
	active: boolean;
};

const SelectedProduct: FC<Props> = ({ price, quantity, name, onSelect, active }) => {
	return (
		<div
			className={`border first:mt-2 border-gray-300 px-4 py-2 border-t-0 first:border-t cursor-pointer ${
				active ? 'bg-gray-200' : ''
			}`}
			onClick={(e) => {
				e.preventDefault();
				onSelect(active);
			}}>
			<div className='flex'>
				<p className='font-bold text-lg text-gray-600'>{name}</p>
				<span className='ml-auto font-bold text-sm pt-1 text-gray-600'>₱{formatNumber(price * quantity)}</span>
			</div>
			<p className='text-gray-500'>
				{quantity} item/s at ₱{price}/item
			</p>
		</div>
	);
};

export default SelectedProduct;
