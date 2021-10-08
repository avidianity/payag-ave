import React, { DetailedHTMLProps, forwardRef, SelectHTMLAttributes } from 'react';

interface Props extends DetailedHTMLProps<SelectHTMLAttributes<HTMLSelectElement>, HTMLSelectElement> {
	inputSize?: 'sm' | 'md';
}

const Select = forwardRef<HTMLSelectElement, Props>(({ children, className, inputSize, ...props }, ref) => {
	const h = {
		sm: 'h-10',
		md: 'h-12',
	}[inputSize || 'md'];

	return (
		<select
			{...props}
			ref={ref}
			className={`w-full border-gray-400 border-2 rounded-lg ${h} px-4 my-3 focus:outline-none focus:border-blue-400 focus:placeholder-transparent ${
				className || ''
			}`}>
			{children}
		</select>
	);
});

export default Select;
