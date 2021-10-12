import React, { DetailedHTMLProps, FC, forwardRef, HTMLAttributes } from 'react';
import { OrderStatuses } from '../../interfaces/order.interface';

interface Props extends DetailedHTMLProps<HTMLAttributes<HTMLSpanElement>, HTMLSpanElement> {}

const DangerBadge = forwardRef<HTMLSpanElement, Props>(({ children, className, ...props }, ref) => {
	return (
		<span ref={ref} className={`w-16 text-center text-xs py-1 px-2 rounded-lg bg-red-500 text-white ${className || ''}`} {...props}>
			{children}
		</span>
	);
});

export default DangerBadge;
