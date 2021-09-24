import React, { DetailedHTMLProps, InputHTMLAttributes, forwardRef } from 'react';

interface Props extends DetailedHTMLProps<InputHTMLAttributes<HTMLInputElement>, HTMLInputElement> {
	inputSize?: 'sm' | 'md';
}

const Input = forwardRef<HTMLInputElement, Props>(({ inputSize, ...props }, ref) => {
	const h = {
		sm: 'h-10',
		md: 'h-12',
	}[inputSize || 'md'];
	return (
		<input
			ref={ref}
			{...props}
			className={`w-full border-gray-400 border-2 rounded-lg ${h} px-4 my-3 focus:outline-none focus:border-blue-400 focus:placeholder-transparent`}
		/>
	);
});

export default Input;
