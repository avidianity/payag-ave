import React, { ButtonHTMLAttributes, DetailedHTMLProps, forwardRef } from 'react';

interface Props extends DetailedHTMLProps<ButtonHTMLAttributes<HTMLButtonElement>, HTMLButtonElement> {
	buttonSize?: 'sm' | 'md' | 'lg' | 'xl';
	color?: 'gray' | 'red' | 'yellow' | 'green' | 'blue' | 'indigo' | 'purple' | 'pink';
	tip?: string;
}

const Button = forwardRef<HTMLButtonElement, Props>(({ buttonSize, color, tip, ...props }, ref) => {
	const [h, px] = {
		sm: ['py-1', 'px-3'],
		md: ['py-2', 'px-4'],
		lg: ['py-3', 'px-5'],
		xl: ['py-4', 'px-6'],
	}[buttonSize || 'md'];

	return (
		<button
			ref={ref}
			{...props}
			className={`flex items-center ${h} rounded-lg bg-${color || 'blue'}-600 text-white ${px} hover:bg-${color || 'blue'}-700 ${
				props.className || ''
			}`}>
			{props.children}
		</button>
	);
});

export default Button;
