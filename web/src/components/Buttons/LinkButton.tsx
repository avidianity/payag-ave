import React, { ButtonHTMLAttributes, DetailedHTMLProps, forwardRef } from 'react';

interface Props extends DetailedHTMLProps<ButtonHTMLAttributes<HTMLAnchorElement>, HTMLAnchorElement> {
	buttonSize?: 'sm' | 'md' | 'lg' | 'xl';
	color?: 'gray' | 'red' | 'yellow' | 'green' | 'blue' | 'indigo' | 'purple' | 'pink';
}

const LinkButton = forwardRef<HTMLAnchorElement, Props>(({ buttonSize, color, ...props }, ref) => {
	const [h, px] = {
		sm: ['py-1', 'px-3'],
		md: ['py-2', 'px-4'],
		lg: ['py-3', 'px-5'],
		xl: ['py-4', 'px-6'],
	}[buttonSize || 'md'];

	return (
		<a
			ref={ref}
			{...props}
			className={`flex items-center ${h} rounded-lg bg-${color || 'blue'}-600 text-white ${px} hover:bg-${color || 'blue'}-700 ${
				props.className || ''
			} disabled:opacity-70`}>
			{props.children}
		</a>
	);
});

export default LinkButton;
