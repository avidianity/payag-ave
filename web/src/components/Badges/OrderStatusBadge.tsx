import React, { FC } from 'react';
import { OrderStatuses } from '../../interfaces/order.interface';

type Props = {
	status: OrderStatuses;
};

const OrderStatusBadge: FC<Props> = ({ status }) => {
	const className = (() => {
		switch (status) {
			case 'paid':
				return 'bg-green-400 text-white';
			case 'unpaid':
				return 'bg-red-400 text-white';
			case 'debt':
				return 'bg-indigo-400 text-white';
			default:
				return 'bg-pink-400 text-white';
		}
	})();
	return (
		<span className={`w-14 text-center text-xs py-1 px-2 rounded-lg cursor-pointer ${className}`} title={status} data-tip={status}>
			{status}
		</span>
	);
};

export default OrderStatusBadge;
